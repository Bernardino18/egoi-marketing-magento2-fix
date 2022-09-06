<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Support\Edit\Tab;

/**
 * Class Form
 *
 * @package Egoi\Marketing\Block\Adminhtml\Support\Edit\Tab
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Store\Model\System\Store       $systemStore
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry             $registry,
        \Magento\Framework\Data\FormFactory     $formFactory,
        \Magento\Store\Model\System\Store       $systemStore,
        array                                   $data = []
    )
    {

        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

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
            'first_name',
            "text",
            [
                "label"    => __('First Name'),
                "required" => true,
                "name"     => 'first_name',
            ]
        );

        $fieldset->addField(
            'last_name',
            "text",
            [
                "label"    => __('Last Name'),
                "required" => true,
                "name"     => 'last_name',
            ]
        );

        $fieldset->addField(
            'email',
            "text",
            [
                "label"    => __('Email'),
                "required" => true,
                "name"     => 'email',
            ]
        );

        $eOp = [];
        $eOp[] = ['value' => 'bug', 'label' => __('Bug Report')];
        $eOp[] = ['value' => 'request', 'label' => __('Request')];
        $eOp[] = ['value' => 'other', 'label' => __('Other Information')];

        $fieldset->addField(
            'reason',
            "select",
            [
                "label"    => __('Contact Reason'),
                "required" => true,
                'values'   => $eOp,
                "name"     => 'reason',
            ]
        );

        $fieldset->addField(
            'message',
            "textarea",
            [
                "label"    => __('Message'),
                "required" => true,
                "name"     => 'message',
                "note"     => __('Please be as descriptive as possible'),
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
