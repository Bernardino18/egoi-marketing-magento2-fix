<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Account;

/**
 * Class Edit
 *
 * @package Egoi\Marketing\Block\Adminhtml\Account
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    protected function _construct()
    {

        $this->_objectId = 'id';
        $this->_blockGroup = 'Egoi_Marketing';
        $this->_controller = 'adminhtml_account';

        parent::_construct();

        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {

        return __('Account');
    }
}
