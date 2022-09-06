<?php

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class NewLogin
 *
 * @package Egoi\Marketing\Observer
 */
class NewLogin implements ObserverInterface
{

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $helper;

    /**
     * @param \Egoi\Marketing\Helper\Data $helper
     */
    function __construct(
        \Egoi\Marketing\Helper\Data $helper
    )
    {

        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $event
     */
    public function execute(\Magento\Framework\Event\Observer $event)
    {

        try {
            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $event->getEvent()->getCustomer();

            $this->helper->setCookieByEmail($customer->getEmail());
        } catch (\Exception $e) {

        }
    }

}
