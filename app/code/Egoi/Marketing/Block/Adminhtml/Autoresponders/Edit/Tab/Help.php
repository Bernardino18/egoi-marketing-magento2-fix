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
 * Class Help
 *
 * @package Egoi\Marketing\Block\Adminhtml\Autoresponders\Edit\Tab
 */
class Help extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Variable\Model\VariableFactory
     */
    protected $_variablesFactory;

    /**
     * Help constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Framework\Registry             $coreRegistry
     * @param \Magento\Variable\Model\VariableFactory $variableFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry             $registry,
        \Magento\Framework\Data\FormFactory     $formFactory,
        \Magento\Framework\Registry             $coreRegistry,
        \Magento\Variable\Model\VariableFactory $variableFactory,
        array                                   $data = [])
    {

        parent::__construct($context, $registry, $formFactory, $data);
        $this->_variablesFactory = $variableFactory;
        $this->_registry = $coreRegistry;
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {

        parent::_construct();
        $this->setTemplate('form/help.phtml');
    }

    /**
     * @return array
     */
    public function getVariables()
    {

        $customVariables = $this->_variablesFactory->create()->getVariablesOptionArray(true);

        $egoi = [
            'label' => __('Subscriber Variables'),
            'value' => [
                ['label' => __('Name'), 'value' => '{{var subscriber.getName()}}'],
                ['label' => __('Cellphone'), 'value' => '{{var subscriber.getCellphone()}}'],
                ['label' => __('Email'), 'value' => '{{var subscriber.getEmail()}}'],
            ],
        ];

        return [$customVariables, $egoi];
    }

    /**
     * @return mixed|string
     */
    public function getEvent()
    {

        if ($this->getRequest()->getParam('event')) {
            return $this->getRequest()->getParam('event');
        }

        $current = $this->_registry->registry('egoi_autoresponder');
        if ($current && $current->getEvent()) {
            return $current->getEvent();
        }

        return '';

    }

}
