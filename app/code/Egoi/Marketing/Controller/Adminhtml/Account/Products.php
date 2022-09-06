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
 * Class Products
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Products extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page|void
     */
    public function execute()
    {

        $cron = $this->_storeManager->getStores();

        foreach ($cron as $info) {
            $result = $this->_egoi->buildProductsFeed($info->getId());

            if ($result) {
                $this->messageManager->addSuccessMessage(
                    __('Products for store %1 synced successfully', $info->getName())
                );
            }
        }

        $this->_redirect('*/account/');

        return;

    }
}
