<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Lists\Map\Tab;

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
     * @param Attributes                                            $attributes
     * @param \Magento\Customer\Model\ResourceModel\CustomerFactory $customerResource
     * @param \Egoi\Marketing\Model\Egoi                            $egoi
     * @param \Magento\Backend\Block\Template\Context               $context
     * @param \Magento\Framework\Registry                           $registry
     * @param \Magento\Framework\Data\FormFactory                   $formFactory
     * @param \Magento\Store\Model\System\Store                     $systemStore
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
        array                                                 $data = []
    )
    {

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

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('list_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Assign E-Goi List to Magento'), 'class' => 'fieldset-wide']
        );

        $lists = $this->_egoi->getLists()->getData();

        $options = [];
        foreach ($lists as $list) {
            if (!isset($list['listnum'])) {
                die(
                    'Listagem de Listas indisponível. Por favor tente novamente mais tarde. Erro:' . json_encode(
                        $lists
                    )
                );
            }

            $options[$list['listnum'] . '-' . $list['title'] . '-' . $list['title_ref']] = $list['title'] . ' - ' . $list['title_ref'];
        }

        $fieldset->addField(
            "list_id",
            "select",
            [
                "label"    => __("List Name"),
                "class"    => "required-entry",
                "required" => true,
                "options"  => $options,
                "name"     => "list_id",
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
