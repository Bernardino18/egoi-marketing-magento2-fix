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
class Subscriber extends \Magento\Backend\App\Action
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
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * @var \Egoi\Marketing\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Egoi\Marketing\Model\SubscriberFactory           $subscriberFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory  $fileFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     */
    public function __construct(
        Action\Context                                    $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry,
        \Egoi\Marketing\Model\SubscriberFactory           $subscriberFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\App\Response\Http\FileFactory  $fileFactory,
        \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
    )
    {

        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_fileFactory = $fileFactory;
        $this->_layoutFactory = $resultLayoutFactory;
        $this->_subscriberFactory = $subscriberFactory;
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
        $resultPage->setActiveMenu('Egoi_Marketing::subscribers')
                   ->addBreadcrumb(__('Subscribers'), __('Subscribers'))
                   ->addBreadcrumb(__('Manage Subscribers'), __('Manage Subscribers'));

        return $resultPage;
    }

    /**
     *
     */
    public function execute()
    {

        $model = $this->_subscriberFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $model->load($id);
        }

        if ($data = $this->_getSession()->getFormData(true)) {
            $model->addData($data);
        }

        $this->_coreRegistry->register('egoi_subscriber', $model, true);

    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Egoi_Marketing::subscribers');
    }
}
