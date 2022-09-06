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

namespace Egoi\Marketing\Controller\Callback;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Callback
 */
class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Index constructor.
     *
     * @param \Psr\Log\LoggerInterface                    $loggerInterface
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Framework\App\Action\Context       $context
     */
    public function __construct(
        \Psr\Log\LoggerInterface                    $loggerInterface,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Framework\App\Action\Context       $context
    )
    {

        $this->_subscriberFactory = $subscriberFactory;
        $this->_logger = $loggerInterface;

        parent::__construct($context);
    }

    /**
     * @param       $xmlObject
     * @param array $out
     *
     * @return array
     */
    public function xml2array($xmlObject, $out = [])
    {

        foreach ((array) $xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;
        }

        $out = array_change_key_case($out);

        return $out;
    }

    /**
     *
     */
    public function execute()
    {

        $data = (array) $this->getRequest()->getParams();
        $data = reset($data);
        $info = $this->xml2array(simplexml_load_string($data));

        if (!isset($info['email'])) {
            die();
        }

        try {

            /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
            $subscriber = $this->_subscriberFactory->create();
            $subscriber->loadByEmail($info['email']);

            if ($info['status'] == \Egoi\Marketing\Model\Subscriber::STATUS_SUBSCRIBED &&
                $subscriber->getId() &&
                $subscriber->isSubscribed()) {
                die();
            }

            if ($info['status'] != \Egoi\Marketing\Model\Subscriber::STATUS_SUBSCRIBED &&
                $subscriber->getId() &&
                !$subscriber->isSubscribed()) {
                die();
            }

            $subscriber->setInCallBack(true);
            $subscriber->setImportMode(true);

            $status = $info['status'];

            if ($status == 1) {
                $subscriber->subscribe($info['email']);
            } else {
                if ($subscriber->getId()) {
                    $subscriber->unsubscribe();
                }
            }

        } catch (\Exception $e) {

            $this->_logger->warning($e->getMessage());

        }

        die();
    }

}
