<?php

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class EgoiTrackSuccessPage
 *
 * @package Egoi\Marketing\Observer
 */
class EgoiTrackSuccessPage implements ObserverInterface
{

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout
    )
    {

        $this->_layout = $layout;
    }

    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param EventObserver $observer
     *
     * @return void
     */
    public function execute(EventObserver $observer)
    {

        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $block = $this->_layout->getBlock('egoitrack');
        if ($block) {
            $block->setOrderIds($orderIds);
        }
    }

}
