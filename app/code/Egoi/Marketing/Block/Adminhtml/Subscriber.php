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
 * Class Subscriber
 *
 * @package Egoi\Marketing\Block\Adminhtml
 */
class Subscriber extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {

        $this->_blockGroup = 'Egoi_Marketing';
        $this->_controller = 'adminhtml_subscriber';
        $this->_headerText = __('Subscribers');
        $this->_addButtonLabel = __('New Subscriber');
        parent::_construct();

    }
}
