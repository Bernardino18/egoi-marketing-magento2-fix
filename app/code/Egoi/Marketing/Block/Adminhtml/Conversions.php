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
 * Class Conversions
 *
 * @package Egoi\Marketing\Block\Adminhtml
 */
class Conversions extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {

        $this->_blockGroup = 'Egoi_Marketing';
        $this->_controller = 'adminhtml_conversions';
        $this->_headerText = __('Conversions');
        parent::_construct();

        $this->buttonList->remove('add');

        if ($this->getRequest()->getParam('display') == 'campaigns') {

            $dataAR = [
                'label'   => __('Display Conversions Log'),
                'class'   => '',
                'onclick' => "setLocation('{$this->getUrl("*/*/*",['display'=>false])}')",
            ];
        } else {

            $dataAR = [
                'label'   => __('Display Conversions Totals'),
                'class'   => '',
                'onclick' => "setLocation('{$this->getUrl("*/*/*",['display'=>'campaigns'])}')",
            ];
        }
        $this->buttonList->add('add_ar', $dataAR);

    }
}
