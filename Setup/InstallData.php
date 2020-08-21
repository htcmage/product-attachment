<?php
/**
 * NOTICE OF LICENSE
 *
 * @author HTCMage Team
 * @copyright Copyright (c) 2020 HTCMage (https://www.htcmage.com)
 * @package HTCMage_ProductAttachment
 *
 */

namespace HTCMage\ProductAttachment\Setup;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package HTCMage\ProductAttachment\Setup
 */
class InstallData implements InstallDataInterface
{

    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            'attachment',
            [
                'group' => 'General',
                'type' => 'varchar',
                'label' => 'Attachments',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'input' => 'multiselect',
                'wysiwyg_enabled' => false,
                'source' => 'HTCMage\ProductAttachment\Model\Source\Product\Attachments',
                'required' => false,
                'sort_order' => 16,
                'global' => Attribute::SCOPE_GLOBAL,
                'used_in_product_listing' => true,
                'visible_on_front' => true,
            ]
        );

        $setup->endSetup();
    }
}
