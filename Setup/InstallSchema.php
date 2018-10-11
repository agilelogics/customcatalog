<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */
namespace Agilelogics\CustomCatalog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Agilelogics\CustomCatalog\Setup\EavTablesSetupFactory;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var EavTablesSetupFactory
     */
    protected $eavTablesSetupFactory;

    /**
     * Init
     *
     * @internal param EavTablesSetupFactory $EavTablesSetupFactory
     */
    public function __construct(EavTablesSetupFactory $eavTablesSetupFactory)
    {
        $this->eavTablesSetupFactory = $eavTablesSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) //@codingStandardsIgnoreLine
    {
        $setup->startSetup();

        $tableName = ProductsSetup::ENTITY_TYPE_CODE . '_entity';
        /**
         * Create entity Table
         */
        $table = $setup->getConnection()
            ->newTable($setup->getTable($tableName))
            ->addColumn('entity_id', Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'EntityID')
            ->setComment('Entity Table');        
        $table->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Created timestamp')
              ->addColumn('updated_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE], 'Update timestamp');

        $setup->getConnection()->createTable($table);

        $eavTablesSetup = $this->eavTablesSetupFactory->create(['setup' => $setup]);
        $eavTablesSetup->createEavTables(ProductsSetup::ENTITY_TYPE_CODE);

        $setup->endSetup();
    }
}
