<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml;

/**
 * Class Autoresponders
 *
 * @package Egoi\Marketing\Block\Adminhtml
 */
class Autoresponders extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {

        $this->_blockGroup = 'Egoi_Marketing';
        $this->_controller = 'adminhtml_autoresponders';
        $this->_headerText = __('Autoresponders');
        $this->_addButtonLabel = __('New Autoresponder');
        parent::_construct();
    }
}
