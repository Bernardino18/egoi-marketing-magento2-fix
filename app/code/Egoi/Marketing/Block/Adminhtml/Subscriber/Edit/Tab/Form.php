<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Subscriber\Edit\Tab;

/**
 * Class Form
 *
 * @package Egoi\Marketing\Block\Adminhtml\Subscriber\Edit\Tab
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
        $this->setTitle(__('Subscriber Information'));
    }

    /**
     * Prepare form
     *
     * @return \Magento\Backend\Block\Widget\Form\Generic
     */
    protected function _prepareForm()
    {

        $current = $this->_coreRegistry->registry('egoi_subscriber');

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
            'email',
            "text",
            [
                "label"    => __('Email'),
                "class"    => "required-entry validate-email",
                "required" => true,
                "name"     => 'email',
            ]
        );

        $fieldset->addField(
            'first_name',
            "text",
            [
                "label" => __('First Name'),
                "name"  => 'first_name',
            ]
        );

        $fieldset->addField(
            'last_name',
            "text",
            [
                "label" => __('Last Name'),
                "name"  => 'last_name',
            ]
        );

        $fieldset->addField(
            'cellphone',
            "text",
            [
                "label" => __('Cellphone Number'),
                "name"  => 'cellphone',
            ]
        );

        $fieldset->addField(
            'status',
            "select",
            [
                "label"   => __('Status'),
                "name"    => 'status',
                'options' => [4 => 'inactive', 1 => 'subscribed'],
            ]
        );

        if ($current->getId()) {
            $form->addValues($current->getData());
        } else {
            $form->addValues(['status' => 1]);
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
