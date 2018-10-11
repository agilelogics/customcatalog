<?php

/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Eav\Model\Entity\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Agilelogics\CustomCatalog\Setup\ProductsSetup;

class Products extends AbstractEntity
{
    protected $_storeId = null;
    protected $_storeManager;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->setType(ProductsSetup::ENTITY_TYPE_CODE);
        $this->setConnection(ProductsSetup::ENTITY_TYPE_CODE . '_read', ProductsSetup::ENTITY_TYPE_CODE . '_write');
        $this->_storeManager = $storeManager;
    }

    protected function _getDefaultAttributes()
    {
        return ['created_at', 'updated_at'];
    }

    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    public function getStoreId()
    {
        if ($this->_storeId === null) {
            return $this->_storeManager->getStore()->getId();
        }
        return $this->_storeId;
    }

    protected function _saveAttribute($object, $attribute, $value)
    {
        $table = $attribute->getBackend()->getTable();
        if (!isset($this->_attributeValuesToSave[$table])) {
            $this->_attributeValuesToSave[$table] = [];
        }

        $entityIdField = $attribute->getBackend()->getEntityIdField();
        $storeId = $object->getStoreId()?:Store::DEFAULT_STORE_ID;
        $data = [
            $entityIdField => $object->getId(),
            'attribute_id' => $attribute->getId(),
            'value' => $this->_prepareValueForSave($value, $attribute),
            'store_id' => $storeId,
        ];

        if (!$this->getEntityTable() || $this->getEntityTable() == \Magento\Eav\Model\Entity::DEFAULT_ENTITY_TABLE) {
            $data['entity_type_id'] = $object->getEntityTypeId();
        }

        if ($attribute->isScopeStore()) {
            //Update attribute value for store            
            $this->_attributeValuesToSave[$table][] = $data;
        } 
        elseif ($attribute->isScopeWebsite() && $storeId != Store::DEFAULT_STORE_ID) {
            //Update attribute value for website            
            $storeIds = $this->_storeManager->getStore($storeId)->getWebsite()->getStoreIds(true);
            foreach ($storeIds as $storeId) {
                $data['store_id'] = (int)$storeId;
                $this->_attributeValuesToSave[$table][] = $data;
            }
        } else {
            //Update global attribute value             
            $data['store_id'] = Store::DEFAULT_STORE_ID;
            $this->_attributeValuesToSave[$table][] = $data;
        }
        return $this;
    }
}
