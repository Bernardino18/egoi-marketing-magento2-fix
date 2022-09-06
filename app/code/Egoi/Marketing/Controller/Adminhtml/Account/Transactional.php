<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Account;

use Egoi\Marketing\Helper\Data;
use Magento\Backend\App\Action;

/**
 * Class Transactional
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Transactional extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Transactional constructor.
     *
     * @param Data                                               $helper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\ReinitableConfig            $reinitableConfig
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Config\Model\ResourceModel\Config         $config
     * @param \Magento\Framework\Filesystem                      $filesystem
     * @param \Magento\Cron\Model\ScheduleFactory                $scheduleFactory
     * @param Action\Context                                     $context
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Magento\Framework\Registry                        $registry
     * @param \Egoi\Marketing\Model\Egoi                         $egoi
     * @param \Egoi\Marketing\Model\ListsFactory                 $listsFactory
     * @param \Egoi\Marketing\Model\AccountFactory               $accountFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
     */
    public function __construct(
        \Egoi\Marketing\Helper\Data                        $helper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ReinitableConfig            $reinitableConfig,
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Config\Model\ResourceModel\Config         $config,
        \Magento\Framework\Filesystem                      $filesystem,
        \Magento\Cron\Model\ScheduleFactory                $scheduleFactory,
        Action\Context                                     $context,
        \Magento\Framework\View\Result\PageFactory         $resultPageFactory,
        \Magento\Framework\Registry                        $registry, \Egoi\Marketing\Model\Egoi $egoi,
        \Egoi\Marketing\Model\ListsFactory                 $listsFactory,
        \Egoi\Marketing\Model\AccountFactory               $accountFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
    )
    {

        parent::__construct(
            $reinitableConfig,
            $storeManager,
            $config,
            $filesystem,
            $scheduleFactory,
            $context,
            $resultPageFactory,
            $registry,
            $egoi,
            $listsFactory,
            $accountFactory,
            $resultForwardFactory,
            $resultLayoutFactory
        );

        $this->_helper = $helper;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     *
     */
    public function execute()
    {

        parent::execute();

        try {

            $transport = $this->_helper->getSmtpTransport();

            $salesSender = $this->_scopeConfig->getValue('trans_email/ident_general/name');
            $salesEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email');

            if ($this->_helper->versionCompare('2.2.8')) {

                /** @var \Zend\Mail\Message $message */
                $message = new \Zend\Mail\Message;
                $message->setBody(
                    'If you are receiving this message, everything seems to be ok with your SMTP configuration'
                );
                $message->setFrom($salesEmail, $salesSender)
                        ->addTo($salesEmail, $salesSender)
                        ->setSubject('E-Goi / Magento - Test');

                $transport = new \Zend\Mail\Transport\Smtp();
                $options = new \Zend\Mail\Transport\SmtpOptions(
                    [
                        'name'              => 'localhost',
                        'host'              => $this->_helper->getSmtpServer(),
                        'port'              => $this->_helper->getSmtpDetails()['port'],
                        'connection_class'  => $this->_helper->getSmtpDetails()['auth'],
                        'connection_config' => $this->_helper->getSmtpDetails(),
                    ]
                );

                if ($domain = $this->_scopeConfig->getValue('egoi/transactional/domain')) {
                    $message->getHeaders()->addHeaderLine('X-Domain', $domain);
                }

                $transport->setOptions($options);
                $transport->send($message);

            } else {
                $mail = new \Zend_Mail('utf-8');
                $mail->setBodyHtml(
                    'If you are receiving this message, everything seems to be ok with your SMTP configuration'
                );
                $mail->setFrom($salesEmail, $salesSender)
                     ->addTo($salesEmail, $salesSender)
                     ->setSubject('E-Goi / Magento - Test');

                if ($domain = $this->_scopeConfig->getValue('egoi/transactional/domain')) {
                    $mail->addHeader('X-Domain', $domain);
                }

                $mail->send($transport);

                $this->messageManager->addSuccessMessage(
                    'Success. Everything seems to be ok with your setting. We sent an email to ' . $salesEmail
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Error Testing your Settings: ' . $e->getMessage());
        }

        return $this->resultRedirectFactory->create()
                                           ->setUrl($this->_redirect->getRefererUrl());

    }
}
