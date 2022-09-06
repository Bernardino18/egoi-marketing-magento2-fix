<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Egoi\Marketing\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

/**
 * Class Concat
 *
 * @package Egoi\Marketing\Ui\Component\Listing\Column
 */
class Concat extends Column
{

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     *  constructor.
     *
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param ContextInterface                $context
     * @param UiComponentFactory              $uiComponentFactory
     * @param array                           $components
     * @param array                           $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        ContextInterface                $context,
        UiComponentFactory              $uiComponentFactory,
        array                           $components,
        array                           $data
    )
    {

        parent::__construct($context, $uiComponentFactory, $components, $data);

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

                $fields = explode(',', $this->getData('config/fields'));
                $separator = $this->getData('config/separator');
                $return = [];
                foreach ($fields as $field) {
                    $return[] = $item[$field];
                }

                $item[$this->getData('name')] = implode($separator, $return);

            }
        }

        return $dataSource;
    }
}
