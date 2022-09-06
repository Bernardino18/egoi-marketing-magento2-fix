<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model\ResourceModel\Subscriber;

/**
 * Class Collection
 *
 * @package Egoi\Marketing\Model\ResourceModel\Subscriber
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Constructor
     * Configures collection
     *
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->_init('Egoi\Marketing\Model\Subscriber', 'Egoi\Marketing\Model\ResourceModel\Subscriber');
    }

    /**
     * @return $this
     */
    public function addActiveSubscribers()
    {

        $this->addFieldToFilter('subscriber_status', 1);
        $this->addFieldToFilter('cellphone', ['neq' => '']);

        return $this;
    }

    /**
     * @param string $customerGroups
     *
     * @return $this
     */
    public function addCustomerGroups($customerGroups = '')
    {

        if (!$customerGroups) {
            return $this;
        }

        if (!is_array($customerGroups)) {
            $customerGroups = explode(',', $customerGroups);
        }

        $select = $this->getSelect();
        $select->joinInner(
            $this->getTable('customer_entity'),
            'main_table.customer_id=' . $this->getTable('customer_entity') . '.entity_id',
            []
        );
        $select->where($this->getTable('customer_entity') . '.group_id IN (?)', $customerGroups);

        return $this;
    }

    /**
     * @param string $storeIds
     *
     * @return $this
     */
    public function addStoreIds($storeIds = '')
    {

        if (!$storeIds) {
            return $this;
        }

        if (!is_array($storeIds)) {
            $storeIds = explode(',', $storeIds);
        }
        $this->addFieldToFilter('main_table.store_id', ['in' => $storeIds]);

        return $this;
    }

    /**
     * @param bool|false $field
     *
     * @return array
     */
    public function getAllIds($field = false)
    {

        if (!$field) {
            return parent::getAllIds();
        }

        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(\Zend_Db_Select::ORDER);
        $idsSelect->reset(\Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(\Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(\Zend_Db_Select::COLUMNS);
        $idsSelect->columns('main_table.' . $field, 'main_table');

        return $this->getConnection()->fetchCol($idsSelect);
    }

}
