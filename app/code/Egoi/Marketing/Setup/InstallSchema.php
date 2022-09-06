<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();

        $setup->run(
            "
-- ----------------------------
--  Table structure for `egoi_account`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{$setup->getTable('egoi_account')}`(
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL DEFAULT '0',
  `company_name` varchar(255) DEFAULT NULL,
  `company_legal_name` varchar(255) DEFAULT NULL,
  `company_type` varchar(255) DEFAULT NULL,
  `business_activity_code` varchar(255) DEFAULT NULL,
  `date_registration` date DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `signup_date` date DEFAULT NULL,
  `credits` float(8,2) DEFAULT NULL,
  `cron` smallint(2) DEFAULT NULL,
  `notify_user` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='E-Goi - Account Info';
"
        );

        $setup->run(
            "
-- ----------------------------
--  Table structure for `egoi_extra`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{$setup->getTable('egoi_extra')}` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `extra_code` varchar(50) DEFAULT NULL,
  `attribute_code` varchar(50) DEFAULT NULL,
  `system` smallint(11) DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='E-Goi - List Extra Fields';
"
        );

        $setup->run(
            "
-- ----------------------------
--  Table structure for `egoi_lists`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{$setup->getTable('egoi_lists')}`(
  `list_id` int(11) NOT NULL AUTO_INCREMENT,
  `listnum` int(12) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `internal_name` varchar(255) DEFAULT NULL,
  `subs_activos` int(11) DEFAULT NULL,
  `subs_total` int(11) DEFAULT NULL,
  `canal_email`  smallint(6) NOT NULL DEFAULT '1',
  `canal_sms`  smallint(6) NOT NULL DEFAULT '0',
  `is_active`  smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`list_id`),
  UNIQUE KEY `unq_listnum` (`listnum`),
  KEY `listnum` (`listnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='E-Goi - List of Lists';

"
        );

        $setup->run(
            "
-- ----------------------------
--  Table structure for `egoi_subscribers`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{$setup->getTable('egoi_subscribers')}`(
  `subscriber_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `store_id` varchar(255) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  `subscription_method` varchar(255) DEFAULT NULL,
  `list` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cellphone` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `id_card` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `birth_date` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `bounces` int(11) DEFAULT NULL,
  `email_sent` int(11) DEFAULT NULL,
  `email_views` int(11) DEFAULT NULL,
  `referrals` int(11) DEFAULT NULL,
  `referrals_converted` int(11) DEFAULT NULL,
  `clicks` int(11) DEFAULT NULL,
  `sms_sent` int(11) DEFAULT NULL,
  `sms_delivered` int(11) DEFAULT NULL,
  `remove_method` varchar(255) DEFAULT NULL,
  `remove_date` datetime DEFAULT NULL,
  PRIMARY KEY (`subscriber_id`),
  UNIQUE KEY `unq_uid_list` (`uid`,`list`),
  KEY `email_i` (`email`),
  KEY `list_i` (`list`),
  KEY `uid_i` (`uid`),
  KEY `customer_i` (`customer_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='E-Goi - List of subscribers';
"
        );

        $setup->run(
            "
-- ----------------------------
--  Table structure for `egoi_autoresponders`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{$setup->getTable('egoi_autoresponders')}` (
  `autoresponder_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_ids` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `send_moment` varchar(255) NOT NULL DEFAULT 'occurs',
  `active` smallint(6) NOT NULL DEFAULT '0',
  `after_days` smallint(2) DEFAULT NULL,
  `after_hours` smallint(1) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `message` text,
  `number_subscribers` int(11) DEFAULT NULL,
  `send_once`  smallint(6) NOT NULL DEFAULT '1',
  `search` varchar(255) DEFAULT NULL,
  `search_option` varchar(255) NOT NULL  DEFAULT 'eq',
  `order_status` varchar(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  PRIMARY KEY (`autoresponder_id`),
  KEY `event` (`event`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='E-Goi - Autoresponders lists'
"
        );

        $setup->run(
            "
-- ----------------------------
--  Table structure for `egoi_autoresponders_events`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{$setup->getTable('egoi_autoresponders_events')}` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) DEFAULT NULL,
  `autoresponder_id` int(11) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `cellphone` varchar(255) DEFAULT NULL,
  `send_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `sent`  smallint(6) NOT NULL DEFAULT '0',
  `sent_at` datetime DEFAULT NULL,
  `data_object_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `autoresponder_id` (`autoresponder_id`),
  KEY `sent` (`sent`),
  CONSTRAINT `FK_EGOI_EVENT_AUTR` FOREIGN KEY (`autoresponder_id`) REFERENCES `{$setup->getTable('egoi_autoresponders')}` (`autoresponder_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='E-Goi - List of events in queue for autoresponder'
"
        );

        try {
            $setup->run(
                "ALTER TABLE `{$setup->getTable('newsletter_subscriber')}` ADD COLUMN `egoi_udpated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP  ON UPDATE CURRENT_TIMESTAMP"
            );
        } catch (\Exception $e) {

        }
        $setup->endSetup();
    }

}
