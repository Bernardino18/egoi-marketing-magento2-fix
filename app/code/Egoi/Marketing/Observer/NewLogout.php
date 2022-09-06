<?php

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 *
 */
class NewLogout implements ObserverInterface
{

    /**
     * @var \Egoi\Marketing\Helper\Data
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
            $this->helper->unsetCookie();
        } catch (\Exception $e) {

        }
    }

}
