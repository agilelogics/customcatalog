<?php

/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Products extends AbstractModel implements IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'agilelogics_customcatalog_products';

    /**
     * @var string
     */
    protected $_cacheTag = 'agilelogics_customcatalog_products';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'agilelogics_customcatalog_products';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Agilelogics\CustomCatalog\Model\ResourceModel\Products');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Save from collection data
     *
     * @param array $data
     * @return $this|bool
     */
    public function saveCollection(array $data)
    {
        if (isset($data[$this->getId()])) {
            $this->addData($data[$this->getId()]);
            $this->getResource()->save($this);
        }
        return $this;
    }


}
