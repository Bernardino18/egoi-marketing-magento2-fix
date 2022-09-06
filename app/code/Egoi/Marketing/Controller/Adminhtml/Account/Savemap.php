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
class Savemap extends \Egoi\Marketing\Controller\Adminhtml\Account
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
        $info = explode('-', $data['list_id']);

        $listId = $info[0];
        $name = $info[1];

        $data = [];
        $data['listnum'] = $listId;
        $data['nome'] = $name;
        $data['title'] = $name;
        $data['name'] = $name;
        $data['internal_name'] = $info[2];
        $data['is_active'] = 1;

        $this->_listsFactory->create()->setData($data)->save();

        $this->messageManager->addSuccessMessage(__('Success!!!'));

        return $resultRedirect->setPath('*/*/first');

    }

}
