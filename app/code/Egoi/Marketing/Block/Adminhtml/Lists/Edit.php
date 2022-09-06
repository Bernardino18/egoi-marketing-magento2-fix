<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Lists;

/**
 * Class Edit
 *
 * @package Egoi\Marketing\Block\Adminhtml\Lists
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry           $registry,
        array                                 $data = []
    )
    {

        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {

        $this->_objectId = 'id';
        $this->_blockGroup = 'Egoi_Marketing';
        $this->_controller = 'adminhtml_lists';

        parent::_construct();

        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        $this->buttonList->update('save', 'label', __('Save List'));

    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {

        if ($this->_coreRegistry->registry('egoi_list')->getId()) {
            return __(
                "Edit List '%1'",
                $this->escapeHtml($this->_coreRegistry->registry('egoi_list')->getTitle())
            );
        } else {
            return __('New List');
        }
    }
}
