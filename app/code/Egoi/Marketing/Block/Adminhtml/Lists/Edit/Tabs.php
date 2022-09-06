<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Lists\Edit;

/**
 * Class Tabs
 *
 * @package Egoi\Marketing\Block\Adminhtml\Lists\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session      $authSession
     * @param \Magento\Framework\Registry              $coreRegistry
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context  $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session      $authSession,
        \Magento\Framework\Registry              $coreRegistry,
        array                                    $data = []
    )
    {

        $this->_registry = $coreRegistry;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    protected function _construct()
    {

        parent::_construct();
        $this->setId('List_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('List Information'));
    }

    /**
     * @return \Magento\Backend\Block\Widget\Tabs
     */
    protected function _beforeToHtml()
    {

        $this->addTab(
            'form_section',
            [
                'label'   => __('List Information'),
                'title'   => __('List Information'),
                'content' => $this->getLayout()
                                  ->createBlock('Egoi\Marketing\Block\Adminhtml\Lists\Edit\Tab\Form')
                                  ->toHtml(),
            ]
        );

        return parent::_beforeToHtml();
    }

}
