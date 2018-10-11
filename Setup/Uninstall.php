<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{
    protected $tablesToUninstall = [
        ProductsSetup::ENTITY_TYPE_CODE . '_entity',
        ProductsSetup::ENTITY_TYPE_CODE . '_eav_attribute',
        ProductsSetup::ENTITY_TYPE_CODE . '_entity_datetime',
        ProductsSetup::ENTITY_TYPE_CODE . '_entity_decimal',
        ProductsSetup::ENTITY_TYPE_CODE . '_entity_int',
        ProductsSetup::ENTITY_TYPE_CODE . '_entity_text',
        ProductsSetup::ENTITY_TYPE_CODE . '_entity_varchar'
    ];

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        foreach ($this->tablesToUninstall as $table) {
            if ($setup->tableExists($table)) {
                $setup->getConnection()->dropTable($setup->getTable($table));
            }
        }
        $setup->endSetup();
    }
}