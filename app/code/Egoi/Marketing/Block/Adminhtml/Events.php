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
 * Class Events
 *
 * @package Egoi\Marketing\Block\Adminhtml
 */
class Events extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {

        $this->_blockGroup = 'Egoi_Marketing';
        $this->_controller = 'adminhtml_events';
        $this->_headerText = __('Events');
        parent::_construct();

        $this->buttonList->remove('add');

        $dataAR = [
            'label'   => __('Back to Autoresponders'),
            'class'   => 'back',
            'onclick' => "setLocation('{$this->getUrl("*/autoresponders")}')",
        ];
        $this->buttonList->add('add_ar', $dataAR);

    }
}
