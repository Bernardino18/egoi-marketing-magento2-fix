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
 * Class Sync
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Sync extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page|void
     */
    public function execute()
    {

        $cron = $this->_cron->create();

        $data['status'] = 'pending';
        $data['job_code'] = 'egoi_sync';
        $data['scheduled_at'] = new \Zend_Db_Expr('NOW()');
        $data['created_at'] = new \Zend_Db_Expr('NOW()');
        $cron->setData($data)->save();

        $this->messageManager->addSuccessMessage(__('Data will be synced next time cron runs'));

        $this->_redirect('*/account/');

        return;

    }
}
