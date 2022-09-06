<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Autoresponders\Edit\Tab;

/**
 * Class Data
 *
 * @package Egoi\Marketing\Block\Adminhtml\Autoresponders\Edit\Tab
 */
class Data extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $_groupCollection;

    /**
     * @var \Magento\Sales\Model\Order\ConfigFactory
     */
    protected $_configFactory;

    /**
     * Catalog data
     *
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager = null;

    /**
     * Data constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                       $context
     * @param \Magento\Framework\Registry                                   $registry
     * @param \Magento\Framework\Data\FormFactory                           $formFactory
     * @param \Magento\Store\Model\System\Store                             $systemStore
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollection
     * @param \Magento\Sales\Model\Order\ConfigFactory                      $configFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config                             $wysiwygConfig
     * @param \Egoi\Marketing\Model\AutorespondersFactory                   $autorespondersFactory
     * @param \Magento\Framework\Module\Manager                             $moduleManager
     * @param array                                                         $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                       $context,
        \Magento\Framework\Registry                                   $registry,
        \Magento\Framework\Data\FormFactory                           $formFactory,
        \Magento\Store\Model\System\Store                             $systemStore,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollection,
        \Magento\Sales\Model\Order\ConfigFactory                      $configFactory,
        \Magento\Cms\Model\Wysiwyg\Config                             $wysiwygConfig,
        \Egoi\Marketing\Model\AutorespondersFactory                   $autorespondersFactory,
        \Magento\Framework\Module\Manager                             $moduleManager,
        array                                                         $data = []
    )
    {

        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_autorespondersFactory = $autorespondersFactory;
        $this->_systemStore = $systemStore;
        $this->_groupCollection = $groupCollection;
        $this->_configFactory = $configFactory;
        $this->_moduleManager = $moduleManager;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     */
    protected function _prepareForm()
    {

        $current = $this->_coreRegistry->registry('egoi_autoresponder');

        $event = $this->getRequest()->getParam('event');

        if ($current->getId()) {
            $event = $current->getEvent();
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $fieldset2 = $form->addFieldset('content_fieldset', ['legend' => __('Content')]);

        $fieldset2->addField(
            'name',
            'text',
            [
                'name'     => 'name',
                'label'    => __('Name'),
                'title'    => __('Name'),
                "required" => true,
            ]
        );

        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);

        $wysiwygConfig->setData('hidden', 1);
        $wysiwygConfig->setData('add_images', false);

        $extraMsg = '';
        if ($event == 'shipment_new') {
            $extraMsg = "Use {track_number} {track_title} {order_number} and {customer_name} to be replaced by atual values";
        }

        if ($event == 'order_new' &&
            (
                $this->_moduleManager->isEnabled('Ifthenpay_Multibanco') ||
                $this->_moduleManager->isEnabled('Toogas_Easypay') ||
                $this->_moduleManager->isEnabled('PTPay_MeoWallet') ||
                $this->_moduleManager->isEnabled('Eupago_Multibanco')
            )
        ) {
            $extraMsg = "Pode utilizar as variáveis {entidade} {referencia} e {valor}, que estas serão substituídas por valores reais";
        }

        if ($event == 'order_new' && $this->_moduleManager->isEnabled('UOL_PagSeguro')) {
            $extraMsg .= "Pode utilizar a variável {boleto} para substituir pelo link de pagamento. ";
        }

        $fieldset2->addField(
            'message',
            'editor',
            [
                "label"    => __("Message"),
                "class"    => "required-entry",
                "required" => true,
                "name"     => "message",
                'config'   => $wysiwygConfig,
                'wysiwyg'  => true,
                "note"     => __($extraMsg),
            ]
        );

        $options = $this->_groupCollection->create()->toOptionArray();
        $options1 = ['label' => __('-- Any --'), 'value' => 10000];
        array_unshift($options, $options1);
        $fieldset2->addField(
            'customer_groups',
            'multiselect',
            [
                'name'     => 'customer_groups[]',
                'label'    => __('Customer Groups'),
                'title'    => __('Customer Groups'),
                'required' => true,
                'values'   => $options,
            ]
        );
        $form->getElement('customer_groups')->setData('size', count($options) > 7 ? 7 : count($options));

        $options = $this->_systemStore->getStoreValuesForForm();
        array_unshift($options, ['label' => __('-- Any --'), 'value' => 0]);
        $fieldset2->addField(
            'store_ids',
            'multiselect',
            [
                'name'     => 'store_ids[]',
                'label'    => __('Store View'),
                'title'    => __('Store View'),
                'required' => true,
                'values'   => $options,
            ]
        );

        $fieldset2->addField(
            'active',
            "select",
            [
                "label"   => __('Status'),
                "options" => ['1' => __('Active'), '0' => __('Inactive')],
                "name"    => 'active',
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::SHORT
        );

        $fieldset2->addField(
            'from_date',
            'date',
            [
                'name'        => 'from_date',
                'date_format' => $dateFormat,
                'label'       => __('Active From Date'),
            ]
        );

        $fieldset2->addField(
            'to_date',
            'date',
            [
                'name'        => 'to_date',
                'date_format' => $dateFormat,
                'label'       => __('Active To Date'),
            ]
        );

        $this->setForm($form);

        if ($current) {
            $form->addValues($current->getData());
        }

        return parent::_prepareForm();
    }

}
