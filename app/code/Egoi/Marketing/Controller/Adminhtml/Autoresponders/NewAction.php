<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Autoresponders;

/**
 * Class NewAction
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Autoresponders
 */
class NewAction extends \Egoi\Marketing\Controller\Adminhtml\Autoresponders
{

    /**
     * @return \Magento\Framework\Controller\Result\Forward
     */
    public function execute()
    {

        parent::execute();
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();

        return $resultForward->forward('edit');

    }
}
