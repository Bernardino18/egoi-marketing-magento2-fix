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
class Map extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        parent::execute();

        $resultPage = $this->_initAction();

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Lists\Map'))
                   ->addLeft($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Lists\Map\Tabs'));

        return $resultPage;

    }
}
