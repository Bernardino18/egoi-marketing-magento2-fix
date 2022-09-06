<?php

namespace Egoi\Marketing\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Class UpgradeData
 *
 * @package Egoi\Marketing\Setup
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '3.5.0', '<')) {
            try {
                $setup->run(
                    "ALTER TABLE `{$setup->getTable('egoi_autoresponders')}` ADD COLUMN `payment_method` varchar(255) DEFAULT NULL"
                );
                $setup->run(
                    "ALTER TABLE `{$setup->getTable('egoi_autoresponders')}` ADD COLUMN `shipping_method` varchar(255) DEFAULT NULL"
                );
            } catch (\Exception $e) {

            }
        }

        if (version_compare($context->getVersion(), '3.5.1', '<')) {
            try {


                $setup->run(
                    "ALTER TABLE `{$setup->getTable('egoi_autoresponders_events')}` ADD COLUMN `extra` varchar(255) DEFAULT NULL"
                );
                $setup->run(
                    "ALTER TABLE `{$setup->getTable('egoi_autoresponders_events')}` CHANGE COLUMN `message` `message` varchar(255) DEFAULT NULL"
                );
                $setup->run(
                    "ALTER TABLE `{$setup->getTable('egoi_autoresponders_events')}` ADD INDEX `extra` (`extra`) comment '';"
                );

            } catch (\Exception $e) {

            }
        }

        if (version_compare($context->getVersion(), '3.6.0', '<')) {
            try {

                $setup->run(
                    " 
                    CREATE TABLE `{$setup->getTable('egoi_conversions')}` (
                      `conversion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `utm_term` varchar(255) DEFAULT NULL,
                      `utm_campaign` varchar(255) DEFAULT NULL,
                      `eg_sub` varchar(255) DEFAULT NULL,
                      `eg_cam` varchar(255) DEFAULT NULL,
                      `eg_list` varchar(255) DEFAULT NULL,
                      `order_id` varchar(50) DEFAULT NULL,
                      `order_amount` decimal(12,4) DEFAULT NULL,
                      `email` varchar(255) DEFAULT NULL,
                      `created_at` datetime DEFAULT NULL,
                      PRIMARY KEY (`conversion_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 
                    "
                );

            } catch (\Exception $e) {

            }
        }

    }
}