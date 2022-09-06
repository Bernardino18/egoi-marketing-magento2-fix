<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class Observer
 *
 * @package Egoi\Marketing\Model
 */
class NewCustomer implements ObserverInterface
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_susbcriberFactory;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $_egoiHelper;

    /**
     * Observer constructor.
     *
     * @param \Egoi\Marketing\Helper\Data                        $egoiHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Newsletter\Model\SubscriberFactory        $subscribersFactory
     */
    public function __construct(
        \Egoi\Marketing\Helper\Data                        $egoiHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Newsletter\Model\SubscriberFactory        $subscribersFactory
    )
    {

        $this->_egoiHelper = $egoiHelper;
        $this->_scopeConfig = $scopeConfig;
        $this->_susbcriberFactory = $subscribersFactory;

    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return bool|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        return $this->addToAutoList($observer);
    }

    /**
     * @param $event
     *
     * @return bool
     */
    public function addToAutoList($event)
    {

        try {
            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $event->getEvent()->getCustomer();

            if (!$this->_scopeConfig->getValue('egoi/info/auto')) {
                return false;
            }

            $email = $customer->getEmail();
            $this->_susbcriberFactory->create()->subscribe($email);

        } catch (\Exception $e) {
            $this->_egoiHelper->getLogger('critical')->critical($e->getMessage());
        }

        return true;
    }
}
