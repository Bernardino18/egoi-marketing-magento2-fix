<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Account;

use Egoi\Marketing\Model\Subscriber;
use Magento\Backend\App\Action;
use Magento\Customer\Model\CustomerFactory;
use Magento\Newsletter\Model\SubscriberFactory;

/**
 * Class Refresh
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Account
 */
class Refresh extends \Egoi\Marketing\Controller\Adminhtml\Account
{

    /**
     * @var SubscriberFactory
     */
    protected $_coreSubscriberFactory;

    /**
     * @var \Egoi\Marketing\Model\SubscriberFactory
     */
    protected $_egoiSubscriberFactory;

    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * Refresh constructor.
     *
     * @param SubscriberFactory                                 $subscriberFactory
     * @param \Egoi\Marketing\Model\SubscriberFactory           $egoiSubscriberFactory
     * @param CustomerFactory                                   $customerFactory
     * @param \Magento\Framework\App\ReinitableConfig           $reinitableConfig
     * @param \Magento\Store\Model\StoreManagerInterface        $storeManager
     * @param \Magento\Config\Model\ResourceModel\Config        $config
     * @param \Magento\Framework\Filesystem                     $filesystem
     * @param \Magento\Cron\Model\ScheduleFactory               $scheduleFactory
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Egoi\Marketing\Model\Egoi                        $egoi
     * @param \Egoi\Marketing\Model\ListsFactory                $listsFactory
     * @param \Egoi\Marketing\Model\AccountFactory              $accountFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     */
    public function __construct(
        \Magento\Newsletter\Model\SubscriberFactory       $subscriberFactory,
        \Egoi\Marketing\Model\SubscriberFactory           $egoiSubscriberFactory,
        \Magento\Customer\Model\CustomerFactory           $customerFactory,
        \Magento\Framework\App\ReinitableConfig           $reinitableConfig,
        \Magento\Store\Model\StoreManagerInterface        $storeManager,
        \Magento\Config\Model\ResourceModel\Config        $config,
        \Magento\Framework\Filesystem                     $filesystem,
        \Magento\Cron\Model\ScheduleFactory               $scheduleFactory, Action\Context $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry, \Egoi\Marketing\Model\Egoi $egoi,
        \Egoi\Marketing\Model\ListsFactory                $listsFactory,
        \Egoi\Marketing\Model\AccountFactory              $accountFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
    )
    {

        parent::__construct(
            $reinitableConfig,
            $storeManager,
            $config,
            $filesystem,
            $scheduleFactory,
            $context,
            $resultPageFactory,
            $registry,
            $egoi,
            $listsFactory,
            $accountFactory,
            $resultForwardFactory,
            $resultLayoutFactory
        );

        $this->_egoiSubscriberFactory = $egoiSubscriberFactory;
        $this->_customerFactory = $customerFactory;
        $this->_coreSubscriberFactory = $subscriberFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|void
     */
    public function execute()
    {

        $core = $this->_coreSubscriberFactory->create()
                                             ->getCollection()
                                             ->addFieldToFilter('subscriber_status', 1);

        /** @var Mage_Newsletter_Model_Subscriber $susbcriber */
        foreach ($core as $susbcriber) {

            /** @var Mage_Customer_Model_Customer $customer */
            $customer = $this->_customerFactory->create()->load($susbcriber->getCustomerId());
            $egoi = $this->_egoiSubscriberFactory->create()->load($susbcriber->getEmail(), 'email');

            $data = $susbcriber->getData();
            if ($customer->getId()) {
                $data['email'] = $customer->getEmail();
                $data['customer_id'] = $customer->getId();
                $data['birth_date'] = substr($customer->getData('dob'), 0, 10);
                $data['first_name'] = $customer->getData('firstname');
                $data['last_name'] = $customer->getData('lastname');

                if ($customer->getData('cellphone')) {
                    $data['cellphone'] = $customer->getData('cellphone');
                }
            }

            $egoi->addData($data)->save();

        }

        $this->messageManager->addSuccessMessage('Success.');

        $this->_redirect('*/account/index');

        return;

    }
}
