<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Plugin;

use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class TransportPlugin
 *
 * @package Egoi\Marketing\PLugin
 */
class TransportPlugin
{

    /**
     * @var ScopeConfigInterface
     */
    protected $_scope;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $egoiHelper;

    /**
     * TransportPlugin constructor.
     *
     * @param \Egoi\Marketing\Helper\Data $helper
     * @param ScopeConfigInterface        $scopeConfigInterface
     */
    public function __construct(
        \Egoi\Marketing\Helper\Data $helper,
        ScopeConfigInterface        $scopeConfigInterface
    )
    {

        $this->_scope = $scopeConfigInterface;
        $this->egoiHelper = $helper;

    }

    /**
     * @param TransportInterface $subject
     * @param \Closure           $proceed
     *
     * @return mixed
     */
    public function aroundSendMessage(
        TransportInterface $subject,
        \Closure           $proceed
    )
    {

        if (!$this->_scope->isSetFlag('egoi/transactional/enable')) {

            return $proceed();

        } else {

            try {

                if (method_exists($subject, 'getMessage')) {
                    $message = $subject->getMessage();
                } else {
                    $message = $this->useReflectionToGetMessage($subject);
                }

                if ($message instanceof \Zend_Mail) {
                    $transport = $this->egoiHelper->getSmtpTransport();

                    $message->send($transport);

                } else {

                    /** @var \Magento\Framework\Mail\Message $message */
                    $message = $subject->getMessage();
                    $message = \Zend\Mail\Message::fromString($message->getRawMessage())->setEncoding('utf-8');

                    $transport = new \Zend\Mail\Transport\Smtp();
                    $options = new \Zend\Mail\Transport\SmtpOptions(
                        [
                            'name'              => 'localhost',
                            'host'              => $this->egoiHelper->getSmtpServer(),
                            'port'              => $this->egoiHelper->getSmtpDetails()['port'],
                            'connection_class'  => $this->egoiHelper->getSmtpDetails()['auth'],
                            'connection_config' => $this->egoiHelper->getSmtpDetails(),
                        ]
                    );

                    if ($domain = $this->_scope->getValue('egoi/transactional/domain')) {
                        $message->getHeaders()->addHeaderLine('X-Domain', $domain);
                    }

                    $transport->setOptions($options);
                    $transport->send($message);

                }

            } catch (\Exception $e) {
            }

        }

    }
}
