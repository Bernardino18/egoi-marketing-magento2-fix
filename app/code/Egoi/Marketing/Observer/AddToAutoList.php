<?php

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddToAutoList
 *
 * @package Egoi\Marketing\Observer
 */
class AddToAutoList implements ObserverInterface
{

    /**
     * @var \Egoi\Marketing\Model\Observer
     */
    protected $observer;

    /**
     * AddToAutoList constructor.
     *
     * @param \Egoi\Marketing\Model\Observer $observer
     */
    function __construct(\Egoi\Marketing\Model\Observer $observer)
    {

        $this->observer = $observer;
    }

    /**
     * @param \Magento\Framework\Event\Observer $event
     */
    public function execute(\Magento\Framework\Event\Observer $event)
    {

        try {
            $this->observer->addToAutoList($event);
        } catch (\Exception $e) {

        }
    }

}
