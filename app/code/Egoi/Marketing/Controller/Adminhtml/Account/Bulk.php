<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Account;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Bulk
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Bulk extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {

        $tmpDir = $this->_fileSystem->getDirectoryWrite(DirectoryList::TMP);
        $file = $tmpDir->getAbsolutePath() . 'egoi.txt';
        file_put_contents($file, '0');

        if ($this->getRequest()->getParam('export')) {

            if (is_file($tmpDir->getAbsolutePath() . 'egoi_export.csv')) {
                unlink($tmpDir->getAbsolutePath() . 'egoi_export.csv');
            }

            $cron = $this->_cron->create();
            $data['status'] = 'pending';
            $data['job_code'] = 'egoi_export_bulk';
            $data['scheduled_at'] = new \Zend_Db_Expr('NOW()');
            $data['created_at'] = new \Zend_Db_Expr('NOW()');
            $cron->setData($data)->save();

            $this->messageManager->addSuccessMessage(__('You will get an email when the file is ready to download'));

            return $this->_redirect('*/*/');
        }

        $cron = $this->_cron->create();
        $data['status'] = 'pending';
        $data['job_code'] = 'egoi_sync_bulk';
        $data['scheduled_at'] = new \Zend_Db_Expr('NOW()');
        $data['created_at'] = new \Zend_Db_Expr('NOW()');
        $cron->setData($data)->save();
        $this->messageManager->addSuccessMessage(__('Data will be synced next time cron runs'));

        return $this->_redirect('*/*/');

    }
}
