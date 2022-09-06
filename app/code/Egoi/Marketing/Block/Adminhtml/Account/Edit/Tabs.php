<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Account\Edit;

/**
 * Class Tabs
 *
 * @package Egoi\Marketing\Block\Adminhtml\Support\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

    protected function _construct()
    {

        parent::_construct();
        $this->setId('subscriber_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Information'));
    }

    /**
     * @return \Magento\Backend\Block\Widget\Tabs
     */
    protected function _beforeToHtml()
    {

        $this->addTab(
            'form_section',
            [
                'label' => __('Account Information'),
                'title' => __('Account Information'),
                'content' => $this->getLayout()
                                  ->createBlock('Egoi\Marketing\Block\Adminhtml\Account\Edit\Tab\Form')
                                  ->toHtml(),
            ]
        );

        return parent::_beforeToHtml();
    }

}
