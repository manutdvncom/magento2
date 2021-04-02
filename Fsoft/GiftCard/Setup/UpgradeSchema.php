<?php
namespace Fsoft\GiftCard\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $quoteTable = 'quote';
        if (version_compare($context->getVersion(), '2.5.0', '<')) {
            $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'giftcard_code',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => '50',
                    'nullable' => true,
                    'comment' => 'Giftcard Code'
                ]
            );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($quoteTable),
                    'giftcard_base_discount',
                    [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => true,
                    'comment' => 'Giftcard Base Discount'
                ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($quoteTable),
                    'giftcard_discount',
                    [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => true,
                    'comment' => 'Giftcard Discount'
                ]
                );
        }
        $setup->endSetup();
    }
}
