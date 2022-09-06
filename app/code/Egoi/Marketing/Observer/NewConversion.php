<?php

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class NewConversion
 *
 * @package Egoi\Marketing\Observer
 */
class NewConversion implements ObserverInterface
{

    /**
     * @var \Egoi\Marketing\Model\ConversionsFactory
     */
    protected $conversionsFactory;

    /**
     * NewOrder constructor.
     *
     * @param \Egoi\Marketing\Model\ConversionsFactory $conversionsFactory
     */
    function __construct(
        \Egoi\Marketing\Model\ConversionsFactory $conversionsFactory
    )
    {

        $this->conversionsFactory = $conversionsFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $event
     */
    public function execute(\Magento\Framework\Event\Observer $event)
    {

        try {
            $this->conversionsFactory->create()->newConversion($event);
        } catch (\Exception $e) {

        }
    }

}
