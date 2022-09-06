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
 * Class First
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class First extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    public function execute()
    {

        try {
            $this->_initAction();
            $this->_getSession()->setData('egoi_first_run', true);

            $this->_listsFactory->create()->getList();
            $this->_accountFactory->create()->getAccount();

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return $this->resultRedirectFactory->create()->setPath(
                '*/*/',
                ['id' => $this->getRequest()->getParam('id')]
            );
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/sync');

    }
}
