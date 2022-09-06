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
 * Class Save
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Save extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {

        parent::execute();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $model = $this->_egoi->setData('api_key', $data['api_key'])->checkLogin($data['api_key']);

        if ($model->getData('user_id')) {

            /** @var \Magento\Config\Model\ResourceModel\Config */
            $this->_config->saveConfig('egoi/info/api_key', $data['api_key'], 'default', '0');

            $this->_reinitableConfig->reinit();

            $this->messageManager->addSuccessMessage(__('Success!!!'));

            return $resultRedirect->setPath('*/*/map');

        } else {
            $this->messageManager->addErrorMessage(__('Apikey invalid'));

            return $resultRedirect->setPath('*/*/new', ['op' => 'api']);
        }

    }

}
