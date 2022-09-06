<?php

namespace Egoi\Marketing\Plugin;

use \Magento\Newsletter\Model\SubscriptionManager;
use \Magento\Newsletter\Model\Subscriber;

/**
 *
 */
class SubscriberManager
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Egoi\Marketing\Model\SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * Subscriber constructor.
     *
     * @param \Egoi\Marketing\Model\SubscriberFactory            $subscriberFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Egoi\Marketing\Model\SubscriberFactory $subscriberFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {

        $this->_scopeConfig = $scopeConfig;
        $this->subscriberFactory = $subscriberFactory;
    }

    /**
     * @param SubscriptionManager $subject
     * @param Subscriber          $result
     * @param string              $email
     * @param int                 $storeId
     *
     * @return Subscriber
     */
    public function afterSubscribe(
        SubscriptionManager $subject,
        Subscriber $result,
        string $email,
        int $storeId
    ): Subscriber {

        try {
            $this->subscriberFactory->create()->subscribe($email, $storeId);
        } catch (\Exception $e) {

        }

        return $result;
    }

    /**
     * @param SubscriptionManager $subject
     * @param Subscriber          $result
     * @param string              $email
     * @param int                 $storeId
     * @param string              $confirmCode
     *
     * @return Subscriber
     */
    public function afterUnsubscribe(
        SubscriptionManager $subject,
        Subscriber $result,
        string $email,
        int $storeId,
        string $confirmCode
    ): Subscriber {

        try {
            $this->subscriberFactory->create()->unsubscribe($email, $storeId);
        } catch (\Exception $e) {

        }

        return $result;
    }

    /**
     * @param SubscriptionManager $subject
     * @param Subscriber          $result
     * @param int                 $customerId
     * @param int                 $storeId
     *
     * @return Subscriber
     */
    public function afterSubscribeCustomer(
        SubscriptionManager $subject,
        Subscriber $result,
        int $customerId,
        int $storeId
    ): Subscriber {

        try {
            $this->subscriberFactory->create()->subscribe($result->getEmail(), $storeId);
        } catch (\Exception $e) {

        }

        return $result;
    }

    /**
     * @param SubscriptionManager $subject
     * @param Subscriber          $result
     * @param int                 $customerId
     * @param int                 $storeId
     *
     * @return Subscriber
     */
    public function afterUnsubscribeCustomer(
        SubscriptionManager $subject,
        Subscriber $result,
        int $customerId,
        int $storeId
    ): Subscriber {

        try {
            $this->subscriberFactory->create()->unsubscribe($result->getEmail(), $storeId);
        } catch (\Exception $e) {

        }

        return $result;
    }
}
