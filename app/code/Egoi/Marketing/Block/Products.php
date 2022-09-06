<?php

namespace Egoi\Marketing\Block;

/**
 * Class Track
 *
 * @package Egoi\Marketing\Block
 */
class Products extends \Magento\Catalog\Block\Product\AbstractProduct
{

    public function __construct(
        \Egoi\Marketing\Model\Products         $products,
        \Magento\Catalog\Block\Product\Context $context,
        array                                  $data = [])
    {

        parent::__construct($context, $data);

        $this->_products = $products;
    }

    protected function _toHtml()
    {

        $params = $this->getData('params');

        $this->setTemplate('email/' . $params['template'] . '.phtml');

        $model = $this->_products->getWidget($params);

        $this->setData('product_collection', $model);
        $this->setData('title', $params['title']);

        return parent::_toHtml();
    }

}
