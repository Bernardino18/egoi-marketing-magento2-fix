<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Helper;

use Magento\Framework\App\Helper\Context;

/**
 * Class Data
 *
 * @package Egoi\Marketing\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     *
     */
    const EGOI_COOKIE_NAME = '_egoi_subscriber';

    /**
     *
     */
    const XML_PATH_ACTIVE = 'egoi/info/analytics';

    /**
     *
     */
    const TRANSACTIONAL_SERVER = 'bo51.e-goi.com';

    /**
     *
     */
    const TRANSACTIONAL_PORT = 587;

    /**
     *
     */
    const TRANSACTIONAL_AUTH = 'login';

    /**
     *
     */
    const TRANSACTIONAL_SSL = 'TLS';

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata
     */
    protected $cookieMetadataFactory;

    /**
     * /**
     * @var \Egoi\Marketing\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_subscriberCollection;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var
     */
    protected $scopeConfig;

    /**
     * @var \Egoi\Marketing\Model\ListsFactory
     */
    protected $listsFactory;

    /**
     * protected $storeManagerInterface;
     * /**
     *
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $coreSubscriberFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerInterface;

    /**
     * @param \Magento\Newsletter\Model\SubscriberFactory                      $coreSubscriberFactory
     * @param \Magento\Store\Model\StoreManagerInterface                       $storeManagerInterface
     * @param \Egoi\Marketing\Model\ListsFactory                               $listsFactory
     * @param \Magento\Framework\Stdlib\CookieManagerInterface                 $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata            $publicCookieMetadata
     * @param \Magento\Framework\App\Config\ScopeConfigInterface               $scope
     * @param \Magento\Framework\Encryption\EncryptorInterface                 $encryptor
     * @param \Magento\Framework\App\ProductMetadataInterface                  $productMetadata
     * @param \Magento\Checkout\Model\Session                                  $checkoutSession
     * @param \Magento\Customer\Model\Session                                  $customerSession
     * @param Context                                                          $context
     * @param \Egoi\Marketing\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollection
     */
    public function __construct(
        \Magento\Newsletter\Model\SubscriberFactory                      $coreSubscriberFactory,
        \Magento\Store\Model\StoreManagerInterface                       $storeManagerInterface,
        \Egoi\Marketing\Model\ListsFactory                               $listsFactory,
        \Magento\Framework\Stdlib\CookieManagerInterface                 $cookieManager,
        \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata            $publicCookieMetadata,
        \Magento\Framework\App\Config\ScopeConfigInterface               $scope,
        \Magento\Framework\Encryption\EncryptorInterface                 $encryptor,
        \Magento\Framework\App\ProductMetadataInterface                  $productMetadata,
        \Magento\Checkout\Model\Session                                  $checkoutSession,
        \Magento\Customer\Model\Session                                  $customerSession,
        Context                                                          $context,
        \Egoi\Marketing\Model\ResourceModel\Subscriber\CollectionFactory $subscriberCollection
    )
    {

        parent::__construct($context);
        $this->storeManagerInterface = $storeManagerInterface;
        $this->listsFactory = $listsFactory;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $publicCookieMetadata;
        $this->productMetadata = $productMetadata;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_subscriberCollection = $subscriberCollection;
        $this->encryptor = $encryptor;
        $this->scopeConfig = $scope;
        $this->coreSubscriberFactory = $coreSubscriberFactory;
    }

    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig()
    {

        return $this->scopeConfig;
    }

    /**
     * @return \Magento\Framework\Encryption\EncryptorInterface
     */
    public function getEncryptor()
    {

        return $this->encryptor;
    }

    /**
     * @param        $ver
     * @param string $operator
     *
     * @return mixed
     */
    public function versionCompare($ver, $operator = '>=')
    {

        return version_compare($this->productMetadata->getVersion(), $ver, $operator);
    }

    /**
     *
     * @param mixed $store
     *
     * @return bool
     */
    public function isTrackAvailable($store = false)
    {

        if ($store === false) {
            $store = $this->storeManagerInterface->getStore()->getId();
        }

        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @return bool|\Magento\Framework\DataObject
     */
    public function loadSubscriber()
    {

        $fieldValue = $this->getCustomerEmail();
        $fieldName = 'email';

        if (!$fieldValue) {
            $fieldValue = $this->getCustomerId();
            $fieldName = 'customer_id';
        }
        if (!$fieldValue) {
            return false;
        }

        $col = $this->_subscriberCollection->create()
                                           ->addFieldToFilter($fieldName, $fieldValue);

        return $col->getSize() > 0 ? $col->getFirstItem() : false;
    }

    /**
     * @return bool|string
     */
    public function getCustomerEmail()
    {

        $quote = $this->_checkoutSession->getQuote();
        if ($this->_customerSession->getCustomer() && $this->_customerSession->getCustomer()->getEmail()) {
            return $this->_customerSession->getCustomer()->getEmail();
        } elseif ($quote->getCustomerEmail()) {
            return $quote->getCustomerEmail();
        } elseif ($quote->getBillingAddress() && $quote->getBillingAddress()->getEmail()) {
            return $quote->getBillingAddress()->getEmail();
        }

        return false;
    }

    /**
     * @return bool|int|mixed|null
     */
    public function getCustomerId()
    {

        $quote = $this->_checkoutSession->getQuote();
        if ($quote->getCustomerId()) {
            return $quote->getCustomerId();
        } elseif ($this->_customerSession->getCustomerId()) {
            return $this->_customerSession->getCustomerId();
        }

        return false;
    }

    /**
     * @return string
     */
    public function getSmtpServer()
    {

        return self::TRANSACTIONAL_SERVER;
    }

    /**
     * @return array
     */
    public function getSmtpDetails()
    {

        return [
            'auth'     => self::TRANSACTIONAL_AUTH,
            'ssl'      => self::TRANSACTIONAL_SSL,
            'port'     => self::TRANSACTIONAL_PORT,
            'username' => $this->scopeConfig->getValue('egoi/transactional/username'),
            'password' => $this->scopeConfig->getValue('egoi/transactional/password'),
        ];
    }

    /**
     *
     * @return \Zend_Mail_Transport_Smtp
     */
    public function getSmtpTransport()
    {

        $config = ['auth' => self::TRANSACTIONAL_AUTH, 'port' => self::TRANSACTIONAL_PORT];

        $config['ssl'] = self::TRANSACTIONAL_SSL;

        $config['username'] = $this->scopeConfig->getValue('egoi/transactional/username');
        $config['password'] = $this->scopeConfig->getValue('egoi/transactional/password');

        return new \Zend_Mail_Transport_Smtp(self::TRANSACTIONAL_SERVER, $config);
    }

    /**
     * @param string $file
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger($file = '')
    {

        return $this->_logger;
    }

    /**
     * @param \Magento\Customer\Model\Customer $customer
     *
     * @return false|void
     */
    public function newCustomer(\Magento\Customer\Model\Customer $customer)
    {

        try {

            if (!$this->scopeConfig->getValue('egoi/info/auto')) {
                return false;
            }

            $email = $customer->getCustomerEmail();

            $subscriber = $this->_subscriberCollection->create()
                                                      ->addFieldToFilter('email', $email)
                                                      ->getFirstItem();

            if (!$subscriber->getId()) {
                $subscriber->subscribe($email);
            }

            if ($subscriber->getData('uid')) {
                $this->setCookie($subscriber->getData('uid'));
            }
        } catch (\Exception $e) {
            $this->getLogger('critical')->critical($e->getMessage());
        }

    }

    /**
     * @param $uid
     */
    public function setCookie($uid)
    {

        $metadata = $this->cookieMetadataFactory->setDurationOneYear()
                                                ->setHttpOnly(false)
                                                ->setPath('/');

        $this->cookieManager->setPublicCookie(self::EGOI_COOKIE_NAME, $uid, $metadata);
    }

    /**
     * @param $uid
     */
    public function unsetCookie()
    {

        $metadata = $this->cookieMetadataFactory->setDuration(time() - 3600)
                                                ->setHttpOnly(false)
                                                ->setPath('/');

        $this->cookieManager->setPublicCookie(self::EGOI_COOKIE_NAME, '', $metadata);
    }

    /**
     * @param $email
     */
    public function setCookieByEmail($email)
    {

        /** @var \Egoi\Marketing\Model\Subscriber $uid */
        $uid = $this->_subscriberCollection->create()
                                           ->addFieldToFilter('email', $email)
                                           ->getFirstItem();

        if (!$uid->getData('uid')) {
            $core = $this->coreSubscriberFactory->create()->loadByEmail($email);
            if ($core->getId() && $core->getSubscriberStatus() == 1) {
                $uid->setInCallBack(true)->subscribe($email, null, false);
            }
        }

        if ($uid->getData('uid')) {
            $this->setCookie($uid->getData('uid'));
        }
    }

    /**
     *
     */
    public function getListId()
    {

        return $this->listsFactory->create()->getList()->getData('listnum');
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession()
    {

        return $this->_customerSession;
    }

    /**
     * @return string|null
     */
    public function getSubscriberCookie()
    {

        return $this->cookieManager->getCookie(self::EGOI_COOKIE_NAME);
    }

}
