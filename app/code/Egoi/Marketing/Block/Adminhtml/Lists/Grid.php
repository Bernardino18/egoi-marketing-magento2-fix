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
 * Class Grid
 *
 * @package Egoi\Marketing\Block\Adminhtml\Lists
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Lists\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context                          $context
     * @param \Magento\Backend\Helper\Data                                     $backendHelper
     * @param \Egoi\Marketing\Model\ResourceModel\Lists\CollectionFactory      $collectionFactory
     * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
     * @param array                                                            $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                          $context,
        \Magento\Backend\Helper\Data                                     $backendHelper,
        \Egoi\Marketing\Model\ResourceModel\Lists\CollectionFactory      $collectionFactory,
        \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
        array                                                            $data = []
    )
    {

        $this->_collectionFactory = $collectionFactory;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {

        parent::_construct();
        $this->setId('egoiListGrid');
        $this->setDefaultSort('list_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
        $this->setSaveParametersInSession(true);
        $this->setPagerVisibility(false);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {

        $collection = $this->_collectionFactory->create();
        /* @var $collection \Egoi\Marketing\Model\ResourceModel\Lists\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'listnum',
            [
                'header' => __('ID'),
                'align'  => 'right',
                'width'  => '50px',
                'index'  => 'listnum',
            ]
        );

        $this->addColumn(
            'internal_name',
            [
                'header' => __('Name'),
                'align'  => 'left',
                'index'  => 'internal_name',
            ]
        );

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'align'  => 'left',
                'index'  => 'title',
            ]
        );

        $this->addColumn(
            'subs_activos',
            [
                'header' => __('Subscribers'),
                'align'  => 'left',
                'index'  => 'subs_activos',
            ]
        );

        return parent::_prepareColumns();
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
