<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Events;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Events
 */
class Index extends \Egoi\Marketing\Controller\Adminhtml\Events
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        parent::execute();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Egoi_Marketing::events');
        $resultPage->getConfig()->getTitle()->prepend(__('Events'));
        $resultPage->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'));
        $resultPage->addBreadcrumb(__('Events'), __('Events'));

        return $resultPage;
    }
}
