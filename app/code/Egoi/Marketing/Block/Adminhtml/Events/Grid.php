<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Events;

/**
 * Class Grid
 *
 * @package Egoi\Marketing\Block\Adminhtml\Events
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $_autorespondersFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                      $context
     * @param \Magento\Backend\Helper\Data                                 $backendHelper
     * @param \Egoi\Marketing\Model\AutorespondersFactory                  $autorespondersFactory
     * @param \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry                                  $registry
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                      $context,
        \Magento\Backend\Helper\Data                                 $backendHelper,
        \Egoi\Marketing\Model\AutorespondersFactory                  $autorespondersFactory,
        \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry                                  $registry,
        array                                                        $data = []
    )
    {

        $this->_coreRegistry = $registry;
        $this->_collectionFactory = $collectionFactory;
        $this->_autorespondersFactory = $autorespondersFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    public function _construct()
    {

        parent::_construct();
        $this->setId('errors_grid');
        $this->setDefaultSort('event_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {

        $collection = $this->_collectionFactory->create();

        $current = $this->_coreRegistry->registry('egoi_autoresponder');

        if ($current && $current->getId()) {
            $collection->addFieldToFilter('autoresponder_id', $current->getId());
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'event_id',
            [
                'header' => __('ID'),
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'event_id',
            ]
        );
        $this->addColumn(
            'event',
            [
                'header'  => __('Event'),
                'index'   => 'event',
                'type'    => 'options',
                'options' => $this->_autorespondersFactory->create()->toOptionArray(),
            ]
        );

        $this->addColumn(
            'autoresponder_id',
            [
                'header'  => __('Autoresponder'),
                'index'   => 'autoresponder_id',
                'type'    => 'options',
                'options' => $this->_autorespondersFactory->create()->toFormValues(),
            ]
        );

        $this->addColumn(
            'customer_name',
            [
                'header' => __('Customer'),
                'align'  => 'center',
                'width'  => '50px',
                'index'  => 'customer_name',
            ]
        );
        $this->addColumn(
            'customer_email',
            [
                'header' => __('Email'),
                'align'  => 'center',
                'width'  => '50px',
                'index'  => 'customer_email',
            ]
        );

        $this->addColumn(
            'cellphone',
            [
                'header' => __('Cellphone'),
                'index'  => 'cellphone',
            ]
        );

        $this->addColumn(
            'message',
            [
                'header' => __('Message'),
                'index'  => 'message',
            ]
        );

        $this->addColumn(
            'created_at_grid',
            [
                'header'    => __('Created at'),
                'align'     => 'left',
                'width'     => '170px',
                'type'      => 'datetime',
                'gmtoffset' => true,
                'index'     => 'created_at',
            ]
        );

        $this->addColumn(
            'send_at',
            [
                'header'    => __('Send at'),
                'align'     => 'left',
                'width'     => '170px',
                'type'      => 'datetime',
                'gmtoffset' => true,
                'index'     => 'send_at',
            ]
        );
        $this->addColumn(
            'sent',
            [
                'header'  => __('Sent?'),
                'align'   => 'left',
                'width'   => '170px',
                'type'    => 'options',
                'options' => [0 => __('No'), 1 => 'Yes'],
                'index'   => 'sent',
            ]
        );

        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('event_id');
        $this->getMassactionBlock()->setFormFieldName('events');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label'   => __('Delete'),
                'url'     => $this->getUrl('*/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {

        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * @param $value
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function customerResult($value)
    {

        if ((int) $value > 0) {
            $url = $this->getUrl('customer/index/edit', ['id' => $value]);

            return '<a href="' . $url . '">' . __('Yes') . '</a>';
        }

        return __('No');
    }

}
