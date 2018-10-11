<?php

/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Model\ResourceModel\Products;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Eav\Model\EntityFactory as EavEntityFactory;
use Magento\Eav\Model\ResourceModel\Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_storeId;
    protected $_storeManager;

    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Config $eavConfig,
        ResourceConnection $resource,
        EavEntityFactory $eavEntityFactory,
        Helper $resourceHelper,
        UniversalFactory $universalFactory,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $resource, $eavEntityFactory, $resourceHelper, $universalFactory, $connection);
    }

    protected function _construct()
    {
        $this->_init('Agilelogics\CustomCatalog\Model\Products', 'Agilelogics\CustomCatalog\Model\ResourceModel\Products');
    }

    public function setStore($store)
    {
        $this->setStoreId($this->_storeManager->getStore($store)->getId());
        return $this;
    }

    public function setStoreId($storeId)
    {
        if ($storeId instanceof \Magento\Store\Api\Data\StoreInterface) {
            $storeId = $storeId->getId();
        }
        $this->_storeId = (int)$storeId;
        return $this;
    }

    public function getStoreId()
    {
        if ($this->_storeId === null) {
            $this->setStoreId($this->_storeManager->getStore()->getId());
        }
        return $this->_storeId;
    }

    public function getDefaultStoreId()
    {
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }

    protected function _getLoadAttributesSelect($table, $attributeIds = [])
    {
        if (empty($attributeIds)) {
            $attributeIds = $this->_selectAttributes;
        }
        $storeId = $this->getStoreId();
        $connection = $this->getConnection();

        $entityTable = $this->getEntity()->getEntityTable();
        $indexList = $connection->getIndexList($entityTable);
        $entityIdField = $indexList[$connection->getPrimaryKeyName($entityTable)]['COLUMNS_LIST'][0];

        if ($storeId) {
            $joinCondition = ['t_s.attribute_id = t_d.attribute_id', "t_s.{$entityIdField} = t_d.{$entityIdField}", $connection->quoteInto('t_s.store_id = ?', $storeId),];

            $select = $connection->select()->from(['t_d' => $table], ['attribute_id'])
                                 ->join(['e' => $entityTable], "e.{$entityIdField} = t_d.{$entityIdField}", ['e.entity_id'])
                                 ->where("e.entity_id IN (?)", array_keys($this->_itemsById))
                                 ->where('t_d.attribute_id IN (?)', $attributeIds)
                                 ->joinLeft(['t_s' => $table], implode(' AND ', $joinCondition), [])
                                 ->where('t_d.store_id = ?', $connection->getIfNullSql('t_s.store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID));
        } 
        else {
            $select = $connection->select()->from(['t_d' => $table], ['attribute_id'])
                                 ->join(['e' => $entityTable], "e.{$entityIdField} = t_d.{$entityIdField}", ['e.entity_id'])
                                 ->where("e.entity_id IN (?)", array_keys($this->_itemsById))
                                 ->where('attribute_id IN (?)', $attributeIds)
                                 ->where('store_id = ?', $this->getDefaultStoreId());
        }
        return $select;
    }

    protected function _addLoadAttributesSelectValues($select, $table, $type)
    {
        $storeId = $this->getStoreId();
        if ($storeId) {
            $connection = $this->getConnection();
            $valueExpr = $connection->getCheckSql('t_s.value_id IS NULL', 't_d.value', 't_s.value');
            $select->columns(['default_value' => 't_d.value', 'store_value' => 't_s.value', 'value' => $valueExpr]);
        } 
        else {
            $select = parent::_addLoadAttributesSelectValues($select, $table, $type);
        }
        return $select;
    }

    protected function _joinAttributeToSelect($method, $attribute, $tableAlias, $condition, $fieldCode, $fieldAlias)
    {
        if (isset($this->_joinAttributes[$fieldCode]['store_id'])) {
            $storeId = $this->_joinAttributes[$fieldCode]['store_id'];
        } 
        else {
            $storeId = $this->getStoreId();
        }

        $connection = $this->getConnection();

        if ($storeId != $this->getDefaultStoreId() && !$attribute->isScopeGlobal()) {
            $defCondition  = '(' . implode(') AND (', $condition) . ')';
            $defAlias      = $tableAlias . '_default';
            $defAlias      = $this->getConnection()->getTableName($defAlias);
            $defFieldAlias = str_replace($tableAlias, $defAlias, $fieldAlias);
            $tableAlias    = $this->getConnection()->getTableName($tableAlias);
            $defCondition  = str_replace($tableAlias, $defAlias, $defCondition);
            $defCondition .= $connection->quoteInto(" AND " . $connection->quoteColumnAs("{$defAlias}.store_id", null) . " = ?", $this->getDefaultStoreId());
            $this->getSelect()->{$method}([$defAlias => $attribute->getBackend()->getTable()], $defCondition, []);
            $method = 'joinLeft';
            $fieldAlias = $this->getConnection()->getCheckSql("{$tableAlias}.value_id > 0", $fieldAlias, $defFieldAlias);
            $this->_joinAttributes[$fieldCode]['condition_alias'] = $fieldAlias;
            $this->_joinAttributes[$fieldCode]['attribute'] = $attribute;
        } 
        else {
            $storeId = $this->getDefaultStoreId();
        }
        $condition[] = $connection->quoteInto($connection->quoteColumnAs("{$tableAlias}.store_id", null) . ' = ?', $storeId);
        return parent::_joinAttributeToSelect($method, $attribute, $tableAlias, $condition, $fieldCode, $fieldAlias);
    }
}
