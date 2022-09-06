<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Subscriber;

/**
 * Class Grid
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Subscriber
 */
class Grid extends \Egoi\Marketing\Controller\Adminhtml\Subscriber
{

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        parent::execute();

        return $this->resultPageFactory->create();
    }
}
