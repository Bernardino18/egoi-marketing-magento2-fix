<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Subscriber;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Subscriber
 */
class Index extends \Egoi\Marketing\Controller\Adminhtml\Subscriber
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Egoi_Marketing::subscribers');
        $resultPage->getConfig()->getTitle()->prepend(__('Subscribers'));
        $resultPage->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'));
        $resultPage->addBreadcrumb(__('Subscribers'), __('Subscribers'));

        return $resultPage;
    }
}
