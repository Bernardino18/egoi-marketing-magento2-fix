<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\Conversions;

/**
 * Class Grid
 *
 * @package Egoi\Marketing\Block\Adminhtml\Conversions
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
    protected $registry = null;

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $conversionsFactory;

    /**
     * Grid constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                           $context
     * @param \Magento\Backend\Helper\Data                                      $backendHelper
     * @param \Egoi\Marketing\Model\ConversionsFactory                          $conversionsFactory
     * @param \Egoi\Marketing\Model\ResourceModel\Conversions\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry                                       $registry
     * @param array                                                             $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                           $context,
        \Magento\Backend\Helper\Data                                      $backendHelper,
        \Egoi\Marketing\Model\ConversionsFactory                          $conversionsFactory,
        \Egoi\Marketing\Model\ResourceModel\Conversions\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry                                       $registry,
        array                                                             $data = []
    )
    {

        $this->registry = $registry;
        $this->_collectionFactory = $collectionFactory;
        $this->conversionsFactory = $conversionsFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    public function _construct()
    {

        parent::_construct();
        $this->setId('conversions_grid');
        $this->setDefaultSort('conversion_id');
        $this->setDefaultDir('DESC');
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {

        $collection = $this->_collectionFactory->create();

        if ($this->getRequest()->getParam('display') == 'campaigns') {
            $collection->getSelect()->group('utm_campaign');
            $collection->getSelect()->columns(['order_amount' => new \Zend_Db_Expr('SUM(order_amount)')]);
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        if ($this->getRequest()->getParam('display') == 'campaigns') {
            $this->addColumn(
                'utm_campaign',
                [
                    'header' => __('Campaign'),
                    'type'   => 'text',
                    'index'  => 'utm_campaign',
                ]
            );

            $this->addColumn(
                'eg_cam',
                [
                    'header' => __('E-Goi Campaign'),
                    'type'   => 'text',
                    'index'  => 'eg_cam',
                ]
            );

            $this->addColumn(
                'order_amount',
                [
                    'header' => __('Order Amnt'),
                    'align'  => 'left',
                    'index'  => 'order_amount',
                    'type'   => 'price',
                ]
            );

        } else {

            $this->addColumn(
                'conversion_id',
                [
                    'header' => __('ID'),
                    'align'  => 'right',
                    'width'  => '90px',
                    'index'  => 'conversion_id',
                ]
            );

            $this->addColumn(
                'email',
                [
                    'header' => __('Email'),
                    'align'  => 'center',
                    'index'  => 'email',
                ]
            );

            $this->addColumn(
                'utm_term',
                [
                    'header' => __('Term'),
                    'type'   => 'text',
                    'index'  => 'utm_term',
                ]
            );

            $this->addColumn(
                'utm_campaign',
                [
                    'header' => __('Campaign'),
                    'type'   => 'text',
                    'index'  => 'utm_campaign',
                ]
            );

            $this->addColumn(
                'eg_cam',
                [
                    'header' => __('E-Goi Campaign'),
                    'type'   => 'text',
                    'index'  => 'eg_cam',
                ]
            );

            $this->addColumn(
                'eg_sub',
                [
                    'header' => __('Sub ID'),
                    'type'   => 'text',
                    'index'  => 'eg_sub',
                ]
            );

            $this->addColumn(
                'order_id',
                [
                    'header' => __('Order ID'),
                    'align'  => 'left',
                    'index'  => 'order_id',
                ]
            );

            $this->addColumn(
                'order_amount',
                [
                    'header' => __('Order Amnt'),
                    'align'  => 'left',
                    'index'  => 'order_amount',
                    'type'   => 'price',
                ]
            );

            $this->addColumn(
                'created_at',
                [
                    'header' => __('Date'),
                    'align'  => 'left',
                    'type'   => 'datetime',
                    'index'  => 'created_at',
                ]
            );
        }

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {

        return false;
    }

}
