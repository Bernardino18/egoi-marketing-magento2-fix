<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Egoi\Marketing\Ui\Component\Listing\Column\Autoresponders;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

/**
 * Class Queue
 *
 * @package Egoi\Marketing\Ui\Component\Listing\Column\Autoresponders
 */
class Queue extends Column
{

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Autoresponders\CollectionFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory
     */
    protected $_eventsFactory;

    /**
     * Queue constructor.
     *
     * @param \Egoi\Marketing\Model\ResourceModel\Autoresponders\CollectionFactory $autorespondersFactory
     * @param \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory         $eventsFactory
     * @param \Magento\Framework\UrlInterface                                      $urlInterface
     * @param ContextInterface                                                     $context
     * @param UiComponentFactory                                                   $uiComponentFactory
     * @param array                                                                $components
     * @param array                                                                $data
     */
    public function __construct(
        \Egoi\Marketing\Model\ResourceModel\Autoresponders\CollectionFactory $autorespondersFactory,
        \Egoi\Marketing\Model\ResourceModel\Events\CollectionFactory         $eventsFactory,
        \Magento\Framework\UrlInterface                                      $urlInterface,
        ContextInterface                                                     $context,
        UiComponentFactory                                                   $uiComponentFactory,
        array                                                                $components,
        array                                                                $data
    )
    {

        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->_eventsFactory = $eventsFactory;
        $this->_autorespondersFactory = $autorespondersFactory;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {

                if (!$item['autoresponder_id']) {
                    return $dataSource;
                }

                $total = $this->_eventsFactory->create()
                                              ->addFieldToFilter('sent', 0)
                                              ->addFieldToFilter('autoresponder_id', $item['autoresponder_id'])
                                              ->getSize();

                $item[$this->getData('name')]['views'] = [
                    'href'   => $this->_urlInterface->getUrl(
                        'egoi/events/index',
                        ['id' => $item['autoresponder_id']]
                    ),
                    'label'  => __('Queue') . " ($total)",
                    'hidden' => false,
                ];
            }
        }

        return $dataSource;
    }
}
