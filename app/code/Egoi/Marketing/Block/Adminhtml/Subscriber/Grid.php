<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Subscriber;

/**
 * Class Grid
 *
 * @package Egoi\Marketing\Block\Adminhtml\Subscriber
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Egoi\Marketing\Model\Subscriber
     */
    protected $_subscriberFactory;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context                          $context
     * @param \Magento\Backend\Helper\Data                                     $backendHelper
     * @param \Egoi\Marketing\Model\Subscriber                                 $subscriberFactory
     * @param \Egoi\Marketing\Model\ResourceModel\Subscriber\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param array                                                            $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                          $context,
        \Magento\Backend\Helper\Data                                     $backendHelper,
        \Egoi\Marketing\Model\Subscriber                                 $subscriberFactory,
        \Egoi\Marketing\Model\ResourceModel\Subscriber\CollectionFactory $collectionFactory,
        \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        array                                                            $data = []
    )
    {

        $this->_collectionFactory = $collectionFactory;
        $this->_subscriberFactory = $subscriberFactory;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {

        parent::_construct();
        $this->setId('egoiSubscriberGrid');
        $this->setDefaultSort('subscriber_id');
        $this->setDefaultDir('ASC');
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
        /* @var $collection \Egoi\Marketing\Model\ResourceModel\Subscriber\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'subscriber_id',
            [
                'header' => __('ID'),
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'subscriber_id',
            ]
        );
        $this->addColumn(
            'uid',
            [
                'header' => __('E-Goi ID'),
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'uid',
            ]
        );
        $this->addColumn(
            'customer_id',
            [
                'header'         => __('Customer'),
                'align'          => 'center',
                'width'          => '50px',
                'index'          => 'customer_id',
                'frame_callback' => [$this, 'customerResult'],
                'is_system'      => true,
            ]
        );

        $this->addColumn(
            'first_name',
            [
                'header' => __('Frst Name'),
                'type'   => 'text',
                'index'  => 'first_name',
            ]
        );

        $this->addColumn(
            'last_name',
            [
                'header' => __('Last Name'),
                'type'   => 'text',
                'index'  => 'last_name',
            ]
        );

        $this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'align'  => 'left',
                'index'  => 'email',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header'  => __('Status'),
                'type'    => 'options',
                'align'   => 'left',
                'options' => [0 => __('Unsubscribed'), 1 => __('Active')],
                'index'   => 'status',
            ]
        );

        $this->addColumn(
            'sent',
            [
                'header' => __('Emails Sent'),
                'align'  => 'left',
                'index'  => 'sent',
                'type'   => 'number',
                'width'  => '40px',
            ]
        );

        $this->addColumn(
            'bounces',
            [
                'header' => __('Bounces'),
                'align'  => 'left',
                'index'  => 'bounces',
                'type'   => 'number',
                'width'  => '50px',
            ]
        );

        //        $this->addExportType('*/*/exportCsv', __('CSV'));
        //        $this->addExportType('*/*/exportXml', __('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * @param $value
     * @param $row
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function customerResult($value, $row)
    {

        if (!$row->getId()) {
            return '<style>.totals .massaction-checkbox{display:none;}</style>';
        }

        if ((int) $value > 0) {
            $url = $this->getUrl('customer/index/edit', ['id' => $value]);

            return '<a href="' . $url . '">' . __('Yes') . '</a>';
        }

        return __('No');
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

}
