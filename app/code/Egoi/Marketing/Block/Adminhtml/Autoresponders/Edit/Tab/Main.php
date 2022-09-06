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
 * Class Main
 *
 * @package Egoi\Marketing\Block\Adminhtml\Autoresponders\Edit\Tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $_paymentHelper;

    /**
     * @var \Magento\Sales\Model\Order\ConfigFactory
     */
    protected $_configFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $_groupCollection;

    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $_shippingConfig;

    /**
     * Main constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                       $context
     * @param \Magento\Framework\Registry                                   $registry
     * @param \Magento\Framework\Data\FormFactory                           $formFactory
     * @param \Magento\Store\Model\System\Store                             $systemStore
     * @param \Magento\Payment\Helper\Data                                  $paymentData
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollection
     * @param \Magento\Sales\Model\Order\ConfigFactory                      $configFactory
     * @param \Egoi\Marketing\Model\AutorespondersFactory                   $autorespondersFactory
     * @param \Magento\Shipping\Model\Config                                $shippingConfig
     * @param array                                                         $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                       $context,
        \Magento\Framework\Registry                                   $registry,
        \Magento\Framework\Data\FormFactory                           $formFactory,
        \Magento\Store\Model\System\Store                             $systemStore,
        \Magento\Payment\Helper\Data                                  $paymentData,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollection,
        \Magento\Sales\Model\Order\ConfigFactory                      $configFactory,
        \Egoi\Marketing\Model\AutorespondersFactory                   $autorespondersFactory,
        \Magento\Shipping\Model\Config                                $shippingConfig,
        array                                                         $data = []
    )
    {

        $this->_autorespondersFactory = $autorespondersFactory;
        $this->_systemStore = $systemStore;
        $this->_groupCollection = $groupCollection;
        $this->_configFactory = $configFactory;
        $this->_paymentHelper = $paymentData;
        $this->_shippingConfig = $shippingConfig;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Form\Generic
     */
    protected function _prepareForm()
    {

        $current = $this->_coreRegistry->registry('egoi_autoresponder');

        $event = $this->getRequest()->getParam('event');
        $sendMoment = $this->getRequest()->getParam('send_moment');

        if ($current->getId()) {
            $event = $current->getEvent();
            if ($sendMoment) {
                $current->setData('send_moment', $sendMoment);
            }
            $sendMoment = $current->getSendMoment();

        } else {
            $current->setData('event', $event);
            $current->setData('send_moment', $sendMoment);
        }

        $location = $this->getUrl('*/*/*', ['id' => $this->getRequest()->getParam('id')]);

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $fieldset = $form->addFieldset('params_fieldset', ['legend' => __('Settings')]);

        $options = $this->_autorespondersFactory->create()->toOptionArray();

        if (!$event) {
            array_unshift($options, __('Please Select'));
        }

        $script = "<script>require(['prototype'], function () {

     goToUrl = {
        go: function(url) {

        var els=new Array('event');

        var temp = '';
        Form.getElements( $('edit_form') ).each(function(item){

            if(els.indexOf(item.name)==-1)
            return;

            if(item.value.length==0)
            return;

            if(item.name =='form_key')
            return;

           temp += item.name+'/'+item.value+'/';

           });

             window.location=url+temp
 }
         }});</script>";

        $fieldset->addField(
            'event',
            'select',
            [
                'name'     => 'event',
                'disabled' => $current->getId() ? 'disabled' : '',
                'label'    => __('Event Trigger'),
                'title'    => __('Event Trigger'),
                'options'  => $options,
                "required" => true,
                "onchange" => "goToUrl.go('$location')",
            ]
        )->setAfterElementHtml($script);

        if ($event) {
            $location = $this->getUrl('*/*/*', ['_current' => true, 'send_moment' => false]) . 'send_moment/';
            $location = "window.location='$location'+this.value";

            $options = [];

            if (!$current->getId() && !$sendMoment) {
                $options[''] = __('Please Select');
            }
            $options['occurs'] = __('When triggered');
            $options['after'] = __('After...');

            $fieldset->addField(
                'send_moment',
                "select",
                [
                    "label"    => __('Send Moment'),
                    "options"  => $options,
                    "name"     => 'send_moment',
                    "required" => true,
                    "onchange" => "$location",
                ]
            );
        }

        if ($sendMoment == 'after') {
            $fieldset->addField(
                'after_hours',
                "text",
                [
                    "label" => __('After Hours...'),
                    "name"  => 'after_hours',
                    "class" => 'validate-digits',
                ]
            );

            $fieldset->addField(
                'after_days',
                "text",
                [
                    "label" => __('After Days...'),
                    "name"  => 'after_days',
                    "class" => 'validate-digits',
                ]
            );
        }

        if (stripos($event, 'order_status_') !== false) {

            $status = $this->_configFactory->create()->getStatuses();

            $status[0] = __('-- Ignore --');
            unset($status[str_replace("order_status_", '', $event)]);

            $fieldset->addField(
                'order_status_previous',
                "select",
                [
                    "label"   => __('From Status'),
                    "options" => $status,
                    "name"    => 'order_status_previous',
                ]
            );

            $fieldset->addField(
                'order_status_time',
                'text',
                [
                    'name'  => 'order_status_time',
                    'label' => __('Max. Time'),
                    'title' => __('Max. Time'),
                    "note"  => __(
                        'Do not send if more than X minutes have passed since the previous status was set. (use 0 or leave blank to ignore)'
                    ),
                ]
            );
        }

        if ($sendMoment && $event != 'order_status') {
            $fieldset->addField(
                'send_once',
                "select",
                [
                    "label"   => __('Send Only Once?'),
                    "options" => ['1' => __('Yes'), '0' => __('Every Time Occurs')],
                    "name"    => 'send_once',
                    "value"   => '1',
                    "note"    => __('To the same subscriber'),
                ]
            );
        }
        if ($sendMoment) {
            $options = $this->_paymentHelper->getPaymentMethodList(true, true);

            $fieldset->addField(
                'payment_method',
                "multiselect",
                [
                    "label"  => __('Match Payment Method'),
                    "values" => $options,
                    "name"   => 'payment_method',
                ]
            );

            $methods = $this->_shippingConfig->getActiveCarriers();
            $options = [];
            foreach ($methods as $_ccode => $_carrier) {
                if ($_methods = $_carrier->getAllowedMethods()) {
                    foreach ($_methods as $_mcode => $_method) {
                        $_code = $_ccode . '_' . $_mcode;
                        $options[] = ['value' => $_code, 'label' => $_method];
                    }
                }
            }
            $fieldset->addField(
                'shipping_method',
                "multiselect",
                [
                    "label"  => __('Match Shipping Method'),
                    "values" => $options,
                    "name"   => 'shipping_method',
                ]
            );
        }

        $form->setValues($current->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
