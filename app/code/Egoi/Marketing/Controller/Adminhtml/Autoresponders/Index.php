<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Autoresponders;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Autoresponders
 */
class Index extends \Egoi\Marketing\Controller\Adminhtml\Autoresponders
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        parent::execute();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Egoi_Marketing::autoresponders');
        $resultPage->getConfig()->getTitle()->prepend(__('Autoresponders'));
        $resultPage->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'));
        $resultPage->addBreadcrumb(__('Autoresponders'), __('Autoresponders'));

        return $resultPage;
    }
}
