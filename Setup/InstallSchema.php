<?php

namespace HTCMage\ProductAttachment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'htcmage_productattachment'
         */
        if (!$installer->tableExists('htcmage_productattachment')) {
            $table = $installer->getConnection()->newTable(
            $installer->getTable('htcmage_productattachment')
            )->addColumn(
                'id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Attachment ID'
            )->addColumn(
                'icon',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Icon'
            )->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Title'
            )->addColumn(
                'file',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Attachment File'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Status'
            )->addColumn(
                'type',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Type'
            )->addColumn(
                'url',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Url'
            )->addColumn(
                'limited',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Limited'
            )->addColumn(
                'number_of_download',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default' => '0'],
                'Number of Download'
            )->addColumn(
                'display',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Display'
            )->addColumn(
                'position',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'default' => '0'],
                'Position'
            );
            $installer->getConnection()->createTable($table);
        }
        
        if (!$installer->tableExists('htcmage_productattachment_product')) {
            $table = $installer->getConnection()->newTable(
            $installer->getTable('htcmage_productattachment_product')
            )->addColumn(
                'id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )
            ->addColumn(
                'id_attachment',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Id Attachment'
            )->addColumn(
                'id_product',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false,'unsigned'=>true],
                'Id Product'
            )
            ->addForeignKey(
                $setup->getFkName('htcmage_productattachment_product', 'id_attachment', 'htcmage_productattachment', 'id'),
                'id_attachment',
                $setup->getTable('htcmage_productattachment'),
                'id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $setup->getFkName('htcmage_productattachment_product', 'id_product', 'catalog_product_entity', 'entity_id'),
                'id_product',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'htcmage_productattachment_store_view'
         */
        if (!$installer->tableExists('htcmage_productattachment_store_view')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('htcmage_productattachment_store_view')
            )->addColumn(
                'id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Link Id'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Store View'
            )->addColumn(
                'id_attachment',
                Table::TYPE_SMALLINT,
                255,
                ['nullable' => false],
                'Id Product Attachment'
            )->addForeignKey(
                $setup->getFkName('htcmage_productattachment_store_view', 'id_attachment', 'htcmage_productattachment', 'id'),
                'id_attachment',
                $setup->getTable('htcmage_productattachment'),
                'id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                    $setup->getFkName('htcmage_productattachment_store_view', 'store_id', 'store', 'store_id'),
                    'store_id',
                    $setup->getTable('store'),
                    'store_id',
                    Table::ACTION_CASCADE
                );
            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'htcmage_productattachment_store_view'
         */
        if (!$installer->tableExists('htcmage_productattachment_customer_group')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('htcmage_productattachment_customer_group')
            )->addColumn(
                'id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Link Id'
            )->addColumn(
                'group_id',
                Table::TYPE_INTEGER,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Customer Group'
            )->addColumn(
                'id_attachment',
                Table::TYPE_SMALLINT,
                255,
                ['nullable' => false],
                'Id Product Attachment'
            )->addForeignKey(
                $setup->getFkName('htcmage_productattachment_customer_group', 'id_attachment', 'htcmage_productattachment', 'id'),
                'id_attachment',
                $setup->getTable('htcmage_productattachment'),
                'id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                    $setup->getFkName('htcmage_productattachment_customer_group', 'store_id', 'customer_group', 'customer_group_id'),
                    'group_id',
                    $setup->getTable('customer_group'),
                    'customer_group_id',
                    Table::ACTION_CASCADE
                );
            $installer->getConnection()->createTable($table);
        }

        /**
         * Create table 'htcmage_productattachment_store_view'
         */
        if (!$installer->tableExists('htcmage_productattachment_display')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('htcmage_productattachment_display')
            )->addColumn(
                'id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Link Id'
            )->addColumn(
                'display',
                Table::TYPE_INTEGER,
                5,
                ['unsigned' => true, 'nullable' => false],
                'Customer Group'
            )->addColumn(
                'id_attachment',
                Table::TYPE_SMALLINT,
                255,
                ['nullable' => false],
                'Id Product Attachment'
            )->addForeignKey(
                $setup->getFkName('htcmage_productattachment_display', 'id_attachment', 'htcmage_productattachment', 'id'),
                'id_attachment',
                $setup->getTable('htcmage_productattachment'),
                'id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
