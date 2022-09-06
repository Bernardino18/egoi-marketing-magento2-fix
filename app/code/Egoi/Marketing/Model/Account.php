<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model;

/**
 * Class Autoresponders
 *
 * @package Egoi\Marketing\Model
 */
class Account extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'egoi_account';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'account';

    /**
     * @var Egoi
     */
    protected $_egoi;

    /**
     * @var ResourceModel\Account\CollectionFactory
     */
    protected $_accountCollection;

    /**
     * Account constructor.
     *
     * @param Egoi                                                         $egoi
     * @param ResourceModel\Account\CollectionFactory                      $egoiCollection
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Egoi\Marketing\Model\Egoi                                    $egoi,
        \Egoi\Marketing\Model\ResourceModel\Account\CollectionFactory $egoiCollection,
        \Magento\Framework\Model\Context                              $context,
        \Magento\Framework\Registry                                   $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource       $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb                 $resourceCollection = null,
        array                                                         $data = []
    )
    {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->_egoi = $egoi;
        $this->_accountCollection = $egoiCollection;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('Egoi\Marketing\Model\ResourceModel\Account');
    }

    /**
     * @return $this
     */
    public function getAccount()
    {

        $col = $this->_accountCollection->create()->getFirstItem();

        if (!$col->getId()) {
            $this->setData(['id' => 1])->save();

            return $this;
        }

        return $col;

    }

    function cron()
    {

        $result = $this->_egoi->getAccountDetails()->getData();
        $result[0]['account_id'] = 1;
        $account = $this->getAccount();

        if ($account->getId()) {
            $account->setData($result[0])->save();
        } else {
            $this->setData($result[0])->save();
        }

    }
}
