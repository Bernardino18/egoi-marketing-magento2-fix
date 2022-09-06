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
 * Class Events
 *
 * @package Egoi\Marketing\Model
 */
class Conversions extends \Magento\Framework\Model\AbstractModel
{

    const COOKIE_NAME = 'egoi_conv';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'egoi_conversions';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'conversion';

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata
     */
    protected $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('Egoi\Marketing\Model\ResourceModel\Conversions');
    }

    /**
     * Conversions constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface         $timezone
     * @param \Magento\Framework\Stdlib\CookieManagerInterface             $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata        $publicCookieMetadata
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface    $timezone,
        \Magento\Framework\Stdlib\CookieManagerInterface        $cookieManager,
        \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata   $publicCookieMetadata,
        \Magento\Framework\Model\Context                        $context,
        \Magento\Framework\Registry                             $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection = null,
        array                                                   $data = [])
    {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->timezone = $timezone;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $publicCookieMetadata;
    }

    /**
     * @param $event
     */
    public function newConversion($event)
    {

        try {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $event->getEvent()->getOrder();
            if ($cookie = $this->cookieManager->getCookie(self::COOKIE_NAME)) {

                $cookie = json_decode($cookie, true);

                if ($this->getCollection()->addFieldToFilter('order_id', $order->getIncrementId())->count() == 0) {

                    $this->setData(
                        [
                            'created_at'   => $this->timezone->date()->format('Y-m-d H:i:s'),
                            'email'        => $order->getCustomerEmail(),
                            'order_id'     => $order->getIncrementId(),
                            'order_amount' => $order->getBaseGrandTotal(),
                        ]
                    )
                         ->addData($cookie)
                         ->save();

                }
            }
        } catch (\Exception $e) {

        }

    }

}
