<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Subscriber
 *
 * @package Egoi\Marketing\Model
 */
class Subscriber extends \Magento\Framework\Model\AbstractModel
{

    const STATUS_SUBSCRIBED = 1;

    const STATUS_UNSUBSCRIBED = 0;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'egoi_subscribers';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'egoi_subscribers';

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Session
     */
    protected $_egoiSession;

    /**
     * @var
     */
    protected $_shipmentFactory;

    /**
     * @var ResourceModel\Subscriber\CollectionFactory
     */
    protected $_subscriberCollection;

    /**
     * @var ListsFactory
     */
    protected $_listsFactory;

    /**
     * @var \Magento\Newsletter\Model\Subscriber
     */
    protected $_subscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_coreSubscriber;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_coreSubscriberCollection;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $_customerCollection;

    /**
     * @var ExtraFactory
     */
    protected $_extraFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var Egoi
     */
    protected $_egoi;

    /**
     * @var \Magento\Store\Model\StoreManager
     */
    protected $_storeManager;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $_egoiHelper;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('Egoi\Marketing\Model\ResourceModel\Subscriber');
    }

    /**
     * Subscriber constructor.
     *
     * @param \Magento\Framework\Filesystem                                        $filesystem
     * @param \Magento\Sales\Model\OrderFactory                                    $orderFactory
     * @param \Egoi\Marketing\Helper\Data                                          $egoiHelper
     * @param \Magento\Store\Model\StoreManager                                    $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                   $scopeConfig
     * @param \Magento\Framework\Model\Context                                     $context
     * @param \Magento\Framework\Registry                                          $registry
     * @param \Magento\Newsletter\Model\SubscriberFactory                          $coreSubscriber
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $coreSubscriberCollection
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory     $customerCollection
     * @param \Magento\Customer\Model\CustomerFactory                              $customerFactory
     * @param Egoi                                                                 $egoi
     * @param ListsFactory                                                         $listsFactory
     * @param ExtraFactory                                                         $extraFactory
     * @param SubscriberFactory                                                    $subscriberFactory
     * @param ResourceModel\Subscriber\CollectionFactory                           $subscriberCollection
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null         $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null                   $resourceCollection
     * @param array                                                                $data
     */
    public function __construct(
        \Magento\Framework\Filesystem                                        $filesystem,
        \Magento\Sales\Model\OrderFactory                                    $orderFactory,
        \Egoi\Marketing\Helper\Data                                          $egoiHelper,
        \Magento\Store\Model\StoreManager                                    $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface                   $scopeConfig,
        \Magento\Framework\Model\Context                                     $context,
        \Magento\Framework\Registry                                          $registry,
        \Magento\Newsletter\Model\SubscriberFactory                          $coreSubscriber,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $coreSubscriberCollection,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory     $customerCollection,
        \Magento\Customer\Model\CustomerFactory                              $customerFactory,
        Egoi                                                                 $egoi,
        ListsFactory                                                         $listsFactory,
        ExtraFactory                                                         $extraFactory,
        SubscriberFactory                                                    $subscriberFactory,
        ResourceModel\Subscriber\CollectionFactory                           $subscriberCollection,
        \Magento\Framework\Model\ResourceModel\AbstractResource              $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb                        $resourceCollection = null,
        array                                                                $data = []
    )
    {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->_egoiHelper = $egoiHelper;

        $this->_orderFactory = $orderFactory;

        $this->_egoi = $egoi;

        $this->_storeManager = $storeManager;

        $this->_scopeConfig = $scopeConfig;

        $this->_customerFactory = $customerFactory;

        $this->_listsFactory = $listsFactory;

        $this->_extraFactory = $extraFactory;

        $this->_subscriberFactory = $subscriberFactory;

        $this->_coreSubscriber = $coreSubscriber;

        $this->_customerCollection = $customerCollection;

        $this->_coreSubscriberCollection = $coreSubscriberCollection;

        $this->_subscriberCollection = $subscriberCollection;
        $this->_fileSystem = $filesystem;

    }

    /**
     * @return $this
     */
    function cron()
    {

        ini_set('max_execution_time', 18000);

        $day = '';
        $hour = 0;
        $start = 0;
        $file = $this->_fileSystem->getDirectoryRead(DirectoryList::LOG)
                                  ->getAbsolutePath('egoi.cron.log');

        if (is_file($file)) {
            $data = explode('-', file_get_contents($file));
            $day = (int) @$data[0];
            $hour = (int) @$data[1];
            $start = (int) @$data[2];
        }

        if ($day == date('d') && date('H') == $hour) {
            #  return $this;
        }

        $this->_egoiHelper->getLogger('subscriber')->info('cron started');

        $subscribers = $this->_subscriberCollection->create()
                                                   ->addFieldToFilter('email', '');
        $subscribers->walk('delete');

        /** @var \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource */
        $resource = $this->_orderFactory->create()->getResource();
        $connection = $resource->getConnection();

        $sql1 = $connection->select()
                           ->from($resource->getTable('newsletter_subscriber'), ['subscriber_email']);

        $sql = $connection->select()
                          ->from($resource->getTable('egoi_subscribers'), ['email'])
                          ->where('email NOT IN (?)', $sql1);

        $notInLocal = $connection->fetchCol($sql);

        foreach ($notInLocal as $email) {

            /** @var \Magento\Newsletter\Model\Subscriber $core */
            $core = $this->_coreSubscriber->create();

            $core->loadByEmail($email);

            if ($core->getStatus() != self::STATUS_SUBSCRIBED) {
                $core->setImportMode(true)
                     ->setStatus(self::STATUS_SUBSCRIBED)
                     ->save();
            }

        }

        if ($this->_scopeConfig->isSetFlag('egoi/info/merge')) {

            $egoi = $this->_egoi;

            $list = $this->_listsFactory->create()->getList();

            $limit = 1000;
            $end = false;
            do {
                $egoi->addData(['listID' => $list->getListnum(), 'subscriber' => 'all_subscribers', 'limit' => $limit])
                     ->setData('start', $start);

                file_put_contents($file, date('d') . '-' . date('H') . '-' . $start);

                try {
                    $subscribers = $egoi->getSubscriberData()->getData();
                } catch (\Exception $e) {
                    $this->_egoiHelper->getLogger('crit')->critical($e->getMessage());
                }

                if (isset($subscribers[0]['ERROR'])) {
                    break;
                }

                if (count($subscribers[0]['subscriber']) < $limit) {
                    $end = true;
                }

                foreach ($subscribers[0]['subscriber'] as $subscriberInfo) {

                    $info = array_change_key_case($subscriberInfo, CASE_LOWER);

                    if (!filter_var($info['email'], FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
                    $subscriber = $this->_coreSubscriber->create();
                    $subscriber->loadByEmail($info['email']);

                    if (!$subscriber->getId()) {
                        continue;
                    }
                    $subscriber->setInCallBack(true);
                    $subscriber->setImportMode(true);

                    $status = $info['status'];

                    if ($status != self::STATUS_SUBSCRIBED) {
                        $status = self::STATUS_UNSUBSCRIBED;
                    }

                    try {

                        if ($status == self::STATUS_SUBSCRIBED &&
                            $subscriber->getStatus() != self::STATUS_SUBSCRIBED) {

                            $subscriber->setStatus(self::STATUS_SUBSCRIBED)
                                       ->save();

                        } elseif ($subscriber->getStatus() != self::STATUS_UNSUBSCRIBED) {

                            $subscriber->setStatus(self::STATUS_UNSUBSCRIBED)
                                       ->save();

                        }

                    } catch (\Exception $e) {
                        $this->_egoiHelper->getLogger('warn')->critical($e->getMessage());
                    }
                }

                $start += $limit;

                $this->_egoiHelper->getLogger('subscriber')->info('cron running ' . $limit . '/' . $start);

            } while ($end === false);

        }

        if (is_file($file)) {
            unlink($file);
        }

        $this->_egoiHelper->getLogger('subscriber')->info('cron ended');

        $this->_egoiHelper->getLogger('subscriber')->info('cron started - core');

        if ($this->_scopeConfig->isSetFlag('egoi/info/previous')) {
            $this->importCoreNewsletterSubscribers();
        }

        $this->_egoiHelper->getLogger('subscriber')->info('cron ended - core');

        return $this;
    }

    /**
     * @param        $value
     * @param string $attribute
     * @param null   $billing
     *
     * @return bool
     */
    public function findCustomer($value, $attribute = 'entity_id', $billing = null)
    {

        $phoneField = $this->_scopeConfig->getValue('egoi/info/cellphone');
        $phoneField = str_replace(['addr_', 'ac_'], ['', ''], $phoneField);

        $customers = $this->_customerCollection
            ->create()
            ->addAttributeToSelect('firstname')
            ->addAttributeToSelect('lastname')
            ->addAttributeToSelect('store_id')
            ->addAttributeToSelect('dob')
            ->addAttributeToFilter($attribute, $value)
            ->joinAttribute('country_id', 'customer_address/country_id', 'default_billing', null, 'left');

        if ($phoneField) {
            $customers->joinAttribute($phoneField, 'customer_address/' . $phoneField, 'default_billing', null, 'left');
        }

        if ($billing && $phoneField != $billing && $attribute != $billing && 'country_id' != $billing) {
            $customers->joinAttribute($billing, 'customer_address/' . $billing, 'default_billing', null, 'left');
        }

        if ($customers->count() == 1) {
            $customer = $customers->getFirstItem();
            if (strlen($customer->getData($phoneField)) > 5) {
                $customer->setData(
                    'cellphone',
                    $this->getPrefixForCountry($customer->getCountryId()) . '-' . preg_replace(
                        '/\D/',
                        '',
                        $customer->getData($phoneField)
                    )
                );
            }

            return $customer;
        }

        return false;
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function isSubscribedByEmail($email)
    {

        $this->load($email, 'email');

        $status = $this->_egoi->getSubscriberStatusApi($email);

        if (!$this->getId() && $status['status'] == 'active') {
            $this->subscribe($email);

            return true;
        }

        return $status['status'] == 'active';

    }

    /**
     *
     */
    public function importCoreNewsletterSubscribers()
    {

        $news = $this->_coreSubscriberCollection->create()
                                                ->addFieldToFilter('subscriber_status', self::STATUS_SUBSCRIBED);

        /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
        foreach ($news as $subscriber) {

            $data = [];

            if ($this->subscriberExists($subscriber->getEmail())) {
                continue;
            }

            $data['email'] = $subscriber->getEmail();
            $data['status'] = self::STATUS_SUBSCRIBED;
            $data['customer_id'] = $subscriber->getCustomerId();

            try {
                $this->_subscriberFactory->create()->setData($data)->save();
            } catch (\Exception $e) {
                $this->_egoiHelper->getLogger('warn')->warn($e->getMessage());
            }
        }
    }

    /**
     * @param $email
     *
     * @return bool
     */
    public function subscriberExists($email)
    {

        $model = $this->_subscriberCollection->create()
                                             ->addFieldToFilter('email', $email);

        if ($model->count() != 1) {
            return false;
        }

        return $model->getFirstItem();
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function save()
    {

        if ($this->getInCallBack()) {
            return parent::save();
        }

        $extra = $this->_extraFactory->create()->getExtra();
        $list = $this->_listsFactory->create()->getList()->getData('listnum');

        $data = $this->getData();

        if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
            return $this;
        }

        $this->load($this->getEmail(), 'email');

        if (!$this->getOrigData() && $this->getId()) {
            $this->load($this->getId());
        }

        $data['listID'] = $list;
        $data['list'] = $list;
        $data['listnum'] = $list;

        $storeId = $this->getStoreId();

        if (!$storeId) {
            $storeId = $this->_storeManager->getDefaultStoreView()->getId();
            $data['store_id'] = $storeId;
        }

        $customer = $this->findCustomer($this->getEmail(), 'email');

        $data['birth_date'] = '';
        $data['first_name'] = '';
        $data['last_name'] = '';
        $data['cellphone'] = '';

        if (!isset($data['first_name']) && !$this->getOrigData('first_name')) {
            $data['first_name'] = 'Customer';
        }

        if ($customer) {
            $data['birth_date'] = substr($customer->getData('dob'), 0, 10);
            $data['first_name'] = $customer->getData('firstname');
            $data['last_name'] = $customer->getData('lastname');

            if ($customer->getData('cellphone')) {
                $data['cellphone'] = $customer->getData('cellphone');
            }

            foreach ($extra as $element) {

                if ($customer->getData($element->getData('attribute_code'))) {
                    $data[$element->getData('extra_code')] = $customer->getData(
                        $element->getData('attribute_code')
                    );
                    continue;
                }

                $billing = false;
                if (stripos($element->getData('attribute_code'), 'addr_') !== false) {
                    $attributeCode = substr($element->getData('attribute_code'), 5);
                    $billing = true;
                } else {
                    $attributeCode = $element->getData('attribute_code');
                }

                if ($billing) {
                    $customer = $this->findCustomer($customer->getId(), 'entity_id', $attributeCode);
                } else {
                    $customer = $this->_customerFactory->create()->load($customer->getId());
                }

                if ($customer->getData($attributeCode)) {
                    $data[$element->getData('extra_code')] = $customer->getData($attributeCode);
                    continue;
                }

                $data[$element->getData('extra_code')] = '';
            }

        } else {
            foreach ($extra as $element) {
                $data[$element->getData('extra_code')] = '';
            }
        }

        $this->addData($data);

        $extraStore = $this->_extraFactory->create()
                                          ->getExtra()
                                          ->addFieldToFilter('attribute_code', 'store_id');

        if ($extraStore->getSize() == 1) {
            $extraStoreView = $extraStore->getFirstItem();

            if (!isset($data[$extraStoreView->getData('extra_code')]) ||
                $data[$extraStoreView->getData('extra_code')] == ''
            ) {
                if ($this->getStoreId()) {
                    $data[$extraStoreView->getData('extra_code')] = $this->getStoreId();
                } else {
                    /** @var Mage_Sales_Model_Order $order */
                    $order = $this->_orderFactory->create()
                                                 ->getCollection()
                                                 ->addFieldToFilter('customer_email', $this->getEmail())
                                                 ->setPageSize(1)
                                                 ->getFirstItem();

                    if ($order->getId()) {
                        $data[$extraStoreView->getData('extra_code')] = $order->getStoreId();
                    }
                }

            }
        }

        $extraStoreCodeCollection = $this->_extraFactory->create()
                                                        ->getExtra()
                                                        ->addFieldToFilter('attribute_code', 'store_code');

        if ($extraStoreCodeCollection->getSize() == 1) {
            $extraStoreCode = $extraStoreCodeCollection->getFirstItem();
            $data[$extraStoreCode->getData('extra_code')] = $this->_storeManager->getStore()->getCode();
        }

        $extraStoreNameCollection = $this->_extraFactory->create()
                                                        ->getExtra()
                                                        ->addFieldToFilter('attribute_code', 'store_name');

        if ($extraStoreNameCollection->getSize() == 1) {
            $extraStoreName = $extraStoreNameCollection->getFirstItem();
            $data[$extraStoreName->getData('extra_code')] = $this->_storeManager->getStore()->getName();
        }

        $egoi = $this->_egoi;

        $egoi->addData($this->getData());
        $egoi->addData($data);
        $this->addData($data);

        foreach ($this->getData() as $key => $value) {
            if (!is_scalar($value)) {
                $this->unsetData($key);
            }
        }

        foreach ($egoi->getData() as $key => $value) {
            if (!is_scalar($value)) {
                $egoi->unsetData($key);
            }
        }

        if ($this->getData('inCron') === true) {
            return parent::save();
        }

        try {

            /** @var \Magento\Newsletter\Model\Subscriber $coreSubscriber */
            $coreSubscriber = $this->_coreSubscriber->create()->loadByEmail($this->getEmail());
            $status = 0;
            if ($coreSubscriber->getSubscriberStatus() == 1) {
                $status = 1;
            } elseif ($coreSubscriber->getSubscriberStatus() == 2) {
                $status = 4;
            } elseif ($coreSubscriber->getSubscriberStatus() == 4) {
                $status = 0;
            } elseif ($coreSubscriber->getSubscriberStatus() == 3) {
                $status = 2;
            }

            $egoi->setData('status', $status);
            if ($this->getId()) {
                if ($egoi->getData('uid')) {
                    $egoi->setData('subscriber', $egoi->getData('uid'));
                }
                $result = $egoi->editSubscriber();
            } else {
                if (!$egoi->getEmail()) {
                    return parent::save();
                }
                $result = $egoi->addSubscriber();
                if (isset($result['uid'])) {
                    $this->setData('uid', $result->getData('uid'));
                }
            }
        } catch (\Exception $e) {
            $this->_egoiHelper->getLogger('warn')->warn($e->getMessage());
        }

        return parent::save();
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function delete()
    {

        if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {

            return parent::delete();
        }

        $model = $this->_egoi;

        $data = [];
        $data['listID'] = $this->getList();
        $data['subscriber'] = $this->getUid();

        if (!$this->getData('inCron')) {
            try {
                $model->setData($data)->removeSubscriber();
            } catch (\Exception $e) {
                $this->_egoiHelper->getLogger('warn')->critical($e->getMessage());
            }
        }

        /** @var \Magento\Newsletter\Model\Subscriber $core */
        $core = $this->_coreSubscriber->create()->loadByEmail($this->getEmail());
        if ($core->getId()) {
            $core->delete();
        }

        return parent::delete();
    }

    /**
     * @return array
     */
    public static function getPhonePrefixs()
    {

        $phones = self::phonePrefixsList();

        $return = [];
        $return[''] = __('-- Please Choose --');
        foreach ($phones as $value) {
            $return[$value[2]] = ucwords(strtolower($value[0])) . ' (+' . $value[2] . ')';
        }

        asort($return);

        return $return;
    }

    /**
     * @param $countryCode
     *
     * @return string
     */
    public function getPrefixForCountry($countryCode)
    {

        $phones = self::phonePrefixsList();
        foreach ($phones as $phone) {

            if ($phone[1] == $countryCode) {
                return $phone[2];
            }
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isSubscribed()
    {

        $result = $this->_egoi->getSubscriberStatusApi($this->getEmail());

        $this->setData('status', $result['status'] == 'active' ? 1 : 0)->save();

        return $result == 'active';

    }

    /**
     * @param       $email
     * @param array $data
     *
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function subscribe($email, $storeId = null, $create = true)
    {

        $this->load($email, 'email');

        if (!$this->getEmail()) {
            $this->setData('email', $email);
        }

        if ($storeId) {
            $this->setData('store_id', $storeId);
        }

        $result = $this->_egoi->getSubscriberStatusApi($email);

        if ($result['status'] == false) {
            $egoiAdded = $this->_egoi->createContactApi($this->buildExtraFields($this));
            if ($egoiAdded) {
                $this->setData('uid', $egoiAdded);
            }
        } elseif ($result != 'active' && $create) {
            if (isset($result['uid'])) {
                $this->_egoi->updateContactStatusApi($result['uid'], 'active');
            }
        }

        if ($result['uid']) {
            $this->setData('uid', $result['uid']);
        }

        if ($this->getData('uid')) {
            $this->_egoiHelper->setCookie($this->getData('uid'));
        }

        return $this->setData('status', self::STATUS_SUBSCRIBED)->save();

    }

    /**
     * @param $email
     *
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function unsubscribe($email)
    {

        $this->load($email, 'email');

        if (!$this->getEmail()) {
            $this->setData('email', $email);
        }

        $status = $this->_egoi->getSubscriberStatusApi($email);

        if ($status['status'] || $this->getStatus() != self::STATUS_UNSUBSCRIBED) {

            if ($status) {
                $this->_egoi->unsubscribeApi($this, $status);
            }

            $this->_coreSubscriber->create()
                                  ->loadByEmail($email)
                                  ->setImportMode(true)
                                  ->setStatus(self::STATUS_UNSUBSCRIBED)
                                  ->save();

            if ($this->getData('uid')) {
                $this->_egoiHelper->unsetCookie();
            }

            return $this->setData('status', self::STATUS_UNSUBSCRIBED)->save();
        }

        return $this;
    }

    /**
     * @return array
     */
    public static function phonePrefixsList()
    {

        return [
            ['CANADA', 'CA', '1'],
            ['PUERTO RICO', 'PR', '1'],
            ['UNITED STATES', 'US', '1'],
            ['ARMENIA', 'AM', '7'],
            ['KAZAKHSTAN', 'KZ', '7'],
            ['RUSSIAN FEDERATION', 'RU', '7'],
            ['EGYPT', 'EG', '20'],
            ['SOUTH AFRICA (Zuid Afrika)', 'ZA', '27'],
            ['GREECE', 'GR', '30'],
            ['NETHERLANDS', 'NL', '31'],
            ['BELGIUM', 'BE', '32'],
            ['FRANCE', 'FR', '33'],
            ['SPAIN (España)', 'ES', '34'],
            ['HUNGARY', 'HU', '36'],
            ['ITALY', 'IT', '39'],
            ['ROMANIA', 'RO', '40'],
            ['SWITZERLAND (Confederation of Helvetia)', 'CH', '41'],
            ['AUSTRIA', 'AT', '43'],
            ['GREAT BRITAIN (United Kingdom)', 'GB', '44'],
            ['UNITED KINGDOM', 'GB', '44'],
            ['DENMARK', 'DK', '45'],
            ['SWEDEN', 'SE', '46'],
            ['NORWAY', 'NO', '47'],
            ['POLAND', 'PL', '48'],
            ['GERMANY (Deutschland)', 'DE', '49'],
            ['PERU', 'PE', '51'],
            ['MEXICO', 'MX', '52'],
            ['CUBA', 'CU', '53'],
            ['ARGENTINA', 'AR', '54'],
            ['BRAZIL', 'BR', '55'],
            ['CHILE', 'CL', '56'],
            ['COLOMBIA', 'CO', '57'],
            ['VENEZUELA', 'VE', '58'],
            ['MALAYSIA', 'MY', '60'],
            ['AUSTRALIA', 'AU', '61'],
            ['INDONESIA', 'ID', '62'],
            ['PHILIPPINES', 'PH', '63'],
            ['NEW ZEALAND', 'NZ', '64'],
            ['SINGAPORE', 'SG', '65'],
            ['THAILAND', 'TH', '66'],
            ['JAPAN', 'JP', '81'],
            ['KOREA (Republic of [South] Korea)', 'KR', '82'],
            ['VIET NAM', 'VN', '84'],
            ['CHINA', 'CN', '86'],
            ['TURKEY', 'TR', '90'],
            ['INDIA', 'IN', '91'],
            ['PAKISTAN', 'PK', '92'],
            ['AFGHANISTAN', 'AF', '93'],
            ['SRI LANKA (formerly Ceylon)', 'LK', '94'],
            ['MYANMAR (formerly Burma)', 'MM', '95'],
            ['IRAN (Islamic Republic of Iran)', 'IR', '98'],
            ['MOROCCO', 'MA', '212'],
            ['ALGERIA (El Djazaïr)', 'DZ', '213'],
            ['TUNISIA', 'TN', '216'],
            ['LIBYA (Libyan Arab Jamahirya)', 'LY', '218'],
            ['GAMBIA, THE', 'GM', '220'],
            ['SENEGAL', 'SN', '221'],
            ['MAURITANIA', 'MR', '222'],
            ['MALI', 'ML', '223'],
            ['GUINEA', 'GN', '224'],
            ['CÔTE D\'IVOIRE (Ivory Coast)', 'CI', '225'],
            ['BURKINA FASO', 'BF', '226'],
            ['NIGER', 'NE', '227'],
            ['TOGO', 'TG', '228'],
            ['BENIN', 'BJ', '229'],
            ['MAURITIUS', 'MU', '230'],
            ['LIBERIA', 'LR', '231'],
            ['SIERRA LEONE', 'SL', '232'],
            ['GHANA', 'GH', '233'],
            ['NIGERIA', 'NG', '234'],
            ['CHAD (Tchad)', 'TD', '235'],
            ['CENTRAL AFRICAN REPUBLIC', 'CF', '236'],
            ['CAMEROON', 'CM', '237'],
            ['CAPE VERDE', 'CV', '238'],
            ['SAO TOME AND PRINCIPE', 'ST', '239'],
            ['EQUATORIAL GUINEA', 'GQ', '240'],
            ['GABON', 'GA', '241'],
            ['CONGO, REPUBLIC OF', 'CG', '242'],
            ['CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)', 'CD', '243'],
            ['ANGOLA', 'AO', '244'],
            ['GUINEA-BISSAU', 'GW', '245'],
            ['ASCENSION ISLAND', '', '247'],
            ['SEYCHELLES', 'SC', '248'],
            ['SUDAN', 'SD', '249'],
            ['RWANDA', 'RW', '250'],
            ['ETHIOPIA', 'ET', '251'],
            ['SOMALIA', 'SO', '252'],
            ['DJIBOUTI', 'DJ', '253'],
            ['KENYA', 'KE', '254'],
            ['TANZANIA', 'TZ', '255'],
            ['UGANDA', 'UG', '256'],
            ['BURUNDI', 'BI', '257'],
            ['MOZAMBIQUE (Moçambique)', 'MZ', '258'],
            ['ZAMBIA (formerly Northern Rhodesia)', 'ZM', '260'],
            ['MADAGASCAR', 'MG', '261'],
            ['RÉUNION', 'RE', '262'],
            ['ZIMBABWE', 'ZW', '263'],
            ['NAMIBIA', 'NA', '264'],
            ['MALAWI', 'MW', '265'],
            ['LESOTHO', 'LS', '266'],
            ['BOTSWANA', 'BW', '267'],
            ['SWAZILAND', 'SZ', '268'],
            ['COMOROS', 'KM', '269'],
            ['MAYOTTE', 'YT', '269'],
            ['SAINT HELENA', 'SH', '290'],
            ['ERITREA', 'ER', '291'],
            ['ARUBA', 'AW', '297'],
            ['FAEROE ISLANDS', 'FO', '298'],
            ['GREENLAND', 'GL', '299'],
            ['GIBRALTAR', 'GI', '350'],
            ['PORTUGAL', 'PT', '351'],
            ['LUXEMBOURG', 'LU', '352'],
            ['IRELAND', 'IE', '353'],
            ['ICELAND', 'IS', '354'],
            ['ALBANIA', 'AL', '355'],
            ['MALTA', 'MT', '356'],
            ['CYPRUS', 'CY', '357'],
            ['FINLAND', 'FI', '358'],
            ['BULGARIA', 'BG', '359'],
            ['LITHUANIA', 'LT', '370'],
            ['LATVIA', 'LV', '371'],
            ['ESTONIA', 'EE', '372'],
            ['MOLDOVA', 'MD', '373'],
            ['BELARUS', 'BY', '375'],
            ['ANDORRA', 'AD', '376'],
            ['MONACO', 'MC', '377'],
            ['SAN MARINO (Republic of)', 'SM', '378'],
            ['VATICAN CITY (Holy See)', 'VA', '379'],
            ['UKRAINE', 'UA', '380'],
            ['SERBIA (Republic of Serbia)', 'RS', '381'],
            ['MONTENEGRO', 'ME', '382'],
            ['CROATIA (Hrvatska)', 'HR', '385'],
            ['SLOVENIA', 'SI', '386'],
            ['BOSNIA AND HERZEGOVINA', 'BA', '387'],
            ['MACEDONIA (Former Yugoslav Republic of Macedonia)', 'MK', '389'],
            ['CZECH REPUBLIC', 'CZ', '420'],
            ['SLOVAKIA (Slovak Republic)', 'SK', '421'],
            ['LIECHTENSTEIN (Fürstentum Liechtenstein)', 'LI', '423'],
            ['FALKLAND ISLANDS (MALVINAS)', 'FK', '500'],
            ['BELIZE', 'BZ', '501'],
            ['GUATEMALA', 'GT', '502'],
            ['EL SALVADOR', 'SV', '503'],
            ['HONDURAS', 'HN', '504'],
            ['NICARAGUA', 'NI', '505'],
            ['COSTA RICA', 'CR', '506'],
            ['PANAMA', 'PA', '507'],
            ['SAINT PIERRE AND MIQUELON', 'PM', '508'],
            ['HAITI', 'HT', '509'],
            ['GUADELOUPE', 'GP', '590'],
            ['BOLIVIA', 'BO', '591'],
            ['GUYANA', 'GY', '592'],
            ['ECUADOR', 'EC', '593'],
            ['FRENCH GUIANA', 'GF', '594'],
            ['PARAGUAY', 'PY', '595'],
            ['MARTINIQUE', 'MQ', '596'],
            ['SURINAME', 'SR', '597'],
            ['URUGUAY', 'UY', '598'],
            ['BONAIRE, ST. EUSTATIUS, AND SABA', 'BQ', '599'],
            ['CURAÃ‡AO', 'CW', '599'],
            ['NETHERLANDS ANTILLES (obsolete)', 'AN', '599'],
            ['SINT MAARTEN', 'SX', '599'],
            ['TIMOR-LESTE (formerly East Timor)', 'TL', '670'],
            ['BRUNEI DARUSSALAM', 'BN', '673'],
            ['NAURU', 'NR', '674'],
            ['PAPUA NEW GUINEA', 'PG', '675'],
            ['TONGA', 'TO', '676'],
            ['SOLOMON ISLANDS', 'SB', '677'],
            ['VANUATU', 'VU', '678'],
            ['FIJI', 'FJ', '679'],
            ['PALAU', 'PW', '680'],
            ['WALLIS AND FUTUNA', 'WF', '681'],
            ['COOK ISLANDS', 'CK', '682'],
            ['NIUE', 'NU', '683'],
            ['SAMOA (formerly Western Samoa)', 'WS', '685'],
            ['KIRIBATI', 'KI', '686'],
            ['NEW CALEDONIA', 'NC', '687'],
            ['TUVALU', 'TV', '688'],
            ['FRENCH POLYNESIA', 'PF', '689'],
            ['TOKELAU', 'TK', '690'],
            ['MICRONESIA (Federated States of Micronesia)', 'FM', '691'],
            ['MARSHALL ISLANDS', 'MH', '692'],
            ['KOREA (Democratic Peoples Republic of [North] Korea)', 'KP', '850'],
            ['HONG KONG (Special Administrative Region of China)', 'HK', '852'],
            ['MACAO (Special Administrative Region of China)', 'MO', '853'],
            ['CAMBODIA', 'KH', '855'],
            ['LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'LA', '856'],
            ['BANGLADESH', 'BD', '880'],
            ['TAIWAN (Chinese Taipei for IOC)', 'TW', '886'],
            ['MALDIVES', 'MV', '960'],
            ['LEBANON', 'LB', '961'],
            ['JORDAN (Hashemite Kingdom of Jordan)', 'JO', '962'],
            ['SYRIAN ARAB REPUBLIC', 'SY', '963'],
            ['IRAQ', 'IQ', '964'],
            ['KUWAIT', 'KW', '965'],
            ['SAUDI ARABIA (Kingdom of Saudi Arabia)', 'SA', '966'],
            ['YEMEN (Yemen Arab Republic)', 'YE', '967'],
            ['OMAN', 'OM', '968'],
            ['PALESTINIAN TERRITORIES', 'PS', '970'],
            ['UNITED ARAB EMIRATES', 'AE', '971'],
            ['ISRAEL', 'IL', '972'],
            ['BAHRAIN', 'BH', '973'],
            ['QATAR', 'QA', '974'],
            ['BHUTAN', 'BT', '975'],
            ['MONGOLIA', 'MN', '976'],
            ['NEPAL', 'NP', '977'],
            ['TAJIKISTAN', 'TJ', '992'],
            ['TURKMENISTAN', 'TM', '993'],
            ['AZERBAIJAN', 'AZ', '994'],
            ['KYRGYZSTAN', 'KG', '996'],
            ['UZBEKISTAN', 'UZ', '998'],
            ['BAHAMAS', 'BS', '1242'],
            ['BARBADOS', 'BB', '1246'],
            ['ANGUILLA', 'AI', '1264'],
            ['ANTIGUA AND BARBUDA', 'AG', '1268'],
            ['VIRGIN ISLANDS, BRITISH', 'VG', '1284'],
            ['VIRGIN ISLANDS, U.S.', 'VI', '1340'],
            ['CAYMAN ISLANDS', 'KY', '1345'],
            ['BERMUDA', 'BM', '1441'],
            ['GRENADA', 'GD', '1473'],
            ['TURKS AND CAICOS ISLANDS', 'TC', '1649'],
            ['MONTSERRAT', 'MS', '1664'],
            ['NORTHERN MARIANA ISLANDS', 'MP', '1670'],
            ['GUAM', 'GU', '1671'],
            ['AMERICAN SAMOA', 'AS', '1684'],
            ['SAINT LUCIA', 'LC', '1758'],
            ['DOMINICA', 'DM', '1767'],
            ['SAINT VINCENT AND THE GRENADINES', 'VC', '1784'],
            ['DOMINICAN REPUBLIC', 'DO', '1809'],
            ['TRINIDAD AND TOBAGO', 'TT', '1868'],
            ['SAINT KITTS AND NEVIS', 'KN', '1869'],
            ['JAMAICA', 'JM', '1-876'],
        ];
    }

    /**
     * @param $subscriber
     *
     * @return array
     */
    public function buildExtraFields($subscriber = null)
    {

        if (!$subscriber) {
            $subscriber = $this;
        }

        $extra = $this->_extraFactory->create()->getExtra();

        $customer = $this->findCustomer($subscriber->getEmail(), 'email');

        $data['base']['email'] = $subscriber->getEmail();
        $data['base']['birth_date'] = '';
        $data['base']['first_name'] = '';
        $data['base']['last_name'] = '';
        $data['base']['cellphone'] = '';

        if ($customer) {
            $data['base']['birth_date'] = substr($customer->getData('dob'), 0, 10);
            $data['base']['first_name'] = $customer->getData('firstname');
            $data['base']['last_name'] = $customer->getData('lastname');

            if ($customer->getData('cellphone')) {
                $data['base']['cellphone'] = $customer->getData('cellphone');
            }

            foreach ($extra as $element) {

                $remoteFieldId = str_replace('extra_', '', $element->getData('extra_code'));

                if ($customer->getData($element->getData('attribute_code'))) {

                    $data['extra'][] = [
                        'field_id' => $remoteFieldId,
                        'value'    => $customer->getData($element->getData('attribute_code')),
                    ];

                    continue;
                }

                $billing = false;
                if (stripos($element->getData('attribute_code'), 'addr_') !== false) {
                    $attributeCode = substr($element->getData('attribute_code'), 5);
                    $billing = true;
                } else {
                    $attributeCode = $element->getData('attribute_code');
                }

                if ($billing) {
                    $customer = $this->findCustomer($customer->getId(), 'entity_id', $attributeCode);
                } else {
                    $customer = $this->_customerFactory->create()->load($customer->getId());
                }

                if ($customer->getData($attributeCode)) {

                    $data['extra'][] = [
                        'field_id' => $remoteFieldId,
                        'value'    => $customer->getData($attributeCode),
                    ];

                }

            }

        }

        $extraStore = $this->_extraFactory->create()->getExtra()
                                          ->addFieldToFilter('attribute_code', 'store_id');

        if ($extraStore->count() == 1) {
            $extraStoreView = $extraStore->getFirstItem();

            $storeViewFieldId = str_replace('extra_', '', $extraStoreView->getData('extra_code'));

            if (!isset($data[$storeViewFieldId]) || $data[$storeViewFieldId] == '') {

                if ($subscriber->getStoreId()) {
                    $data['extra'][] = [
                        'field_id' => $storeViewFieldId,
                        'value'    => $subscriber->getStoreId(),
                    ];
                } else {

                    /** @var Mage_Sales_Model_Order $order */
                    $order = $this->_orderFactory->create()
                                                 ->getCollection()
                                                 ->addFieldToFilter('customer_email', $subscriber->getEmail())
                                                 ->setPageSize(1)
                                                 ->getFirstItem();

                    if ($order->getId() && $order->getStoreId()) {

                        $data['extra'][] = [
                            'field_id' => $storeViewFieldId,
                            'value'    => $order->getStoreId(),
                        ];

                    }
                }

            }
        }

        $extraStoreCodeCollection = $this->_extraFactory->create()
                                                        ->getExtra()
                                                        ->addFieldToFilter('attribute_code', 'store_code');

        if ($extraStoreCodeCollection->getSize() == 1) {
            $extraStoreCode = $extraStoreCodeCollection->getFirstItem();
            $storeViewFieldId = str_replace('extra_', '', $extraStoreCode->getData('extra_code'));

            $data['extra'][] = [
                'field_id' => $storeViewFieldId,
                'value'    => $this->_storeManager->getStore()->getCode(),
            ];

        }

        $extraStoreNameCollection = $this->_extraFactory->create()
                                                        ->getExtra()
                                                        ->addFieldToFilter('attribute_code', 'store_name');

        if ($extraStoreNameCollection->getSize() == 1) {
            $extraStoreName = $extraStoreNameCollection->getFirstItem();
            $storeViewFieldId = str_replace('extra_', '', $extraStoreName->getData('extra_code'));

            $data['extra'][] = [
                'field_id' => $storeViewFieldId,
                'value'    => $this->_storeManager->getStore()->getName(),
            ];

        }

        return $data;
    }
}
