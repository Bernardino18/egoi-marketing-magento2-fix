<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model;

/**
 * Class Autoresponders
 *
 * @package Egoi\Marketing\Model
 */
class Autoresponders extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_cutomerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'egoi_autoresponders';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'autoresponders';

    /**
     * @var AutorespondersFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var EventsFactory
     */
    protected $_eventsFactory;

    /**
     * @var ResourceModel\Events\CollectionFactory
     */
    protected $_eventsCollection;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_coreDate;

    /**
     * @var \Magento\Sales\Model\Order\ConfigFactory
     */
    protected $_configFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Session
     */
    protected $_egoiSession;

    /**
     * @var \Magento\Newsletter\Model\Template\Filter
     */
    protected $_filterTemplate;

    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    protected $_shipmentFactory;

    /**
     * @var Egoi
     */
    protected $_egoi;

    /**
     * @var ResourceModel\Autoresponders\CollectionFactory
     */
    protected $_autorespondersCollection;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $egoiHelper;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Framework\Url
     */
    protected $url;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('Egoi\Marketing\Model\ResourceModel\Autoresponders');
    }

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * Autoresponders constructor.
     *
     * @param \Magento\Framework\Url                                       $frontendUrl
     * @param \Magento\Customer\Model\Session                              $session
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager
     * @param \Magento\Framework\UrlInterface                              $urlInterface
     * @param \Egoi\Marketing\Helper\Data                                  $egoiHelper
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param AutorespondersFactory                                        $autorespondersFactory
     * @param Egoi                                                         $egoi
     * @param \Magento\Newsletter\Model\Template\Filter                    $filter
     * @param EventsFactory                                                $eventsFactory
     * @param ResourceModel\Events\CollectionFactory                       $eventsCollection
     * @param ResourceModel\Autoresponders\CollectionFactory               $autorespondersCollection
     * @param \Magento\Sales\Model\Order\Shipment                          $shipmentFactory
     * @param \Magento\Sales\Model\OrderFactory                            $orderFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                  $dateTime
     * @param \Magento\Sales\Model\Order\ConfigFactory                     $configFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\Url                                  $frontendUrl,
        \Magento\Customer\Model\Session                         $session,
        \Magento\Store\Model\StoreManagerInterface              $storeManager,
        \Magento\Framework\UrlInterface                         $urlInterface,
        \Egoi\Marketing\Helper\Data                             $egoiHelper,
        \Magento\Framework\Model\Context                        $context,
        \Magento\Framework\Registry                             $registry,
        AutorespondersFactory                                   $autorespondersFactory,
        Egoi                                                    $egoi,
        \Magento\Newsletter\Model\Template\Filter               $filter,
        EventsFactory                                           $eventsFactory,
        ResourceModel\Events\CollectionFactory                  $eventsCollection,
        ResourceModel\Autoresponders\CollectionFactory          $autorespondersCollection,
        \Magento\Sales\Model\Order\Shipment                     $shipmentFactory,
        \Magento\Sales\Model\OrderFactory                       $orderFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime             $dateTime,
        \Magento\Sales\Model\Order\ConfigFactory                $configFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection = null,
        array                                                   $data = []
    )
    {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->url = $frontendUrl;

        $this->_cutomerSession = $session;
        $this->_storeManager = $storeManager;
        $this->_orderFactory = $orderFactory;

        $this->egoiHelper = $egoiHelper;

        $this->_egoi = $egoi;

        $this->_filterTemplate = $filter;

        $this->_shipmentFactory = $shipmentFactory;

        $this->_coreDate = $dateTime;

        $this->_autorespondersFactory = $autorespondersFactory;

        $this->_eventsFactory = $eventsFactory;

        $this->_eventsCollection = $eventsCollection;

        $this->_configFactory = $configFactory;

        $this->_autorespondersCollection = $autorespondersCollection;

        $this->_urlInterface = $urlInterface;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $status = $this->_configFactory->create()->getStatuses();
        $return = [
            'shipment_new'   => __('Shipment - New'),
            'invoice_new'    => __('Invoice - New'),
            'creditmemo_new' => __('Creditmemo - New'),
            'order_new'      => __('Order - New Order'),
        ];

        foreach ($status as $key => $value) {
            $return['order_status_' . $key] = __('Order - Status Changes To ') . $value;
        }

        return $return;
    }

    /**
     * @return array
     */
    public function toOptionValues()
    {

        $options = $this->toOptionArray();
        $return = [];

        foreach ($options as $value => $label) {
            $return[] = ['label' => $label, 'value' => $value];
        }

        return $return;
    }

    /**
     * @param $event
     *
     * @return $this|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function changeStatus($event)
    {

        $order = $event->getEvent()->getOrder();
        $newStatus = $order->getData('status');
        $olderStatus = $order->getOrigData('status');

        if ($newStatus == $olderStatus) {
            return false;
        }

        $phone = $this->_egoi->getPhone($order);

        if (!$phone) {
            $this->egoiHelper->getLogger('autoresponder')->debug('no phone' . $phone);

            return false;
        }

        $autoresponders = $this->_getCollection()
                               ->addFieldToFilter('event', 'order_status_' . $newStatus);

        if ($autoresponders->count() == 0) {
            $this->egoiHelper->getLogger('autoresponder')->debug('no autoresponders order_status_' . $newStatus);

            return false;
        }

        $customer = new \Magento\Framework\DataObject;
        $customer->setName($order->getCustomerName())
                 ->setEmail($order->getCustomerEmail())
                 ->setId($order->getCustomerId());

        /** @var \Egoi\Marketing\Model\Autoresponders $autoresponder */
        foreach ($autoresponders as $autoresponder) {

            if ($autoresponder->getShippingMethod()) {
                $allow = explode(',', $autoresponder->getShippingMethod());
                $shippingMethod = $order->getData('shipping_method');
                if (!in_array($shippingMethod, $allow)) {
                    $this->egoiHelper->getLogger('autoresponder')->debug(
                        'Not in Shipping. autoresponders AR: ' . $autoresponder->getId()
                    );
                    continue;
                }
            }
            if ($autoresponder->getPaymentMethod()) {
                $allow = explode(',', $autoresponder->getPaymentMethod());
                $paymentMethod = $order->getPayment()->getMethod();

                if (!in_array($paymentMethod, $allow)) {
                    $this->egoiHelper->getLogger('autoresponder')->debug(
                        'Not in Payment. autoresponders AR: ' . $autoresponder->getId()
                    );
                    continue;
                }
            }

            if ($autoresponder->getData('order_status_previous') &&
                $autoresponder->getData('order_status_previous') != $olderStatus
            ) {
                continue;
            }

            if ($autoresponder->getData('order_status_previous') &&
                $autoresponder->getData('order_status_time') &&
                $autoresponder->getData('order_status_previous') == $olderStatus
            ) {

                $history = $order->getAllStatusHistory();
                $totalHistory = count($history);

                if ($totalHistory < 2) {
                    continue;
                }

                $dateTime = new \DateTime();
                foreach ($history as $item) {
                    if ($item->getData('status') == $olderStatus) {
                        $dateTime = $item->getData('created_at');
                        break;
                    }
                }

                $start_date = new \DateTime($history[0]->getData('created_at'));
                $since_start = $start_date->diff(new \DateTime($dateTime));

                $minutes = $since_start->days * 24 * 60;
                $minutes += $since_start->h * 60;
                $minutes += $since_start->i;

                if ($minutes > $autoresponder->getData('order_status_time')) {
                    continue;
                }
            }

            $this->_insertData($autoresponder, $phone, $order->getStoreId(), $customer, $order->getId());
        }

        return $this;
    }

    /**
     * @param $event
     *
     * @return $this|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function newOrderDocument($event)
    {

        $document = $event->getEvent()->getDataObject();

        if ($document instanceof \Magento\Sales\Model\Order\Invoice) {
            $type = 'invoice_new';
        } elseif ($document instanceof \Magento\Sales\Model\Order\Shipment) {
            $type = 'shipment_new';
        } elseif ($document instanceof \Magento\Sales\Model\Order\Creditmemo) {
            $type = 'creditmemo_new';
        } else {
            return false;
        }

        $order = $document->getOrder();

        $phone = $this->_egoi->getPhone($order);

        if (!$phone) {
            return false;
        }

        $autoresponders = $this->_getCollection()
                               ->addFieldToFilter('event', $type);

        $customer = new \Magento\Framework\DataObject();
        $customer->setName($order->getCustomerName())
                 ->setEmail($order->getCustomerEmail())
                 ->setId($order->getCustomerId());

        foreach ($autoresponders as $autoresponder) {

            if ($autoresponder->getShippingMethod()) {
                $allow = explode(',', $autoresponder->getShippingMethod());
                $shippingMethod = $order->getData('shipping_method');
                if (!in_array($shippingMethod, $allow)) {
                    continue;
                }
            }
            if ($autoresponder->getPaymentMethod()) {
                $allow = explode(',', $autoresponder->getPaymentMethod());
                $paymentMethod = $order->getPayment()->getMethod();

                if (!in_array($paymentMethod, $allow)) {
                    continue;
                }
            }

            $autoresponder->setDataObjectId($document->getId());

            $this->_insertData($autoresponder, $phone, $order->getStoreId(), $customer, $document->getId());
        }

        return $this;
    }

    /**
     * @param      $event
     * @param null $redirect
     *
     * @return $this|bool
     */
    public function newOrder($event, $redirect = null)
    {

        $message = null;

        if (!$redirect) {
            $order = $event->getEvent()->getOrder();
        } else {
            $order = $event;
        }

        if ($order->getPayment()->getMethod() == 'pagseguro_boleto' && !$redirect) {
            $this->_cutomerSession->setData('egoi_pagseguro', $order->getId());

            return $this;
        }

        if ($order->getPayment()->getMethod() == 'pagseguro_boleto' && $redirect) {
            $order = $event;
            $message = $redirect;
        }

        $autoresponders = $this->_getCollection()
                               ->addFieldToFilter('event', 'order_new');

        if ($autoresponders->count() == 0) {
            $this->egoiHelper->getLogger('autoresponder')->debug('No autoresponders: New Order');

            return false;
        }

        $customer = new \Magento\Framework\DataObject();
        $customer->setName($order->getCustomerName())
                 ->setEmail($order->getCustomerEmail())
                 ->setId($order->getCustomerId());

        $phone = $this->_egoi->getPhone($order);

        if (!$phone) {
            $this->egoiHelper->getLogger('autoresponder')->debug('No phone. New Order. ' . $order->getId());

            return false;
        }

        foreach ($autoresponders as $autoresponder) {

            if ($autoresponder->getShippingMethod()) {
                $allow = explode(',', $autoresponder->getShippingMethod());
                $shippingMethod = $order->getData('shipping_method');
                if (!in_array($shippingMethod, $allow)) {
                    continue;
                }
            }
            if ($autoresponder->getPaymentMethod()) {
                $allow = explode(',', $autoresponder->getPaymentMethod());
                $paymentMethod = $order->getPayment()->getMethod();

                if (!in_array($paymentMethod, $allow)) {
                    continue;
                }
            }

            $autoresponder->setDataObjectId($order->getId());

            $this->_insertData(
                $autoresponder,
                $phone,
                $order->getStoreId(),
                $customer,
                $order->getId(),
                $message,
                $redirect
            );

        }

        return $this;
    }

    /**
     * @param Autoresponders $autoresponder
     *
     * @return string
     */
    public function calculateSendDate(Autoresponders $autoresponder)
    {

        $date = new \DateTime($this->_coreDate->date());

        if ($autoresponder->getSendMoment() == 'after') {
            if ($autoresponder->getAfterHours() > 0) {
                $date->addHour($autoresponder->getAfterHours());
            }
            if ($autoresponder->getAfterDays() > 0) {
                $date->addDay($autoresponder->getAfterDays());
            }
        }

        return $date->format('Y-m-d H:i:s');
    }

    /**
     *
     */
    public function send()
    {

        $date = $this->_coreDate->date();

        $sms = $this->_eventsCollection->create()
                                       ->addFieldToFilter('sent', 0)
                                       ->addFieldToFilter('send_at', ['lteq' => $date]);

        foreach ($sms as $cron) {

            if (!$this->_egoi->validateNumber($cron->getCellphone())) {
                $cron->delete();
                continue;
            }

            $autoresponder = $this->_autorespondersFactory->create()->load($cron->getAutoresponderId());

            $isAutoresponderEnable = $this->_getCollection()
                                          ->addFieldToFilter('autoresponder_id', $autoresponder->getId())
                                          ->getSize();

            if ($isAutoresponderEnable != 1 || !$autoresponder->getId()) {
                $cron->setSent(1)->save();
                continue;
            }

            $message = $this->_filterTemplate->filter($autoresponder->getMessage());

            if ($autoresponder->getEvent() == 'order_new' && $cron->getDataObjectId()) {

                /** @var \Magento\Sales\Model\Order $order */
                $order = $this->_orderFactory->create()->load($cron->getDataObjectId());

                $replaceMb = false;
                if ($order && $order->getPayment()->getMethod() == 'easypay_multibanco') {
                    $replaceMb = true;

                    if ($order->getData('ep_entity')) {
                        $entidade = $order->getData('ep_entity');
                        $referencia = $order->getData('ep_reference');
                    } else {
                        $paymentInfo = $order->getPayment()->getAdditionalInformation('easypay');
                        $entidade = $paymentInfo['method']['entity'];
                        $referencia = chunk_split($paymentInfo['method']['reference'], 3, ' ');
                    }
                    $valor = $order->getPayment()->getAmountOrdered();
                }

                if ($order && $order->getPayment()->getMethod() == 'eupago_multibanco') {
                    $replaceMb = true;
                    $entidade = $order->getPayment()->getAdditionalInformation('entidade');
                    $referencia = $order->getPayment()->getAdditionalInformation('referencia');
                    $valor = $order->getPayment()->getAdditionalInformation('valor');
                }

                if ($order && $order->getPayment()->getMethod() == 'wallet') {

                    $connection = $order->getResource()->getConnection();

                    $mbExists = $connection->fetchRow(
                        $connection->select()
                                   ->from($order->getResource()->getTable('meowallet_mb'))
                                   ->where('sales_order_id=?', $order->getId())
                    );

                    if ($mbExists) {
                        $replaceMb = true;
                        $entidade = $mbExists['entity'];
                        $referencia = $mbExists['reference'];
                        $valor = $mbExists['amount'];
                    } else {

                        $env = $this->egoiHelper->getScopeConfig()
                                                ->getValue('payment/wallet/environment');

                        if ($env == 'sandbox') {
                            $url = \PTPay\MeoWallet\SDK\Api::ENV_SANDBOX_API_URL;
                            $apiKey = $this->egoiHelper->getEncryptor()->decrypt(
                                $this->egoiHelper->getScopeConfig()->getValue('payment/wallet/sandbox_apikey')
                            );
                        } else {
                            $url = \PTPay\MeoWallet\SDK\Api::ENV_PRODUCTION_API_URL;
                            $apiKey = $this->egoiHelper->getEncryptor()->decrypt(
                                $this->egoiHelper->getScopeConfig()->getValue('payment/wallet/production_apikey')
                            );
                        }

                        $mbExists = $connection->fetchRow(
                            $connection->select()
                                       ->from($order->getResource()->getTable('meowallet_checkout'))
                                       ->where('sales_order_id=?', $order->getId())
                        );

                        $curl = curl_init();

                        curl_setopt_array(
                            $curl,
                            [
                                CURLOPT_URL            => $url . "/checkout/" . $mbExists['meowallet_checkout_id'],
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING       => "",
                                CURLOPT_MAXREDIRS      => 10,
                                CURLOPT_TIMEOUT        => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST  => "GET",
                                CURLOPT_HTTPHEADER     => [
                                    "Authorization: WalletPT " . $apiKey,
                                ],
                            ]
                        );

                        $response = curl_exec($curl);

                        curl_close($curl);

                        $json = json_decode($response, true);

                        if (isset($json['payment']['method']) && $json['payment']['method'] == 'MB') {
                            $replaceMb = true;
                            $entidade = $json['payment']['mb']['entity'];
                            $referencia = $json['payment']['mb']['ref'];
                            $valor = $order->getBaseGrandTotal();
                        } else {
                            $cron->delete();

                            return false;
                        }
                    }
                }

                if ($order && $order->getPayment()->getMethod() == 'ifthenpay_multibanco') {
                    /** @var \Ifthenpay\Multibanco\Helper\GerarReferencias $helper */
                    $helper = \Magento\Framework\App\ObjectManager::getInstance()
                                                                  ->get(
                                                                      '\Ifthenpay\Multibanco\Helper\GerarReferencias'
                                                                  );
                    /** @var \Ifthenpay\Multibanco\Helper\Data $helperIfthen */
                    $helperIfthen = \Magento\Framework\App\ObjectManager::getInstance()
                                                                        ->get('\Ifthenpay\Multibanco\Helper\Data');
                    $replaceMb = true;
                    $entidade = $helperIfthen->getEntidade();
                    $valor = $order->getGrandTotal();
                    $referencia = $helper->GenerateMbRef(
                        $helperIfthen->getEntidade(),
                        $helperIfthen->getSubentidade(),
                        $order->getData('increment_id'),
                        $order->getData('grand_total'),
                        true
                    );
                }

                if ($order && $order->getPayment()->getMethod() == 'pagseguro_boleto') {
                    $message = str_replace(
                        '{boleto}',
                        $this->url->getUrl(
                            'egoi/r/i',
                            ['c' => md5($cron->getExtra()), '_nosid' => true]
                        ),
                        $message
                    );
                }

                if ($replaceMb) {
                    $message = str_replace(
                        ['{entidade}', '{referencia}', '{valor}'],
                        [$entidade, $referencia, number_format((float) $valor, 2)],
                        $message
                    );
                }

            }

            if ($autoresponder->getEvent() == 'shipment_new') {

                /** @var \Magento\Sales\Model\Order\Shipment $shipment */
                $shipment = $this->_shipmentFactory->load($cron->getDataObjectId());

                /** @var \Magento\Sales\Model\Order\Shipment\Track $track */
                $track = $shipment->getTracksCollection()
                                  ->getFirstItem();

                if ($track->getId()) {
                    $message = str_replace(
                        ['{track_number}', '{track_title}', '{order_number}', '{customer_name}'],
                        [
                            $track->getTrackNumber(),
                            $track->getTitle(),
                            $shipment->getOrder()->getIncrementId(),
                            $shipment->getOrder()->getCustomerName(),
                        ],
                        $message
                    );
                }
            }

            try {
                $result = $this->_egoi->send($cron->getCellphone(), $message, $cron->getStoreId());
            } catch (\Exception $exception) {
                $result = false;
            }

            if ($result === true) {
                $cron->setSent(1)->setMessage($message)->setSentAt($date)->save();
            } else {
                $datetime1 = new \DateTime($date);
                $datetime2 = new \DateTime($cron->getData('send_at'));
                $interval = $datetime1->diff($datetime2);
                if ($interval->format('%h') > 3) {
                    $cron->delete();
                }

            }
        }

    }

    /**
     * @param      $autoresponder
     * @param      $number
     * @param      $storeId
     * @param      $customer
     * @param null $dataObjectId
     * @param null $message
     * @param null $extra
     *
     * @return $this
     */
    protected function _insertData($autoresponder, $number, $storeId, $customer, $dataObjectId = null, $message = null,
                                   $extra = null)
    {

        if ($autoresponder->getStoreIds()) {
            $storeIds = explode(',', $autoresponder->getStoreIds());
            if (!in_array($storeId, $storeIds)) {
                return $this;
            }
        }

        $data = [];
        $data['send_at'] = $this->calculateSendDate($autoresponder);
        $data['autoresponder_id'] = $autoresponder->getId();
        $data['cellphone'] = $number;
        $data['store_id'] = $storeId;
        $data['customer_id'] = $customer->getId();
        $data['customer_name'] = $customer->getName();
        $data['customer_email'] = $customer->getEmail();
        $data['event'] = $autoresponder->getEvent();
        $data['created_at'] = new \Zend_Db_Expr('NOW()');
        $data['sent'] = 0;
        $data['data_object_id'] = $dataObjectId;
        $data['message'] = $message;
        $data['extra'] = $extra;

        $this->_eventsFactory->create()->setData($data)->save();
        $autoresponder->setData('number_subscribers', $autoresponder->getData('number_subscribers') + 1)->save();

        return $this;

    }

    /**
     * @return array
     */
    public function toFormValues()
    {

        $return = [];
        $collection = $this->_autorespondersCollection->create()
                                                      ->addFieldToSelect('name')
                                                      ->addFieldToSelect('autoresponder_id')
                                                      ->setOrder('name', 'ASC');
        foreach ($collection as $autoresponder) {
            $return[$autoresponder->getId()] = $autoresponder->getName() . ' (ID:' . $autoresponder->getId() . ')';
        }

        return $return;
    }

    /**
     * @return mixed
     */
    protected function _getCollection()
    {

        $date = $this->_coreDate->date();
        $return = $this->_autorespondersCollection->create()
                                                  ->addFieldToFilter('active', 1);
        $return->getSelect()
               ->where(" from_date <=? or from_date IS NULL ", $date)
               ->where(" to_date >=? or to_date IS NULL ", $date);

        return $return;
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function getRedirect($code)
    {

        $events = $this->_eventsCollection->create();

        $events->getSelect()->where('MD5(extra)=?', $code);

        return $events->getFirstItem()->getData('extra');

    }

}
