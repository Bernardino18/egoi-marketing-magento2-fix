<?php
/**
 *
 * Licentia, Unipessoal LDA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://licentia.pt/magento-license.txt
 *
 * @title      Licentia Panda - Magento® Sales Automation Extension
 * @package    Licentia
 * @author     Bento Vilas Boas <bento@licentia.pt>
 * @copyright  Copyright (c) 2012-2017 Licentia - https://licentia.pt
 * @license    https://licentia.pt/magento-license.txt
 *
 */

namespace Egoi\Marketing\Plugin;

/**
 * Class Subscriber
 *
 * @package Egoi\Marketing\Plugin
 */
class Subscriber
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Egoi\Marketing\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * Subscriber constructor.
     *
     * @param \Egoi\Marketing\Model\SubscriberFactory            $subscriberFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Egoi\Marketing\Model\SubscriberFactory            $subscriberFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {

        $this->_scopeConfig = $scopeConfig;
        $this->_subscriberFactory = $subscriberFactory;
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param                                      $result
     *
     * @return bool
     */
    public function afterIsSubscribed(\Magento\Newsletter\Model\Subscriber $subject, $result)
    {

        if ($subject->getInCallback()) {
            return $result;
        }

        if (!filter_var($subject->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return $result;
        }

        return $this->_subscriberFactory->create()->isSubscribedByEmail($subject->getEmail());

    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param                                      $result
     *
     * @return mixed
     */
    public function afterSubscribe(\Magento\Newsletter\Model\Subscriber $subject, $result)
    {

        if ($subject->getInCallback()) {
            return $result;
        }

        if (!filter_var($subject->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return $result;
        }

        try {
            $this->_subscriberFactory->create()
                                     ->subscribe($subject->getEmail());

        } catch (\Exception $e) {

        }

        return $result;
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param                                      $result
     *
     * @return mixed
     */
    public function afterSubscribeCustomerById(\Magento\Newsletter\Model\Subscriber $subject, $result)
    {

        if ($subject->getInCallback() ||
            !isset($_POST['is_subscribed']) ||
            $_POST['is_subscribed'] == 0) {

            return $result;
        }

        if (!filter_var($subject->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return $result;
        }

        try {
            $this->_subscriberFactory->create()
                                     ->subscribe($subject->getEmail());

        } catch (\Exception $e) {

        }

        return $result;
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param                                      $result
     *
     * @return mixed
     */
    public function afterUnsubscribe(\Magento\Newsletter\Model\Subscriber $subject, $result)
    {

        if ($subject->getInCallback()) {
            return $result;
        }

        if (!filter_var($subject->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return $result;
        }

        try {
            $this->_subscriberFactory->create()->unsubscribe($subject->getEmail());
        } catch (\Exception $e) {

        }

        return $result;
    }

    /**
     * @param \Magento\Newsletter\Model\Subscriber $subject
     * @param                                      $result
     *
     * @return mixed
     */
    public function afterUnsubscribeCustomerById(\Magento\Newsletter\Model\Subscriber $subject, $result)
    {

        if ($subject->getInCallback()) {
            return $result;
        }

        if (!filter_var($subject->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return $result;
        }

        if (isset($_POST['is_subscribed']) && $_POST['is_subscribed'] == 1) {
            return $result;
        }

        try {
            $this->_subscriberFactory->create()->unsubscribe($subject->getEmail());
        } catch (\Exception $e) {

        }

        return $result;
    }
}
