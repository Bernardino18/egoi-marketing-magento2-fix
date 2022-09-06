<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Account\Edit\Tab;

/**
 * Class Form
 *
 * @package Egoi\Marketing\Block\Adminhtml\Account\Edit\Tab
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->setId('block_form');
        $this->setTitle(__('Block Information'));
    }

    /**
     * Prepare form
     *
     * @return \Magento\Backend\Block\Widget\Form\Generic
     */
    protected function _prepareForm()
    {

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('subscriber_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'api_key',
            "text",
            [
                "name"     => 'api_key',
                "label"    => __('Your APi Key'),
                "note"     => __(
                    'To get your API Key, login to your E-goi.com panel, go to your user menu (upper right corner), select "Apps" and copy the account API key.'
                ),
                "class"    => "required-entry",
                "required" => true,
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
