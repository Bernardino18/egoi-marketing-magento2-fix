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
 * Class Egoi
 *
 * @package Egoi\Marketing\Model
 */
class Egoi extends \Magento\Framework\DataObject
{

    /**
     *
     */
    const PLUGIN_KEY = 'e419a126e087bed65ad7fe8342f2f493';

    /**
     *
     */
    const ECOMMERCE_URL = 'https://api.egoiapp.com/';

    /**
     *
     */
    const API_URL_SOAP = 'http://api.e-goi.com/v2/soap.php?wsdl';

    /**
     *
     */
    const MOBILE_NUMBER_VALIDATION_URL = 'https://www51.e-goi.com/api/public/sms/validatePhone/';

    /**
     *
     */
    const MOBILE_NUMBER_SEND_URL = 'https://www51.e-goi.com/api/public/sms/send';

    /**
     * @var \SoapClient
     */
    protected $soapClient;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var ListsFactory
     */
    protected $_listsFactory;

    /**
     * @var AccountFactory
     */
    protected $_accountFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_adminSession;

    /**
     * @var SubscriberFactory
     */
    protected $_susbcriberFactory;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_coreSubscriberFactory;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $_coreCollectionFactory;

    /**
     * @var ListsFactory
     */
    protected $_extraFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var \Zend_Rest_Client
     */
    protected $rpc;

    /**
     * @var \Magento\Cron\Model\ScheduleFactory
     */
    protected $_cron;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Framework\Url
     */
    protected $_url;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $_egoiHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Framework\App\ReinitableConfig
     */
    protected $_reinitableConfig;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_config;

    /**
     * @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
     */
    protected $configurableType;

    /**
     * Egoi constructor.
     *
     * @param \Magento\ConfigurableProduct\Model\Product\Type\Configurable         $configurableType
     * @param \Magento\Framework\App\ReinitableConfig                              $reinitableConfig
     * @param \Magento\Config\Model\ResourceModel\Config                           $config
     * @param \Magento\Catalog\Helper\Image                                        $imageHelper
     * @param \Magento\Catalog\Model\ProductFactory                                $productFactory
     * @param \Magento\Catalog\Model\CategoryFactory                               $categoryFactory
     * @param \Egoi\Marketing\Helper\Data                                          $egoiHelper
     * @param \Magento\Sales\Model\OrderFactory                                    $orderFactory
     * @param \Magento\Framework\Url                                               $urlHelper
     * @param \Magento\Cron\Model\ScheduleFactory                                  $scheduleFactory
     * @param \Magento\Newsletter\Model\SubscriberFactory                          $coreSubscriberFactory
     * @param \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $coreCollectionFactory
     * @param \Magento\Framework\Registry                                          $coreRegistry
     * @param \Magento\Backend\Model\Auth\Session                                  $authSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                   $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface                           $storeManager
     * @param \Magento\Customer\Model\CustomerFactory                              $customerFactory
     * @param ListsFactory                                                         $listsFactory
     * @param ExtraFactory                                                         $extraFactory
     * @param AccountFactory                                                       $accountFactory
     * @param \Magento\Framework\Filesystem                                        $filesystem
     * @param SubscriberFactory                                                    $subscribersFactory
     */
    public function __construct(
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable         $configurableType,
        \Magento\Framework\App\ReinitableConfig                              $reinitableConfig,
        \Magento\Config\Model\ResourceModel\Config                           $config,
        \Magento\Catalog\Helper\Image                                        $imageHelper,
        \Magento\Catalog\Model\ProductFactory                                $productFactory,
        \Magento\Catalog\Model\CategoryFactory                               $categoryFactory,
        \Egoi\Marketing\Helper\Data                                          $egoiHelper,
        \Magento\Sales\Model\OrderFactory                                    $orderFactory,
        \Magento\Framework\Url                                               $urlHelper,
        \Magento\Cron\Model\ScheduleFactory                                  $scheduleFactory,
        \Magento\Newsletter\Model\SubscriberFactory                          $coreSubscriberFactory,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $coreCollectionFactory,
        \Magento\Framework\Registry                                          $coreRegistry,
        \Magento\Backend\Model\Auth\Session                                  $authSession,
        \Magento\Framework\App\Config\ScopeConfigInterface                   $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface                           $storeManager,
        \Magento\Customer\Model\CustomerFactory                              $customerFactory,
        ListsFactory                                                         $listsFactory,
        ExtraFactory                                                         $extraFactory,
        AccountFactory                                                       $accountFactory,
        \Magento\Framework\Filesystem                                        $filesystem,
        SubscriberFactory                                                    $subscribersFactory
    )
    {

        $this->configurableType = $configurableType;
        $this->imageHelper = $imageHelper;
        $this->productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->_egoiHelper = $egoiHelper;
        $this->_orderFactory = $orderFactory;
        $this->_cron = $scheduleFactory;
        $this->_registry = $coreRegistry;
        $this->_accountFactory = $accountFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_listsFactory = $listsFactory;
        $this->_extraFactory = $extraFactory;
        $this->_customerFactory = $customerFactory;
        $this->_susbcriberFactory = $subscribersFactory;
        $this->_adminSession = $authSession;
        $this->_coreSubscriberFactory = $coreSubscriberFactory;
        $this->_coreCollectionFactory = $coreCollectionFactory;
        $this->_fileSystem = $filesystem;
        $this->_url = $urlHelper;
        $this->_reinitableConfig = $reinitableConfig;
        $this->_config = $config;

        parent::__construct();

        ini_set('default_socket_timeout', 20);

        $this->soapClient = new \SoapClient(
            self::API_URL_SOAP,
            [
                "user_agent"         => "Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20180203211507 Firefox/56.0",
                'trace'              => true,
                "connection_timeout" => 20,
            ]
        );

    }

    /**
     * @param $number
     * @param $message
     * @param $storeId
     *
     * @return bool|string
     */
    public function send($number, $message, $storeId)
    {

        if (!$number) {
            return false;
        }

        $method = $this->_scopeConfig->getValue(
            'egoi/info/method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($method == 'campaign') {

            $this->setData('subject', 'Sent from Magento');
            $this->setData(
                'fromID',
                $this->_scopeConfig->getValue(
                    'egoi/info/sender',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                )
            );
            $this->setData('listID', $this->_listsFactory->create()->getList()->getData('listnum'));
            $this->setData('message', $message);
            $this->setData('cellphone', $number);

            $this->processServiceResult($this->soapClient->sendSMS($this->getDataKey()));

            if ($this->getData('id')) {
                return true;
            } else {
                return false;
            }

        } else {

            $url = self::MOBILE_NUMBER_SEND_URL;

            $data = [
                "apikey"     => $this->_scopeConfig->getValue(
                    'egoi/info/api_key',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                ),
                "mobile"     => $number,
                "senderHash" => $this->_scopeConfig->getValue(
                    'egoi/info/sender',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                ),
                "message"    => $message,
            ];

            $data = \Zend_Json::encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = \Zend_Json::decode($output);

            $error = false;
            if (is_array($result) && is_array($result['errors'])) {
                $result = implode(' ', $result['errors']);
                $error = true;
            }

            return ($http_status == 200 && !$error) ? true : '. Server Response: ' . $result;
        }
    }

    /**
     * @param $document
     *
     * @return bool
     */
    public function getPhone($document)
    {

        if (!is_object($document) && stripos($document, '-') === false) {
            return false;
        }

        if (!is_object($document) && stripos($document, '-')) {
            return $this->validateNumber($document);
        }

        if ($document instanceof \Magento\Sales\Model\Order) {
            $billing = $document->getBillingAddress();
        } elseif (is_object($document->getOrder())) {
            $billing = $document->getOrder()->getBillingAddress();
        }

        if (!isset($billing)) {
            return false;
        }

        $prefix = $this->_susbcriberFactory->create()->getPrefixForCountry($billing->getCountryId());

        $cellphoneField = str_replace(['addr_', 'ac_'], ['', ''], $this->_scopeConfig->getValue('egoi/info/cellphone'));
        $number = preg_replace('/\D/', '', $billing->getData($cellphoneField));
        $number = ltrim($number, $prefix);
        $number = ltrim($number, 0);

        return $this->validateNumber($prefix . '-' . $number);
    }

    /**
     * @param $number
     *
     * @return bool
     */
    public function validateNumber($number)
    {

        if (!$number) {
            return false;
        }

        try {

            $url = self::MOBILE_NUMBER_VALIDATION_URL . $number;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);

            $final = \Zend_Json::decode($result);

            if (array_key_exists('errorCode', $final)) {
                return false;
            }
        } catch (\Exception $e) {
            $this->_egoiHelper->getLogger('autoresponder')->critical('Invalid Phone:' . $number . $e->getMessage());

            return false;
        }

        return $number;
    }

    /**
     * @return bool
     */
    public function validateEgoiEnvironment()
    {

        $auth = $this->_adminSession->getUser()->getData('egoiAuth');

        if ($auth === true) {
            return true;
        }

        $info = $this->getUserData()->getData();

        if (!isset($info[0]) || !isset($info[0]['user_id']) || (int) $info[0]['user_id'] == 0) {
            return false;
        }

        $account = $this->_accountFactory->create()->getAccount();

        if ((int) $account->getData('cliente_id') == 0) {

            $n = $this->getAccountDetails()->getData();
            $account->addData($n[0])->save();

            $account = $this->_accountFactory->create()->getAccount();

            if ((int) $account->getData('cliente_id') == 0) {
                return false;
            }
        }

        $this->_adminSession->getUser()->setData('egoiAuth', true);

        return true;
    }

    /**
     * @param $data
     *
     * @return bool|Egoi
     */
    public function formatFields($data)
    {

        if (!is_array($data)) {
            $data = ['RESULT' => $data];
        }

        if (count($data) == 1 && isset($data['ERROR'])) {
            $this->_egoiHelper->getLogger()->debug(serialize($data));
            $data = [0 => $data];
            $this->setData($data);

            return false;
        }

        if (!array_key_exists(0, $data)) {
            $data = [0 => $data];
        }

        foreach ($data as $key => $value) {
            $data[$key] = array_change_key_case($value, CASE_LOWER);
        }

        $this->setData($data);

        return $this;
    }

    /**
     * @param $data
     *
     * @return bool|Egoi
     */
    public function formatSingleField($data)
    {

        if (!is_array($data)) {
            $data = ['RESULT' => $data];
        }

        if (count($data) == 1 && isset($data['ERROR'])) {
            $this->_egoiHelper->getLogger()->debug(serialize($data));
            $data = [0 => $data];
            $this->setData($data);

            return false;
        }

        $data = array_change_key_case($data['subscriber'], CASE_LOWER);

        $this->setData($data);

        return $this;
    }

    /**
     *
     * @return bool
     */
    public function addSubscriberBulkDaily()
    {

        return $this->addSubscriberBulk(false, true);
    }

    /**
     * @return bool
     */
    public function exportBulk()
    {

        return $this->addSubscriberBulk(true);
    }

    /**
     * @param bool $generate
     *
     * @param bool $day
     *
     * @return bool
     */
    public function addSubscriberBulk($generate = false, $day = false)
    {

        $tmpDir = $this->_fileSystem->getDirectoryWrite(DirectoryList::TMP);

        if (!is_dir($tmpDir->getAbsolutePath())) {
            $tmpDir->create($tmpDir->getAbsolutePath());
        }

        $file = $tmpDir->getAbsolutePath() . 'egoi.txt';
        if (!is_file($file)) {
            file_put_contents($file, '0');
        }

        $lastSync = (int) file_get_contents($file);

        $meta = $this->_coreCollectionFactory->create()
                                             ->addFieldToFilter('subscriber_status', 1)
                                             ->addFieldToFilter('subscriber_id', ['gt' => $lastSync]);

        if ($day) {
            $meta->addFieldToFilter('egoi_udpated_at', ['gt' => new \Zend_Db_Expr('DATE_SUB(NOW(), INTERVAL 1 DAY)')]);
        }

        if ($meta->getSize() == 0) {
            return true;
        }

        $list = $this->_listsFactory->create()->getList(true);
        $extra = $this->_extraFactory->create()->getExtra();

        $processNumber = 200;
        $i = 0;

        while ($i * $processNumber <= $meta->getSize()) {

            $i++;

            $core = $this->_coreCollectionFactory->create()
                                                 ->addFieldToFilter('subscriber_status', 1)
                                                 ->addFieldToFilter('subscriber_id', ['gt' => $lastSync])
                                                 ->setPageSize($processNumber)
                                                 ->setCurPage($i);

            if ($day) {
                $core->addFieldToFilter(
                    'egoi_udpated_at',
                    ['gt' => new \Zend_Db_Expr('DATE_SUB(NOW(), INTERVAL 1 DAY)')]
                );
            }

            $subscribers = [];
            $indexArray = [];
            $subI = 0;

            /** @var \Magento\Newsletter\Model\Subscriber $subscriber */
            foreach ($core as $subscriber) {
                $subI++;

                $data = [];

                if (!filter_var($subscriber->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                $data['email'] = $subscriber->getEmail();
                $indexArray[] = 'email';

                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $subscriber->delete();
                    continue;
                }

                $fidelitas = $this->_susbcriberFactory->create()->load($subscriber->getEmail(), 'email');
                $customer = $fidelitas->findCustomer($subscriber->getEmail(), 'email');

                $data['birth_date'] = '';
                $indexArray[] = 'birth_date';
                $data['first_name'] = '';
                $indexArray[] = 'first_name';
                $data['last_name'] = '';
                $indexArray[] = 'last_name';
                $data['cellphone'] = '';
                $indexArray[] = 'cellphone';

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
                            $indexArray[] = $element->getData('extra_code');
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
                            $indexArray[] = $element->getData('extra_code');
                            continue;
                        }

                        $data[$element->getData('extra_code')] = '';
                    }

                } else {
                    foreach ($extra as $element) {
                        $data[$element->getData('extra_code')] = '';
                    }
                }

                $extraStore = $this->_extraFactory->create()->getExtra()
                                                  ->addFieldToFilter('attribute_code', 'store_id');

                if ($extraStore->count() == 1) {
                    $extraStoreView = $extraStore->getFirstItem();

                    if (!isset($data[$extraStoreView->getData('extra_code')]) ||
                        $data[$extraStoreView->getData('extra_code')] == ''
                    ) {

                        if ($subscriber->getStoreId()) {
                            $data[$extraStoreView->getData('extra_code')] = $subscriber->getStoreId();
                        } else {

                            /** @var Mage_Sales_Model_Order $order */
                            $order = $this->_orderFactory->create()
                                                         ->getCollection()
                                                         ->addFieldToFilter('customer_email', $subscriber->getEmail())
                                                         ->setPageSize(1)
                                                         ->getFirstItem();

                            if ($order->getId() && $order->getStoreId()) {
                                $data[$extraStoreView->getData('extra_code')] = $order->getStoreId();
                            }
                        }

                    }
                }

                $data['status'] = 1;
                $indexArray[] = 'status';
                if ($subI == 1 && $lastSync == 0) {
                    $subscribers[] = $indexArray;
                }

                $lastSync = $subscriber->getId();

                $subscribers[] = $data;
            }

            if ($generate === true) {

                if ($lastSync > 0) {
                    unset($subscribers[0]);
                }

                $fileExport = $tmpDir->getAbsolutePath('egoi_export.csv');
                $fp = fopen($fileExport, 'a');

                $i = 0;
                foreach ($subscribers as $fields) {

                    if ($i == 0) {
                        fputcsv($fp, array_keys($fields), ';');
                    }

                    fputcsv($fp, $fields, ';');

                    $i++;
                }

                fclose($fp);

                if (count($subscribers) == 200) {

                    $cron = $this->_cron->create();
                    $data['status'] = 'pending';
                    $data['job_code'] = 'egoi_export_bulk';
                    $data['scheduled_at'] = new \Zend_Db_Expr('NOW()');
                    $data['created_at'] = new \Zend_Db_Expr('NOW()');
                    $cron->setData($data)->save();

                } else {

                    $token = md5(time());
                    $msg = 'Hi ' . $this->_scopeConfig->getValue('trans_email/ident_general/name') . ', ';

                    $msg .= "The exported Newsletter subscribers file is ready. 
                        <br><br>You can download it from the following link<br><br>";

                    $msg .= $this->_storeManager->getStore($this->_storeManager->getDefaultStoreView())
                                                ->getUrl('egoi/download/index', ['token' => $token]);

                    $msg .= "<br><br>Regards<br><br>";

                    $mail = new \Zend_Mail('UTF-8');
                    $mail->setBodyHtml($msg);
                    $mail->setFrom(
                        $this->_scopeConfig->getValue('trans_email/ident_general/email'),
                        $this->_scopeConfig->getValue('trans_email/ident_general/name')
                    )
                         ->addTo(
                             $this->_scopeConfig->getValue('trans_email/ident_general/email'),
                             $this->_scopeConfig->getValue('trans_email/ident_general/name')
                         )
                         ->setSubject('E-Goi - Export Finished');
                    $mail->send();

                    file_put_contents($tmpDir->getAbsolutePath('egoi_token.txt'), $token);

                }

            } else {

                unset($subscribers[0]);
                try {
                    $params = [
                        'apikey'        => $this->_scopeConfig->getValue('egoi/info/api_key'),
                        'plugin_key'    => self::PLUGIN_KEY,
                        'listID'        => $list->getListnum(),
                        'compareField'  => 'email',
                        'operation'     => 2,
                        'autoresponder' => 0,
                        'notification'  => 0,
                        'subscribers'   => $subscribers,
                    ];

                    $this->soapClient->addSubscriberBulk($params);

                    if (count($subscribers) == 200) {
                        $cron = $this->_cron->create();
                        $data['status'] = 'pending';
                        $data['job_code'] = 'egoi_sync_bulk';
                        $data['scheduled_at'] = new \Zend_Db_Expr('NOW()');
                        $data['created_at'] = new \Zend_Db_Expr('NOW()');
                        $cron->setData($data)->save();
                    }

                } catch (\Exception $e) {
                    return false;
                }
            }

            file_put_contents($file, $lastSync);
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getDataKey()
    {

        $data = $this->getData();
        $data['apikey'] = $this->_scopeConfig->getValue('egoi/info/api_key');
        $data['plugin_key'] = self::PLUGIN_KEY;

        return $data;
    }

    /**
     * @param      $result
     * @param null $index
     *
     * @return $this
     * @throws \Exception
     */
    public function processServiceResult($result, $index = null)
    {

        if (!is_array($result)) {
            $result = ['result' => $result];
        }

        $result = array_change_key_case($result, CASE_LOWER);

        if ($index && isset($result[$index])) {
            $result = $result[$index];
            $result = array_change_key_case($result, CASE_LOWER);
        }

        $this->setData($result);

        $additionalData = serialize(
            ['request' => $this->soapClient->__getLastRequest(), 'response' => $this->soapClient->__getLastResponse()]
        );

        if (isset($result['error'])) {
            $this->_egoiHelper->getLogger('crit')->critical(serialize($additionalData));
            $this->_egoiHelper->getLogger('crit')->critical($result['error']);
            throw new \Exception(__($result['error']));
        }

        return $this;
    }

    /**
     * @return Egoi
     */
    public function addExtraField()
    {

        $this->setData('type', 'texto');

        return $this->processServiceResult($this->soapClient->addExtraField($this->getDataKey()));
    }

    /**
     * @return $this
     */
    public function getAccountDetails()
    {

        $this->formatFields($this->soapClient->getClientData($this->getDataKey()));

        return $this;
    }

    /**
     * @return $this
     */
    public function getUserData()
    {

        $this->formatFields($this->soapClient->getUserData($this->getDataKey()));

        return $this;
    }

    /**
     * @return $this
     */
    public function getSenders()
    {

        $this->setData('channel', 'telemovel');
        $this->formatFields($this->soapClient->getSenders($this->getDataKey()));

        return $this;
    }

    /**
     * @param null $listnum
     *
     * @return $this
     */
    public function getLists($listnum = null)
    {

        $result = $this->soapClient->getLists($this->getDataKey());

        if (is_array($result)) {
            foreach ($result as $key => $value) {

                if ($listnum && $listnum != $value['listnum']) {
                    unset($result[$key]);
                }

                if (is_array($value) && (isset($value['extra_fields']) && !is_array($value['extra_fields']))) {
                    continue;
                }
                if (isset($value['extra_fields']) && is_array($value['extra_fields'])) {
                    foreach ($value['extra_fields'] as $eKey => $eValue) {
                        unset($result[$key]['extra_fields'][$eKey]['listnum']);
                        unset($result[$key]['extra_fields'][$eKey]['opcoes']);
                    }
                }
            }
        }

        $this->formatFields($result);

        return $this;
    }

    /**
     * @return $this
     */
    public function getSubscriberData()
    {

        $this->formatFields($this->soapClient->subscriberData($this->getDataKey()));

        return $this;
    }

    /**
     * @return $this
     */
    public function getSubscriberStatus()
    {

        $this->formatSingleField($this->soapClient->subscriberData($this->getDataKey()));

        return $this;
    }

    /**
     * @return Egoi
     */
    public function editApiCallback()
    {

        return $this->processServiceResult($this->soapClient->editApiCallback($this->getDataKey()));
    }

    /**
     * @return Egoi
     */
    public function createList()
    {

        return $this->processServiceResult($this->soapClient->createList($this->getDataKey()));
    }

    /**
     * @return Egoi
     */
    public function updateList()
    {

        return $this->processServiceResult($this->soapClient->updateList($this->getDataKey()));
    }

    /**
     * @return Egoi
     */
    public function addSubscriber()
    {

        $this->setData('status', 1);

        return $this->processServiceResult($this->soapClient->addSubscriber($this->getDataKey()));
    }

    /**
     * @return Egoi
     */
    public function editSubscriber()
    {

        return $this->processServiceResult($this->soapClient->editSubscriber($this->getDataKey()));
    }

    /**
     * @return array|bool|Egoi
     */
    public function removeSubscriber()
    {

        $result = $this->setData('listID', $this->getData('listID'))
                       ->setData('subscriber', $this->getSubscriber())
                       ->getSubscriberData()
                       ->getData();

        if (is_array($result) && $result[0]['subscriber']['STATUS'] != 2) {
            return $this->processServiceResult($this->soapClient->removeSubscriber($this->getDataKey()));
        }

        if ($this->getData('inCron')) {
            return false;
        }

        return ['error' => __('Subscriber not found or action not allowed')];
    }

    /**
     * @param null $apiKey
     *
     * @return $this
     */
    public function checkLogin($apiKey = null)
    {

        $data = $this->getDataKey();
        if ($apiKey) {
            $data['apikey'] = $apiKey;
        }
        $this->processServiceResult($this->soapClient->checklogin($data));

        return $this;
    }

    /**
     *
     */
    public function sync()
    {

        $account = $this->_accountFactory->create()->getAccount();
        $key = $this->_scopeConfig->getValue('egoi/info/api_key');

        if (!$key) {
            return;
        }

        $account->cron();
        $this->_susbcriberFactory->create()->cron();

    }

    /**
     *
     */
    public function syncCustomerData()
    {

        $core = $this->_coreCollectionFactory->create()
                                             ->addFieldToFilter('subscriber_status', 1);

        /** @var \Magento\Newsletter\Model\Subscriber $susbcriber */
        foreach ($core as $susbcriber) {

            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $this->_customerFactory->create()->load($susbcriber->getCustomerId());
            $egoi = $this->_susbcriberFactory->create()->load($susbcriber->getEmail(), 'email');

            if ($customer->getId() && !$egoi->getCustomerId()) {
                $data['email'] = $customer->getEmail();
                $data['customer_id'] = $customer->getId();
                $data['birth_date'] = substr($customer->getData('dob'), 0, 10);
                $data['first_name'] = $customer->getData('firstname');
                $data['last_name'] = $customer->getData('lastname');

                if ($customer->getData('cellphone')) {
                    $data['cellphone'] = $customer->getData('cellphone');
                }

                $egoi->addData($data)->save();
            }

        }

    }

    /**
     * @param \Magento\Store\Api\Data\StoreInterface $store
     *
     * @return bool|string
     */
    public function createCatalog(\Magento\Store\Api\Data\StoreInterface $store)
    {

        $data = [];
        $data['title'] = 'Magento - ' . $store->getName() . ' [' . $store->getId() . ']';
        $data['currency'] = $store->getBaseCurrencyCode();
        $data['language'] = substr(
            $this->_scopeConfig->getValue('general/locale/code', 'store', $store->getId()),
            0,
            2
        );

        $url = self::ECOMMERCE_URL . 'catalogs';

        $ch = curl_init($url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Apikey: ' . $this->_scopeConfig->getValue('egoi/info/api_key'),
            ]
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);

    }

    /**
     *
     */
    public function checkEcommerceCatalogs()
    {

        $stores = $this->getEcommerceCatalogs();

        foreach ($this->_storeManager->getStores() as $store) {

            $catalogIdForStore = $this->_scopeConfig->getValue('egoi/products/catalog_id', 'stores', $store->getId());
            if (!$catalogIdForStore || !array_key_exists($catalogIdForStore, $stores)) {

                $result = $this->createCatalog($store);
                /** @var \Magento\Config\Model\ResourceModel\Config */
                $this->_config->saveConfig(
                    'egoi/products/catalog_id',
                    $result['catalog_id'],
                    'stores',
                    $store->getId()
                );
                $this->_config->saveConfig('egoi/products/enable', 1, 'stores', $store->getId());
                $this->_reinitableConfig->reinit();

            }
        }
    }

    /**
     *
     */
    public function getEcommerceCatalogs()
    {

        $url = self::ECOMMERCE_URL . 'catalogs';

        $ch = curl_init($url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Apikey: ' . $this->_scopeConfig->getValue('egoi/info/api_key'),
            ]
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $final = \Zend_Json::decode($result);

        $catalogs = [];

        if ($final['total_items'] > 0) {
            foreach ($final['items'] as $info) {
                $catalogs[$info['catalog_id']] = $info['title'] . ' - ' . $info['language'] . ' - ' . $info['currency'];
            }
        }

        return $catalogs;

    }

    /**
     *
     */
    public function getActiveEcommerceCatalogs()
    {

        $stores = $this->getEcommerceCatalogs();
        $return = [];
        foreach ($this->_storeManager->getStores() as $store) {

            $catalogIdForStore = $this->_scopeConfig->getValue('egoi/products/catalog_id', 'store', $store->getId());
            if ($catalogIdForStore && array_key_exists($catalogIdForStore, $stores)) {
                $return[$store->getId()] = $stores[$catalogIdForStore];

            }
        }

        return $return;

    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function buildProductsFeed($storeId = 1)
    {

        $this->_storeManager->getStore()->setId($storeId);
        $attributesInfo = $this->_scopeConfig->getValue('egoi/products', 'stores', $storeId);
        $catalogId = $attributesInfo['catalog_id'];

        if (!isset($attributesInfo['enable']) || !$attributesInfo['enable']) {
            return false;
        }

        $category = $this->categoryFactory->create();
        $tree = $category->getTreeModel();
        $tree->load();

        $ids = $tree->getCollection()->addIsActiveFilter()->getAllIds();
        $cnt = 0;
        $categories = [];
        $catInfo = [];
        if ($ids) {

            foreach ($ids as $k => $id) {
                $category->load($id);
                $categories[$id]['name'] = $category->getName();
                $categories[$id]['path'] = $category->getPath();
            }

            foreach ($ids as $id) {

                $path = explode('/', $categories[$id]['path']);
                $string = '';
                foreach ($path as $pathId) {
                    if (isset($categories[$pathId])) {
                        $string .= $categories[$pathId]['name'] . ' > ';
                        $cnt++;
                    }
                }

                $string = str_replace('Root Catalog > Default Category > ', '', $string);
                $string = str_replace('Default Category > ', '', $string);
                $catInfo[$id] = rtrim($string, ' > ');
            }
        }

        $collection = $this->productFactory->create()
                                           ->getCollection()
                                           ->addAttributeToSelect('*');

        $collection->addAttributeToSelect('price')
                   ->addAttributeToFilter('status', 1)
                   ->addAttributeToFilter('visibility', 4);

        $return = [];

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection as $product) {

            $parents = $this->configurableType->getParentIdsByChild($product->getId());

            if ($parents && !$this->_scopeConfig->isSetFlag('egoi/products/configurable')) {
                continue;
            }

            $productUrl = $product->getProductUrl();
            if ($parents) {
                /** @var \Magento\Catalog\Model\Product $parent */
                $parent = $this->productFactory->create();
                $productImg = $parent->load($parents[0]);
                $productUrl = $parent->getProductUrl();
                $imageUrl = $this->imageHelper->init($productImg, 'product_page_image_small')
                                              ->setImageFile($productImg->getSmallImage())
                                              ->getUrl();

            } else {
                $imageUrl = $this->imageHelper->init($product, 'product_page_image_small')
                                              ->setImageFile($product->getSmallImage())
                                              ->getUrl();
            }

            $return[$product->getId()]['product_identifier'] = $product->getId();
            $return[$product->getId()]['sku'] = $product->getSku();
            $return[$product->getId()]['product_identifier'] = $product->getSku();
            $return[$product->getId()]['name'] = $product->getName();
            $return[$product->getId()]['description'] = $product->getShortDescription();
            $return[$product->getId()]['price'] = number_format($product->getPrice(), 2, '.', '');
            $return[$product->getId()]['image_link'] = $imageUrl;
            $return[$product->getId()]['link'] = $productUrl;

            if ($product->getFinalPrice() < $product->getPrice()) {
                $return[$product->getId()]['sale_price'] = number_format($product->getFinalPrice(), 2, '.', '');
            }

            $cats = $product->getCategoryIds();

            foreach ($cats as $cat) {
                if (isset($catInfo[$cat])) {
                    $return[$product->getId()]['categories'][] = $catInfo[$cat];
                }
            }

            $related = $product->getRelatedProductCollection();

            foreach ($related as $info) {

                $return[$product->getId()]['related_product'][] = $info->getSku();

            }

            $attributes = $product->getAttributes();

            foreach ($attributes as $attribute) {

                if (!in_array($attribute->getAttributeCode(), $attributesInfo)) {
                    continue;
                }

                if ($attribute->getSource()) {

                    $value = $product->getResource()
                                     ->getAttribute($attribute->getAttributeCode())
                                     ->getFrontend()
                                     ->getValue($product);

                    $return[$product->getId()][array_search(
                        $attribute->getAttributeCode(),
                        $attributesInfo
                    )] = (string) $value;

                }

            }

        }

        $return = ['products' => $return];

        $url = self::ECOMMERCE_URL . 'catalogs/' . $catalogId . '/products/actions/import';

        $ch = curl_init($url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Apikey: ' . $this->_scopeConfig->getValue('egoi/info/api_key'),
            ]
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($return));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        #$final = \Zend_Json::decode($result);

        return ($httpcode < 300);

    }

    /**
     * @return array|mixed
     */
    public function getWebPushSiteList()
    {

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => "api.egoiapp.com/webpush/sites",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => [
                    "Apikey: " . $this->_scopeConfig->getValue('egoi/info/api_key'),
                ],
            ]
        );

        $response = json_decode(curl_exec($curl), true);
        if (isset($response['items'])) {
            return $response['items'];
        }

        curl_close($curl);

        return [];
    }

    public function createWebPushSite()
    {

        $curl = curl_init();

        $name = parse_url($this->_scopeConfig->getValue('web/unsecure/base_url'))['host'];
        $site = rtrim($this->_scopeConfig->getValue('web/unsecure/base_url'), '/');
        $listId = $this->_listsFactory->create()->getList()->getListnum();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => "https://api.egoiapp.com/webpush/sites",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => "{\"site\":\"{$site}\",\"list_id\":\"{$listId}\",\"name\":\"{$name}\"}",
                CURLOPT_HTTPHEADER     => [
                    "Apikey: " . $this->_scopeConfig->getValue('egoi/info/api_key'),
                    "Content-Type: application/json",
                ],
            ]
        );

        $response = json_decode(curl_exec($curl), true);
        if (isset($response['app_code'])) {
            $this->_config->saveConfig('egoi/info/webpush', $response['app_code'], 'default', '0');
            $this->_reinitableConfig->reinit();
        }
        curl_close($curl);
    }

    /**
     * @param Subscriber $subscriber
     *
     * @return bool|string
     */
    public function unsubscribeApi(\Egoi\Marketing\Model\Subscriber $subscriber, $user)
    {

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => "api.egoiapp.com/lists/" . $subscriber->getData(
                        'list'
                    ) . "/contacts/actions/unsubscribe",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => "{\"data\":[{\"contact_id\":\"{$user['uid']}\"}]}",
                CURLOPT_HTTPHEADER     => [
                    "Apikey: " . $this->_scopeConfig->getValue('egoi/info/api_key'),
                    "Content-Type: application/json",
                ],
            ]
        );

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response;
    }

    /**
     * @param $email
     *
     * @return array
     */
    public function getSubscriberStatusApi($email)
    {

        $curl = curl_init();

        $db = $this->productFactory->create()->getResource();
        $name = $db->getTable('egoi_lists');

        $listId = $db->getConnection()->fetchOne("SELECT listnum from " . $name . " LIMIT 1");

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => "api.egoiapp.com/lists/{$listId}/contacts?email=" . $email,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => [
                    "Apikey: " . $this->_scopeConfig->getValue('egoi/info/api_key'),
                ],
            ]
        );
        try {
            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if (!empty($response['items'][0]['base']['status'])) {
                return [
                    'status' => $response['items'][0]['base']['status'],
                    'email'  => $response['items'][0]['base']['email'],
                    'uid'    => $response['items'][0]['base']['contact_id'],
                ];
            }
        } catch (\Exception $e) {

        }

        return [
            'status' => false,
            'email'  => false,
            'uid'    => false,
        ];

    }

    /**
     * @param $uid
     * @param $status
     */
    public function updateContactStatusApi($uid, $status)
    {

        $curl = curl_init();

        $data = [];
        $data['base']['status'] = $status;

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => "api.egoiapp.com/lists/{$this->getListNum()}/contacts/" . $uid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "PATCH",
                CURLOPT_POSTFIELDS     => json_encode($data),
                CURLOPT_HTTPHEADER     => [
                    "Apikey: " . $this->_scopeConfig->getValue('egoi/info/api_key'),
                    "Content-Type: application/json",
                ],
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);
    }

    /**
     * @param $data
     *
     * @return false|mixed
     */
    public function createContactApi($data)
    {

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL            => "api.egoiapp.com/lists/{$this->getListNum()}/contacts",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 5,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => json_encode($data),
                CURLOPT_HTTPHEADER     => [
                    "Apikey: " . $this->_scopeConfig->getValue('egoi/info/api_key'),
                    "Content-Type: application/json",
                ],
            ]
        );

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);

        if (!empty($response['contact_id'])) {
            return $response['contact_id'];
        }

        return false;

    }

    /**
     * @return string
     */
    public function getListNum()
    {

        $db = $this->productFactory->create()->getResource();
        $name = $db->getTable('egoi_lists');

        return $db->getConnection()->fetchOne("SELECT listnum from " . $name . " LIMIT 1");

    }
}
