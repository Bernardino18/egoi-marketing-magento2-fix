<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Lists;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Lists
 */
class Index extends \Egoi\Marketing\Controller\Adminhtml\Lists
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {

        $auth = $this->_egoi->validateEgoiEnvironment();
        if (!$auth) {
            return $this->resultRedirectFactory->create()->setPath('*/account/new');
        }

        parent::execute();
        $list = $this->_coreRegistry->registry('egoi_list');

        $this->_redirect('*/*/edit', ['id' => $list->getId()]);

        $resultPage = $this->_initAction();

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Lists\Grid'));

        return $resultPage;
    }
}
