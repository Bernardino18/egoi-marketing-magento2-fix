<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model\ResourceModel;

/**
 * Class Autoresponders
 *
 * @package Egoi\Marketing\Model\ResourceModel
 */
class Autoresponders extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @var string
     */
    protected $_idFieldName = 'autoresponder_id';

    /**
     * Initialize resource model
     * Get tablename from config
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('egoi_autoresponders', 'autoresponder_id');
    }

    /**
     * @param $table
     * @param $where
     *
     * @return int
     */
    public function deleteData($table, $where)
    {

        return $this->getConnection()->delete($table, $where);

    }
}
