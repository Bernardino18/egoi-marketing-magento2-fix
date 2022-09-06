<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Support;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Support
 */
class Index extends \Egoi\Marketing\Controller\Adminhtml\Support
{

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {

        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Egoi_Marketing::support')
                   ->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'))
                   ->addBreadcrumb(__('Support'), __('Support'));

        return $resultPage;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {

        parent::execute();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($this->getRequest()->isPost()) {

            $info = $this->_accountFactory->create()->getAccount()->getData();

            $params = array_merge($info, $this->getRequest()->getPostValue());

            $email = 'integrations@e-goi.com';
            $msg = '';

            foreach ($params as $key => $value) {
                $msg .= "$key : $value <br>";
            }

            $msg .= "API : " . $this->_scopeConfig->getValue('egoi/info/api_key') . "<br>";
            $msg .= "Reason : " . $params['reason'] . "<br>";
            $msg .= "Message : " . $params['message'] . "<br>";

            $mail = new \Zend_Mail('utf8');
            $mail->addTo($email, 'Support');
            $mail->setBodyHtml($msg);
            $mail->setSubject('Contact - E-Goi Support');
            $mail->setFrom($params['email'], $params['first_name'] . ' ' . $params['last_name']);

            try {

                $dir = $this->_fileSystem->getDirectoryRead(DirectoryList::LOG)->getAbsolutePath();
                foreach (glob($dir . '/*') as $arquivo) {
                    if (stripos($arquivo, 'egoi') !== false) {

                        $content = file_get_contents($arquivo);
                        $attachment = new \Zend_Mime_Part($content);
                        $attachment->type = 'text/plain';
                        $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
                        $attachment->encoding = \Zend_Mime::ENCODING_BASE64;
                        $attachment->filename = basename($arquivo);

                        $mail->addAttachment($attachment);
                    }
                }

                $t = $mail->send();
                if ($t === false) {
                    throw new \Exception('Unable to send. Please send an email to integrations@e-goi.com');
                }
                $this->messageManager->addSuccessMessage(__('Your request has been sent'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            return $resultRedirect->setPath('*/account');
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Support'));

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Support\Edit'))
                   ->addLeft($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Support\Edit\Tabs'));

        return $resultPage;
    }

}
