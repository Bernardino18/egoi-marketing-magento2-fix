<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Lists\Edit\Tab;

use Egoi\Marketing\Model\Source\Attributes;

/**
 * Class Form
 *
 * @package Egoi\Marketing\Block\Adminhtml\Lists\Edit\Tab
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $_egoi;

    /**
     * @var \Magento\Customer\Model\ResourceModel\CustomerFactory
     */
    protected $_customerResource;

    /**
     * @var Attributes
     */
    protected $_attributes;

    /**
     * @var \Egoi\Marketing\Model\Extra
     */
    protected $extraFactory;

    /**
     * Form constructor.
     *
     * @param Attributes                                            $attributes
     * @param \Magento\Customer\Model\ResourceModel\CustomerFactory $customerResource
     * @param \Egoi\Marketing\Model\Egoi                            $egoi
     * @param \Magento\Backend\Block\Template\Context               $context
     * @param \Magento\Framework\Registry                           $registry
     * @param \Magento\Framework\Data\FormFactory                   $formFactory
     * @param \Magento\Store\Model\System\Store                     $systemStore
     * @param \Egoi\Marketing\Model\ExtraFactory                    $extraFactory
     * @param array                                                 $data
     */
    public function __construct(
        \Egoi\Marketing\Model\Source\Attributes               $attributes,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerResource,
        \Egoi\Marketing\Model\Egoi                            $egoi,
        \Magento\Backend\Block\Template\Context               $context,
        \Magento\Framework\Registry                           $registry,
        \Magento\Framework\Data\FormFactory                   $formFactory,
        \Magento\Store\Model\System\Store                     $systemStore,
        \Egoi\Marketing\Model\ExtraFactory                    $extraFactory,
        array                                                 $data = []
    )
    {

        $this->extraFactory = $extraFactory;
        $this->_egoi = $egoi;
        $this->_attributes = $attributes;
        $this->_customerResource = $customerResource;
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

        $current = $this->_coreRegistry->registry('egoi_list');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('list_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            "nome",
            "text",
            [
                "label"    => __("List Name"),
                "class"    => "required-entry",
                "required" => true,
                "name"     => "nome",
            ]
        );

        $fieldset->addField(
            "internal_name",
            "text",
            [
                "label"    => __("Internal Name"),
                "class"    => "required-entry",
                "required" => true,
                "name"     => "internal_name",
            ]
        );

        if ($current->getId()) {

            $remoteList = $this->_egoi->getLists($current->getListnum())->getData();
            $remoteList = reset($remoteList);

            if (!isset($remoteList['listnum'])) {
                $remoteList = reset($remoteList);
            }

            $productAttributes = $this->_customerResource->create()
                                                         ->loadAllAttributes()
                                                         ->getAttributesByCode();

            $attrToRemove = [
                'increment_id',
                'updated_at',
                'attribute_set_id',
                'entity_type_id',
                'confirmation',
                'default_billing',
                'default_shipping',
                'password_hash',
            ];

            $attributes = ['0' => __('--Ignore--')];
            foreach ($productAttributes as $attribute) {
                if (in_array($attribute->getAttributeCode(), $attrToRemove)) {
                    continue;
                }

                if (strlen($attribute->getFrontendLabel()) == 0) {
                    continue;
                }

                $attributes[$attribute->getAttributeCode()] = '[Account] - ' . $attribute->getFrontendLabel();
            }

            asort($attributes);

            $address = $this->_attributes->toOptionArray();
            foreach ($address as $field) {
                $attributes['addr_' . $field['value']] = '[Addresss] - ' . $field['label'];
            }

            if (isset($remoteList['extra_fields']) && is_array($remoteList['extra_fields'])) {

                $fieldset2 = $form->addFieldset(
                    "edit_form",
                    ["legend" => __("E-Goi List Attributes / Magento List Attributes")]
                );

                foreach ($remoteList['extra_fields'] as $field) {

                    if (in_array($field['ref'], \Egoi\Marketing\Model\Extra::AUTO_MAPPING)) {

                        $extra = $this->extraFactory->create()->load($field['ref'], 'attribute_code');

                        if (!$extra->getId()) {
                            $extra->setData(
                                [
                                    'extra_code'     => "extra_" . $field['id'],
                                    'attribute_code' => $field['ref'],
                                    'system'         => 1,
                                ]
                            )
                                  ->save();
                        }

                    } else {

                        $fieldset2->addField(
                            "extra_" . $field['id'],
                            "select",
                            [
                                "label"    => __($field['ref']),
                                "options"  => $attributes,
                                "required" => true,
                                "name"     => "extra_" . $field['id'],
                            ]
                        );
                    }
                }
            }
        }

        if ($current) {

            $currentValues = $current->getData();

            if (count($currentValues) > 0) {
                $currentValues['nome'] = $currentValues['title'];
            }

            $form->setValues($currentValues);

            if (count($currentValues) > 0) {

                $fieldset->addField(
                    "listID",
                    "hidden",
                    [
                        "value"   => $currentValues['listnum'],
                        "no_span" => true,
                        "name"    => "listID",
                    ]
                );
            }
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
