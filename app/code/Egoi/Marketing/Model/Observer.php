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
 * Class Observer
 *
 * @package Egoi\Marketing\Model
 */
class Observer
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
     * @param $event
     *
     * @return bool|Observer
     */
    public function addToAutoList($event)
    {

        try {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $event->getEvent()->getOrder();

            if (!$this->_scopeConfig->getValue('egoi/info/auto')) {
                return false;
            }

            $email = $order->getCustomerEmail();
            $this->_susbcriberFactory->create()->subscribe($email);

        } catch (\Exception $e) {
            $this->_egoiHelper->getLogger('critical')->critical($e->getMessage());
        }

        return $this;
    }
}
