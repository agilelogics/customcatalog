<?php
/**
 * Module: Agilelogics > CustomCatalog
 * @author: Qaisar Ashfaq
 */

namespace Agilelogics\CustomCatalog\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Products setup factory
     *
     * @var ProductsSetupFactory
     */
    protected $productsSetupFactory;

    /**
     * Init
     *
     * @param ProductsSetupFactory $productsSetupFactory
     */
    public function __construct(ProductsSetupFactory $productsSetupFactory)
    {
        $this->productsSetupFactory = $productsSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) //@codingStandardsIgnoreLine
    {
        /** @var ProductsSetup $productsSetup */
        $productsSetup = $this->productsSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $productsSetup->installEntities();
        $entities = $productsSetup->getDefaultEntities();
        foreach ($entities as $entityName => $entity) {
            $productsSetup->addEntityType($entityName, $entity);
        }

        $setup->endSetup();
    }
}
