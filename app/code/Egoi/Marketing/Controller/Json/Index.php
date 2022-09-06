<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Json;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Products
 */
class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_productImageHelper;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_helperCurrency;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\Pricing\Helper\Data      $helperCurrency
     * @param \Magento\Catalog\Model\ProductFactory       $productFactory
     * @param \Magento\Catalog\Model\CategoryFactory      $categoryFactory
     * @param \Magento\Framework\Registry                 $registry
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Catalog\Helper\Image               $productImageHelper
     * @param \Magento\Framework\App\Action\Context       $context
     */
    public function __construct(
        \Magento\Framework\Pricing\Helper\Data      $helperCurrency,
        \Magento\Catalog\Model\ProductFactory       $productFactory,
        \Magento\Catalog\Model\CategoryFactory      $categoryFactory,
        \Magento\Framework\Registry                 $registry,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Catalog\Helper\Image               $productImageHelper,
        \Magento\Framework\App\Action\Context       $context
    )
    {

        $this->_helperCurrency = $helperCurrency;
        $this->_productFactory = $productFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_subscriberFactory = $subscriberFactory;
        $this->_productImageHelper = $productImageHelper;
        $this->_registry = $registry;

        parent::__construct($context);
    }

    /**
     *
     */
    public function execute()
    {


        $categories = $this->getRequest()->getParam('categories');
        $categories = explode(',', $categories);
        $categories = array_map('trim', $categories);
        $categories = array_filter($categories);

        $storeId = $this->getRequest()->getParam('store_id');

        $ids = $this->getRequest()->getParam('ids');
        $ids = explode(',', $ids);
        $ids = array_map('trim', $ids);
        $ids = array_filter($ids);

        $skus = $this->getRequest()->getParam('skus');
        $skus = explode(',', $skus);
        $skus = array_map('trim', $skus);
        $skus = array_filter($skus);

        /** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
        $collection = $this->_productFactory->create()->getCollection()
                                            ->addAttributeToSelect('*')
                                            ->joinField(
                                                'qty',
                                                'cataloginventory_stock_item',
                                                'qty',
                                                'product_id=entity_id',
                                                '{{table}}.stock_id=1',
                                                'left'
                                            );

        foreach ($categories as $category) {
            $collection->addCategoryFilter($this->_categoryFactory->create()->load($category));
        }
        $collection->setFlag('has_stock_status_filter', true);

        $collection->addAttributeToSelect('price');
        $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner')
                   ->addAttributeToFilter('status', 1)
                   ->addAttributeToFilter('visibility', 4)
                   ->setCurPage($this->getRequest()->getParam('start', 1))
                   ->setPageSize($this->getRequest()->getParam('limit', 20));

        if (count($ids) > 0) {
            $collection->addAttributeToFilter('entity_id', ['in' => $ids]);
        }

        if (count($skus) > 0) {
            $collection->addAttributeToFilter('sku', ['in' => $skus]);
        }

        if ($storeId) {
            $collection->setStoreId($storeId);
        }

        $return = [];

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection as $product) {

            if (!$product->isSalable()) {
                continue;
            }

            $cats = $product->getCategoryCollection()->addAttributeToSelect('name');
            $rels = $product->getRelatedProductCollection();
            $ups = $product->getUpSellProductCollection();

            $price = $this->_helperCurrency->currency($product->getPrice(), true, false);

            $return = [
                'id'                => $product->getId(),
                'name'              => $product->getName(),
                'sku'               => $product->getSku(),
                'short_description' => $product->getShortDescription(),
                'price'             => $price,
                'url'               => $product->getProductUrl(),
                'thumbnail'         => (string) $this->resizeImage($product, 'product_small_image', 150, 150),
                'small_image'       => (string) $this->resizeImage($product, 'product_base_image', 300, 300),
                'image'             => (string) $this->resizeImage($product, 'product_base_image', 600, 600),
                'large_image'       => (string) $this->resizeImage($product, 'product_base_image', 1000),
            ];

            $specialPrice = $product->getSpecialPrice();
            if ($product->getPrice() > $product->getFinalPrice()) {
                $specialPrice = $product->getFinalPrice();
            }

            $return['special_price'] = $specialPrice > 0 ? $this->_helperCurrency->currency(
                $specialPrice,
                true,
                false
            ) : '';

            foreach ($cats as $cat) {
                $return['categories'][$cat->getId()] = $cat->getName();
            }

            foreach ($rels as $rel) {
                $return['related'][$rel->getId()] = $rel->getId();
            }

            foreach ($ups as $up) {
                $return['upsell'][$up->getId()] = $up->getId();
            }

            $return1['item'][] = $return;
            unset($return[$product->getId()]);

        }

        echo json_encode(['items' => $return1]);

        die();
    }

    /**
     * @param      $product
     * @param      $imageId
     * @param      $width
     * @param null $height
     *
     * @return string
     */
    public function resizeImage($product, $imageId, $width, $height = null)
    {

        $resizedImage = $this->_productImageHelper
            ->init($product, $imageId)
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize($width, $height)
            ->getUrl();

        return $resizedImage;
    }
}
