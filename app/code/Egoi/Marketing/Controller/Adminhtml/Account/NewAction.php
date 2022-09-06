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
 * Class NewAction
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class NewAction extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        parent::execute();

        $resultPage = $this->_initAction();

        $model = new \Magento\Framework\DataObject();

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_coreRegistry->register('current_account', $model);

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Account\Edit'))
                   ->addLeft($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Account\Edit\Tabs'));

        return $resultPage;

    }
}
