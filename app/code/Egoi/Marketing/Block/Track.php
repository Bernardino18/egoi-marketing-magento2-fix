<?php

namespace Egoi\Marketing\Block;

/**
 * Class Track
 *
 * @package Egoi\Marketing\Block
 */
class Track extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Sales\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Sales\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollection;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $_helper;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkout;

    /**
     * @var \Egoi\Marketing\Model\AccountFactory
     */
    protected $_accountFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $configurableType;

    /**
     * Track constructor.
     *
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Checkout\Model\Session                              $checkout
     * @param \Magento\Customer\Model\Session                              $customerSession
     * @param \Egoi\Marketing\Helper\Data                                  $helper
     * @param \Egoi\Marketing\Model\AccountFactory                         $accountFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory   $orderCollection
     * @param \Magento\Catalog\Model\ProductFactory                        $productFactory
     * @param \Magento\Catalog\Model\CategoryFactory                       $categoryFactory
     * @param \Magento\Framework\View\Element\Template\Context             $context
     */
    public function __construct(
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType,
        \Magento\Framework\Registry                                  $registry,
        \Magento\Checkout\Model\Session                              $checkout,
        \Magento\Customer\Model\Session                              $customerSession,
        \Egoi\Marketing\Helper\Data                                  $helper,
        \Egoi\Marketing\Model\AccountFactory                         $accountFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory   $orderCollection,
        \Magento\Catalog\Model\ProductFactory                        $productFactory,
        \Magento\Catalog\Model\CategoryFactory                       $categoryFactory,
        \Magento\Framework\View\Element\Template\Context             $context
    )
    {

        $this->configurableType = $configurableType;
        $this->_registry = $registry;
        $this->_customerSession = $customerSession;
        $this->_accountFactory = $accountFactory;
        $this->_checkout = $checkout;
        $this->_helper = $helper;
        $this->_orderCollection = $orderCollection;
        $this->_productFactory = $productFactory;
        $this->_categoryFactory = $categoryFactory;

        parent::__construct($context);
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {

        return $this->_customerSession->getCustomerId();
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {

        return $this->_accountFactory->create()->getAccount();
    }

    /**
     * @return string|void
     */
    public function getOrdersTrackingCode()
    {

        $order = $this->_checkout->getLastRealOrder();
        if (!$order->getId()) {
            return;
        }

        $result = [];

        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($order->getAllVisibleItems() as $item) {

            if ($item->getQtyOrdered()) {
                $qty = number_format($item->getQtyOrdered(), 0, '.', '');
            } else {
                $qty = '0';
            }

            $productId = $item->getProductId();
            if ($item->getParentItem()) {
                $productId = $item->getParentItem()->getProductId();
            }

            $result[] = sprintf(
                "_egoiaq.push(['addEcommerceItem',  '%s', '%s', '%s', %s, %s]);",
                $this->escapeJsQuote($productId),
                $this->escapeJsQuote($item->getName()),
                '',
                number_format($item->getBasePrice(), 2),
                $qty
            );

        }

        $subtotal = number_format(
            $order->getGrandTotal() - $order->getShippingAmount() - $order->getShippingTaxAmount(),
            2
        );

        $result[] = sprintf(
            "_egoiaq.push(['trackEcommerceOrder', '%s', '%s','%s', '%s', '%s']);",
            $order->getIncrementId(),
            number_format($order->getBaseGrandTotal(), 2),
            $subtotal,
            number_format($order->getBaseTaxAmount(), 2),
            number_format($order->getBaseShippingAmount(), 2)
        );

        return implode("\n", $result);
    }

    /**
     * @return mixed|string
     */
    protected function _toHtml()
    {

        if (!$this->_helper->getScopeConfig()->getValue('egoi/info/webpush') &&
            !$this->_helper->isTrackAvailable($this->_storeManager->getStore()->getId())) {
            return '';
        }

        return str_replace("\n\n", "\n", parent::_toHtml());
    }

    /**
     * @return int
     */
    public function getStoreId()
    {

        return $this->_storeManager->getStore()->getId();
    }

}