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
 * Class Clear
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Clear extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * Clear action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        parent::execute();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var $config \Magento\Config\Model\ResourceModel\Config */
        $config = $this->_config->saveConfig('egoi/info/api_key', "0", 'default', 0);

        /** @var \Egoi\Marketing\Model\Lists $resource */
        $resource = $this->_listsFactory->create();
        $sql = $resource->getResource()->getConnection();

        $tables = [
            'egoi_account',
            'egoi_autoresponders',
            'egoi_autoresponders_events',
            'egoi_lists',
            'egoi_extra',
            'egoi_subscribers',
        ];

        foreach ($tables as $table) {

            $sql->delete($resource->getResource()->getTable($table));

        }
        $this->_auth->getUser()->setData('egoiAuth', false);

        $this->_reinitableConfig->reinit();
        $this->_storeManager->reinitStores();

        $this->_redirect('*/*/');

    }
}
