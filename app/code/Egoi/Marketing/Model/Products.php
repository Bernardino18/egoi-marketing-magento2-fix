<?php

namespace Egoi\Marketing\Model;

/**
 * Class Products
 *
 * @package Egoi\Marketing\Model
 */
class Products
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollection;

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $_quoteCollection;

    /**
     * @var array
     */
    protected $_productIds = [];

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollection;

    /**
     * @var \Magento\Reports\Model\ResourceModel\Event\CollectionFactory
     */
    protected $_eventsCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Wishlist\Model\WishlistFactory
     */
    protected $_wishlistFactory;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $_cacheManager;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Catalog\Model\ConfigFactory
     */
    protected $_configAttributes;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_coreDate;

    /**
     * @var \Magento\Reports\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_soldCollection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @param \Magento\Framework\Registry                                        $registry
     * @param \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory         $quoteCollection
     * @param \Egoi\Marketing\Helper\Data                                        $newsletterData
     * @param \Magento\Catalog\Model\ProductFactory                              $productFactory
     * @param \Magento\Catalog\Model\Product\Visibility                          $visibilityFactory
     * @param \Magento\Catalog\Model\Config|\Magento\Catalog\Model\ConfigFactory $configFactory
     * @param \Magento\Wishlist\Model\WishlistFactory                            $wishlistFactory
     * @param \Magento\Reports\Model\ResourceModel\Product\CollectionFactory     $soldCollection
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory         $orderCollection
     * @param \Magento\Reports\Model\ResourceModel\Event\CollectionFactory       $eventsCollection
     * @param \Magento\Store\Model\StoreManagerInterface                         $storeManager
     * @param \Magento\Framework\App\CacheInterface                              $cacheInterface
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                        $dateTime
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory     $productCollection
     */
    public function __construct(
        \Magento\Framework\Registry                                    $registry,
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory     $quoteCollection,
        \Egoi\Marketing\Helper\Data                                    $newsletterData,
        \Magento\Catalog\Model\ProductFactory                          $productFactory,
        \Magento\Catalog\Model\Product\Visibility                      $visibilityFactory,
        \Magento\Catalog\Model\Config                                  $configFactory,
        \Magento\Wishlist\Model\WishlistFactory                        $wishlistFactory,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $soldCollection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory     $orderCollection,
        \Magento\Reports\Model\ResourceModel\Event\CollectionFactory   $eventsCollection,
        \Magento\Store\Model\StoreManagerInterface                     $storeManager,
        \Magento\Framework\App\CacheInterface                          $cacheInterface,
        \Magento\Framework\Stdlib\DateTime\DateTime                    $dateTime,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection
    )
    {

        $this->_soldCollection = $soldCollection;
        $this->_quoteCollection = $quoteCollection;
        $this->_productCollection = $productCollection;
        $this->_helperData = $newsletterData;
        $this->_productFactory = $productFactory;
        $this->_orderCollection = $orderCollection;
        $this->_eventsCollection = $eventsCollection;
        $this->_storeManager = $storeManager;
        $this->_wishlistFactory = $wishlistFactory;
        $this->_cacheManager = $cacheInterface;
        $this->_configAttributes = $configFactory;
        $this->_visibility = $visibilityFactory;
        $this->_coreDate = $dateTime;
        $this->_registry = $registry;
    }

    /**
     * @return \Egoi\Marketing\Model\Subscriber
     */
    public function getSubscriber()
    {

        return $this->_registry->registry('egoi_subscriber');

    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function getWidget($data)
    {

        $storeId = $this->getSubscriber()->getStoreId();

        if (!$storeId) {
            $storeId = $this->_storeManager->getDefaultStoreView()->getId();
        }

        $this->_storeManager->getStore()->setId($storeId);

        $segments = explode(',', $data['segments']);
        $segments = array_values($segments);
        $segments['number_products'] = $data['number_products'];
        $segments['category'] = $data['category'];

        $productsIds[] = $this->getRelatedProductsFromLastOrder($segments);
        $productsIds[] = $this->getRelatedProducts($segments);
        $productsIds[] = $this->getAbandonedCart($segments);
        $productsIds[] = $this->getViewsProducts($segments);
        $productsIds[] = $this->getWishlistProducts($segments);
        $productsIds[] = $this->getCategoriesProducts($segments);
        $productsIds[] = $this->getCategoryProducts($segments);
        $productsIds[] = $this->getAttributesProducts($segments);
        $productsIds[] = $this->getNewProducts($segments);

        $prod = [];
        foreach ($productsIds as $list) {
            if (is_array($list)) {
                foreach ($list as $value) {
                    $prod[$value] = $value;
                }
            }
        }

        $productsIds = $prod;

        $catalog = $this->_productCollection->create()
                                            ->addAttributeToFilter('entity_id', ['in' => $productsIds])
                                            ->setVisibility($this->_visibility->getVisibleInCatalogIds())
                                            ->addMinimalPrice()
                                            ->addFinalPrice()
                                            ->addTaxPercents()
                                            ->addAttributeToSelect($this->_configAttributes->getProductAttributes())
                                            ->addUrlRewrite()
                                            ->addStoreFilter()
                                            ->setPageSize($segments['number_products'])
                                            ->setCurPage(1);

        switch ($data['sort_results']) {
            case 'random':
                $catalog->getSelect()->order('rand()');
                break;
            case 'created_at':
                $catalog->addAttributeToSort('created_at', 'DESC');
                break;
            case 'price_asc':
                $catalog->addAttributeToSort('price', 'ASC');
                break;
            case 'price_desc':
            default:
                $catalog->addAttributeToSort('price', 'DESC');
                break;
        }

        return $catalog;
    }

    /**
     *
     * @param $info
     *
     * @return boolean
     */
    public function getWishlistProducts($info)
    {

        $customerId = $this->getSubscriber()->getCustomerId();

        if (!$customerId) {
            return false;
        }

        if (!in_array('wishlist', $info)) {
            return false;
        }

        if (isset($this->_productIds['wishlist'])) {
            return $this->_productIds['wishlist'];
        }

        /** @var \Magento\Wishlist\Model\Wishlist $wishlist */
        $wishlist = $this->_wishlistFactory->create()->loadByCustomerId($customerId)
                                           ->getItemCollection()
                                           ->setOrder('added_at', 'asc');

        $productsIds = [];

        foreach ($wishlist as $item) {
            $productsIds[] = $item->getProductId();
        }

        $this->_productIds['wishlist'] = $productsIds;

        return $this->_productIds['wishlist'];
    }

    /**
     *
     * @param array $info
     *
     * @return boolean
     */
    public function getCategoriesProducts($info)
    {

        if (!in_array('categories', $info)) {
            return false;
        }

        if (isset($this->_productIds['categories'])) {
            return $this->_productIds['categories'];
        }

        $customerId = $this->getSubscriber()->getCustomerId();

        if (!$customerId) {
            return false;
        }

        $info[] = 'views';

        $items = $this->getViewsProducts($info);

        $productsIds = [];
        $cats = [];

        foreach ($items as $item) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->_productFactory->create()->load($item);

            if (!$product->getId()) {
                continue;
            }

            $rp = $product->getCategoryIds();
            foreach ($rp as $value) {
                $cats[] = $value;
            }
        }

        $cats = array_unique($cats);

        $collection = $this->_productCollection->create();
        $collection->joinField(
            'category_id',
            'catalog/category_product',
            'category_id',
            'product_id=entity_id',
            null,
            'left'
        );
        $collection->distinct(true);
        $collection->addAttributeToFilter('category_id', ['in' => ['finset' => implode(',', $cats)]]);
        $collection->addAttributeToSort('price', 'desc');
        $collection->setPageSize($info['number_products']);

        foreach ($collection as $product) {
            $productsIds[] = $product->getId();
        }

        $this->_productIds['categories'] = $productsIds;

        return $this->_productIds['categories'];
    }

    /**
     *
     * @param array $info
     *
     * @return boolean
     */
    public function getCategoryProducts($info)
    {

        if (!in_array('category', $info)) {
            return false;
        }

        if (isset($this->_productIds['category'])) {
            return $this->_productIds['category'];
        }

        $customerId = $this->getSubscriber()->getCustomerId();

        if (!$customerId) {
            return false;
        }

        $info[] = 'views';

        $collection = $this->_productCollection->create();
        $collection->joinField(
            'category_id',
            'catalog/category_product',
            'category_id',
            'product_id=entity_id',
            null,
            'left'
        );
        $collection->distinct(true);
        $collection->addAttributeToFilter('category_id', $info['category']);
        $collection->addAttributeToSort('price', 'desc');
        $collection->setPageSize($info['number_products']);

        foreach ($collection as $product) {
            $productsIds[] = $product->getId();
        }

        $this->_productIds['category'] = $productsIds;

        return $this->_productIds['category'];
    }

    /**
     *
     * @param array $info
     *
     * @return boolean
     */
    public function getAttributesProducts($info)
    {

        if (!in_array('attributes', $info)) {
            return false;
        }

        if (isset($this->_productIds['attributes'])) {
            return $this->_productIds['attributes'];
        }

        $customerId = $this->getSubscriber()->getCustomerId();

        if (!$customerId) {
            return false;
        }

        $info[] = 'views';
        $items = $this->getViewsProducts($info);

        $products = $this->_productCollection->create()
                                             ->addAttributeToSort('price', 'desc')
                                             ->setPageSize($info['number_products'])
                                             ->addAttributeToFilter('entity_id', ['in' => $items]);

        $productsIds = [];

        $attrs = [];

        foreach ($products as $product) {

            $attributes = $product->getAttributes();
            foreach ($attributes as $attribute) {
                if ($attribute->getData('is_filterable')) {
                    if (!isset($attrs[$attribute->getName()])) {
                        $attrs[$attribute->getName()] = 1;
                    } else {
                        $attrs[$attribute->getName()] = $attrs[$attribute->getName()] + 1;
                    }
                }
            }
        }

        ksort($attrs);
        $attr = array_keys($attrs);

        if (count($attr) == 0) {
            return [];
        }
        $attributeId = $attr[0];

        $catalog = $this->_productCollection->create()
                                            ->addAttributeToFilter($attributeId, ['neq' => 'egoi']);

        $catalog->setPageSize($info['number_products']);

        foreach ($catalog as $prod) {
            $productsIds[$prod->getId()] = $prod->getId();
        }

        $this->_productIds['attributes'] = $productsIds;

        return $this->_productIds['attributes'];
    }

    /**
     *
     * @param $info
     *
     * @return boolean
     */
    public function getRelatedProductsFromLastOrder($info)
    {

        $customerId = $this->getSubscriber()->getCustomerId();
        $customerEmail = $this->getSubscriber()->getEmail();

        if (!$customerEmail && !$customerEmail) {
            return false;
        }

        if (!in_array('related_order', $info)) {
            return false;
        }

        if (isset($this->_productIds['related_order'])) {
            return $this->_productIds['related_order'];
        }

        $orders = $this->_orderCollection->create()
                                         ->addAttributeToSelect('entity_id')
                                         ->addAttributeToFilter('state', 'complete')
                                         ->setOrder('created_at', 'DESC')
                                         ->setPageSize(1);

        if ($customerId) {
            $orders->addAttributeToFilter('customer_id', $customerId);
        } else {
            $orders->addAttributeToFilter('customer_email', $customerEmail);
        }

        $productsIds = [];

        if ($orders->getSize() == 0) {
            return false;
        }

        $items = $orders->getFirstItem()->getItemsCollection();
        foreach ($items as $item) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->_productFactory->create()->load($item->getProductId());

            if (!$product->getId()) {
                continue;
            }

            $rp = $product->getRelatedProductIds();
            foreach ($rp as $value) {
                $productsIds[$value] = $value;
            }
        }

        $this->_productIds['related_order'] = $productsIds;

        return $this->_productIds['related_order'];
    }

    /**
     *
     * @param $info
     *
     * @return boolean
     */
    public function getRelatedProducts($info)
    {

        $customerId = $this->getSubscriber()->getCustomerId();
        $customerEmail = $this->getSubscriber()->getEmail();

        if (!$customerEmail && !$customerEmail) {
            return false;
        }

        if (!in_array('related', $info)) {
            return false;
        }

        if (isset($this->_productIds['related'])) {
            return $this->_productIds['related'];
        }

        $orders = $this->_orderCollection->create()
                                         ->addAttributeToSelect('entity_id')
                                         ->addAttributeToFilter('state', 'complete');

        if ($customerId) {
            $orders->addAttributeToFilter('customer_id', $customerId);
        } else {
            $orders->addAttributeToFilter('customer_email', $customerEmail);
        }

        $productsIds = [];

        foreach ($orders as $order) {
            $items = $order->getItemsCollection();

            foreach ($items as $item) {
                /** @var \Magento\Catalog\Model\Product $product */
                $product = $this->_productFactory->create()->load($item->getProductId());

                if (!$product->getId()) {
                    continue;
                }

                $rp = $product->getRelatedProductIds();
                foreach ($rp as $value) {
                    $productsIds[$value] = $value;
                }
            }
        }

        $this->_productIds['related'] = $productsIds;

        return $this->_productIds['related'];
    }

    /**
     * @param $info
     *
     * @return bool
     */
    public function getAbandonedCart($info)
    {

        $customerId = $this->getSubscriber()->getCustomerId();
        $customerEmail = $this->getSubscriber()->getEmail();

        if (!$customerEmail && !$customerEmail) {
            return false;
        }

        if (!in_array('abandoned', $info)) {
            return false;
        }

        if (isset($this->_productIds['abandoned'])) {
            return $this->_productIds['abandoned'];
        }

        $quote = $this->_quoteCollection->create()
                                        ->addFieldToSelect('*')
                                        ->addFieldToFilter('store_id', $this->_storeManager->getStore()->getId())
                                        ->addFieldToFilter('items_count', ['neq' => '0'])
                                        ->addFieldToFilter('is_active', '1')
                                        ->setOrder('updated_at', 'DESC');

        if ($customerEmail) {
            $quote->addFieldToFilter('customer_email', $customerEmail);
        } else {
            $quote->addFieldToFilter('customer_id', $customerId);
        }

        if ($quote->getSize() == 0) {
            return false;
        }

        $productsIds = [];

        $items = $quote->getFirstItem()->getItemsCollection();

        foreach ($items as $item) {
            if ($item->getParentItemId()) {
                continue;
            }
            $productsIds[$item->getProductId()] = $item->getProductId();
        }

        $this->_productIds['abandoned'] = $productsIds;

        return $this->_productIds['abandoned'];
    }

    /**
     *
     * @param $info
     *
     * @return boolean
     */
    public function getNewProducts($info)
    {

        if (!in_array('new', $info)) {
            return false;
        }

        if (isset($this->_productIds['new'])) {
            return $this->_productIds['new'];
        }

        $cache = $this->_cacheManager;
        $key = 'egoi-recent-products';

        if (!$productsIds = $cache->load($key)) {

            $todayDate = $this->_coreDate->gmtDate();

            $collection = $this->_productCollection->create();
            $collection->setVisibility($this->_visibility->getVisibleInSiteIds());

            $collection->addAttributeToFilter(
                'news_from_date',
                [
                    'or' => [
                        0 => ['date' => true, 'to' => $todayDate],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ],
                ],
                'left'
            )
                       ->addAttributeToFilter(
                           'news_to_date',
                           [
                               'or' => [
                                   0 => ['date' => true, 'from' => $todayDate],
                                   1 => ['is' => new \Zend_Db_Expr('null')],
                               ],
                           ],
                           'left'
                       )
                       ->addAttributeToSort('news_from_date', 'desc')
                       ->setPageSize($info['number_products']);

            $productsIds = [];

            foreach ($collection as $value) {
                $productsIds[] = $value->getId();
            }
            $cache->save(serialize($productsIds), $key, [], 60 * 60 * 2);
        } else {
            $productsIds = unserialize($productsIds);
        }
        $this->_productIds['new'] = $productsIds;

        return $this->_productIds['new'];
    }

    /**
     * @param $info
     *
     * @return bool
     */
    public function getViewsProducts($info)
    {

        $customerId = $this->getSubscriber()->getCustomerId();

        if (!in_array('views', $info)) {
            return [];
        }

        if (isset($this->_productIds['views'])) {
            return $this->_productIds['views'];
        }

        $cache = $this->_cacheManager;
        $key = 'egoi-products-views';

        if (!$productsIds = $cache->load($key)) {

            $storeId = $this->_storeManager->getStore()->getId();
            $products = $this->_soldCollection->create()
                                              ->addAttributeToSelect('entity_id')
                                              ->setStoreId($storeId)
                                              ->addStoreFilter($storeId)
                                              ->addViewsCount()
                                              ->setPageSize($info['number_products']);

            foreach ($products as $product) {
                $productsIds[$product->getEntityId()] = $product->getEntityId();
            }

            $cache->save(serialize($productsIds), $key, [], 60 * 60 * 2);
        } else {
            $productsIds = unserialize($productsIds);
        }

        $this->_productIds['views'] = $productsIds;

        return $this->_productIds['views'];
    }

}
