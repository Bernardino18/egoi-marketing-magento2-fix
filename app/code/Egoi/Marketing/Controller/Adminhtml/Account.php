<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Newsletter subscribers controller
 */
class Account extends \Magento\Backend\App\Action
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * @var \Egoi\Marketing\Model\SubscriberFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var \Egoi\Marketing\Model\AccountFactory
     */
    protected $_accountFactory;

    /**
     * @var \Egoi\Marketing\Model\ListsFactory
     */
    protected $_listsFactory;

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $_egoi;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Cron\Model\ScheduleFactory
     */
    protected $_cron;

    /**
     * @var \Magento\Framework\App\ReinitableConfig
     */
    protected $_reinitableConfig;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Account constructor.
     *
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
        \Magento\Framework\App\ReinitableConfig           $reinitableConfig,
        \Magento\Store\Model\StoreManagerInterface        $storeManager,
        \Magento\Config\Model\ResourceModel\Config        $config,
        \Magento\Framework\Filesystem                     $filesystem,
        \Magento\Cron\Model\ScheduleFactory               $scheduleFactory,
        Action\Context                                    $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry,
        \Egoi\Marketing\Model\Egoi                        $egoi,
        \Egoi\Marketing\Model\ListsFactory                $listsFactory,
        \Egoi\Marketing\Model\AccountFactory              $accountFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
    )
    {

        $this->_storeManager = $storeManager;
        $this->_reinitableConfig = $reinitableConfig;
        $this->_config = $config;
        $this->_cron = $scheduleFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;

        $this->_egoi = $egoi;
        $this->_coreRegistry = $registry;
        $this->_layoutFactory = $resultLayoutFactory;
        $this->_listsFactory = $listsFactory;
        $this->_accountFactory = $accountFactory;
        $this->_fileSystem = $filesystem;

        parent::__construct($context);

    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {

        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Egoi_Marketing::account')
                   ->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'))
                   ->addBreadcrumb(__('Manage Account'), __('Manage Account'));

        return $resultPage;
    }

    /**
     *
     */
    public function execute()
    {

        $model = $this->_accountFactory->create()->getAccount();
        $this->_coreRegistry->register('egoi_account', $model, true);

    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Egoi_Marketing::account');
    }
}
