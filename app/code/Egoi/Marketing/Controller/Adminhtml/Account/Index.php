<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Account;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Index extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    public function execute()
    {

        parent::execute();

        $resultPage = $this->_initAction();

        $auth = $this->_egoi->validateEgoiEnvironment();
        if (!$auth) {
            return $this->resultRedirectFactory->create()->setPath('*/account/new');
        }

        $list = $this->_listsFactory->create()->getList();

        if (!$list->getListnum()) {
            return $this->resultRedirectFactory->create()->setPath('*/account/map');
        }

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Account'));

        return $resultPage;
    }
}
