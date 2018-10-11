<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;

/**
 * @codeCoverageIgnore
 */
class ProductsSetup extends EavSetup
{
    /**
     * Entity type for Products EAV attributes
     */
    const ENTITY_TYPE_CODE = 'agilelogics_products';

    /**
     * Retrieve Entity Attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getAttributes()
    {
        $attributes = [];

        $attributes['product_id'] = [
            'type' => 'varchar',
            'label' => 'ProductID',
            'input' => 'text',
            'required' => true,
            'sort_order' => 10,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General'
        ];

		$attributes['copywrite_info'] = [
            'type' => 'text',
            'label' => 'CopyWriteInfo',
            'input' => 'textarea',
            'required' => true,
            'sort_order' => 20,
            'global' => ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General'
        ];
		
		
		$attributes['vpn'] = [
            'type' => 'varchar',
            'label' => 'VPN',
            'input' => 'text',
            'required' => true,
            'sort_order' => 30,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General'
        ];
       
       
		$attributes['sku'] = [
            'type' => 'varchar',
            'label' => 'SKU',
            'input' => 'text',
            'required' => true,
            'sort_order' => 40,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'group' => 'General'
        ];

        return $attributes;
    }

    /**
     * Retrieve default entities: products
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        $entities = [
            self::ENTITY_TYPE_CODE => [
                'entity_model' => 'Agilelogics\CustomCatalog\Model\ResourceModel\Products',
                'attribute_model' => 'Agilelogics\CustomCatalog\Model\ResourceModel\Eav\Attribute',
                'table' => self::ENTITY_TYPE_CODE . '_entity',
                'increment_model' => null,
                'additional_attribute_table' => self::ENTITY_TYPE_CODE . '_eav_attribute',
                'entity_attribute_collection' => 'Agilelogics\CustomCatalog\Model\ResourceModel\Attribute\Collection',
                'attributes' => $this->getAttributes()
            ]
        ];

        return $entities;
    }
}
