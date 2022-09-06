<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Autoresponders;

/**
 * Adminhtml Autoresponders grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Autoresponders\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory
     */
    protected $_eventsCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context                              $context
     * @param \Magento\Backend\Helper\Data                                         $backendHelper
     * @param \Egoi\Marketing\Model\ResourceModel\Autoresponders\CollectionFactory $collectionFactory
     * @param \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory         $eventsCollection
     * @param \Egoi\Marketing\Model\AutorespondersFactory                          $autorespondersFactory
     * @param array                                                                $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                              $context,
        \Magento\Backend\Helper\Data                                         $backendHelper,
        \Egoi\Marketing\Model\ResourceModel\Autoresponders\CollectionFactory $collectionFactory,
        \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory         $eventsCollection,
        \Egoi\Marketing\Model\AutorespondersFactory                          $autorespondersFactory,
        array                                                                $data = []
    )
    {

        $this->_collectionFactory = $collectionFactory;
        $this->_autorespondersFactory = $autorespondersFactory;
        $this->_eventsCollection = $eventsCollection;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {

        parent::_construct();
        $this->setId('egoiautorespondersGrid');
        $this->setDefaultSort('autoresponder_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {

        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'autoresponder_id',
            [
                'header' => __('ID'),
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'autoresponder_id',
            ]
        );

        $this->addColumn(
            'event',
            [
                'header'  => __('Event'),
                'align'   => 'left',
                'index'   => 'event',
                'type'    => 'options',
                'options' => $this->_autorespondersFactory->create()->toOptionArray(),
            ]
        );

        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'align'  => 'left',
                'index'  => 'name',
            ]
        );

        $this->addColumn(
            'number_subscribers',
            [
                'header' => __('N. Sends'),
                'align'  => 'right',
                'type'   => 'number',
                'index'  => 'number_subscribers',
            ]
        );

        $this->addColumn(
            'active',
            [
                'header'  => __('Is Active?'),
                'align'   => 'left',
                'width'   => '80px',
                'index'   => 'active',
                'type'    => 'options',
                'options' => ['0' => __('No'), '1' => __('Yes')],
            ]
        );

        $this->addColumn(
            'from_date',
            [
                'header'         => __('From Date'),
                'align'          => 'left',
                'width'          => '120px',
                'type'           => 'date',
                'default'        => '-- N/A --',
                'frame_callback' => [$this, 'date'],
                'index'          => 'from_date',
            ]
        );

        $this->addColumn(
            'to_date',
            [
                'header'         => __('To Date'),
                'align'          => 'left',
                'width'          => '120px',
                'type'           => 'date',
                'default'        => '-- N/A --',
                'frame_callback' => [$this, 'date'],
                'index'          => 'to_date',
            ]
        );

        $this->addColumn(
            'action2',
            [
                'header'         => __('View'),
                'width'          => '120px',
                'filter'         => false,
                'sortable'       => false,
                'frame_callback' => [$this, 'events'],
                'index'          => 'active',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {

        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function getRowUrl($row)
    {

        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

    /**
     * @param $value
     * @param $row
     *
     * @return string
     */
    public function events($value, $row)
    {

        if (!$value) {
            return '';
        }
        $url = $this->getUrl('*/events/index', ['id' => $row->getAutoresponderId()]);

        $total = $this->_eventsCollection->create()
                                         ->addFieldToFilter('sent', 0)
                                         ->addFieldToFilter('autoresponder_id', $row->getAutoresponderId())
                                         ->getSize();

        return "<a href='$url'>" . __('Queue') . " ($total)</a>";
    }

    /**
     * @param $value
     * @param $row
     *
     * @return string
     */
    public function date($value, $row)
    {

        if (!$row->getId()) {
            return '';
        }

        return $value;
    }

}

