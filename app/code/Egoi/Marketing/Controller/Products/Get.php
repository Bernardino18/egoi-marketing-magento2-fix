<?php

namespace Egoi\Marketing\Controller\Products;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Products
 */
class Get extends \Magento\Framework\App\Action\Action
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
     * Index constructor.
     *
     * @param \Magento\Framework\Registry                 $registry
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Framework\App\Action\Context       $context
     */
    public function __construct(
        \Magento\Framework\Registry                 $registry,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Framework\App\Action\Context       $context
    )
    {

        $this->_subscriberFactory = $subscriberFactory;
        $this->_registry = $registry;

        parent::__construct($context);
    }

    /**
     *
     */
    public function execute()
    {

        $subscriber = new \Magento\Framework\DataObject();

        $email = $this->getRequest()->getParam('email');
        $uid = $this->getRequest()->getParam('uid');

        $params = $this->getRequest()->getParams();

        $paramsDefault = [];
        $paramsDefault['number_products'] = 10;
        $paramsDefault['title'] = '';
        $paramsDefault['sort_results'] = 'price';
        $paramsDefault['segments'] = 'new';
        $paramsDefault['template'] = 'products';
        $paramsDefault['category'] = \Magento\Catalog\Model\Category::TREE_ROOT_ID;

        $params = array_merge($paramsDefault, $params);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $subscriber = $this->_subscriberFactory->create()->load($email, 'email');
        }

        if (!$subscriber->getId()) {
            $subscriber = $this->_subscriberFactory->create()->load($uid, 'uid');
        }

        $this->_registry->register('egoi_subscriber', $subscriber);

        $block = $this->_view->getLayout()->createBlock('Egoi\Marketing\Block\Products', 'egoi_products');
        $block->setData('params', $params);

        echo $block->toHtml();

        die();
    }

}
