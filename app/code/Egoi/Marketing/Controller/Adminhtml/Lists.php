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
class Lists extends \Magento\Backend\App\Action
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
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $_egoi;

    /**
     * @var \Egoi\Marketing\Model\AccountFactory
     */
    protected $_accountFactory;

    /**
     * @var \Egoi\Marketing\Model\ListsFactory
     */
    protected $_listsFactory;

    /**
     * @var \Egoi\Marketing\Model\ExtraFactory
     */
    protected $_extraFactory;

    /**
     * Lists constructor.
     *
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Egoi\Marketing\Model\AccountFactory              $accountFactory
     * @param \Egoi\Marketing\Model\ListsFactory                $listsFactory
     * @param \Egoi\Marketing\Model\ExtraFactory                $extraFactory
     * @param \Egoi\Marketing\Model\Egoi                        $egoi
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     */
    public function __construct(
        Action\Context                                    $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry,
        \Egoi\Marketing\Model\AccountFactory              $accountFactory,
        \Egoi\Marketing\Model\ListsFactory                $listsFactory,
        \Egoi\Marketing\Model\ExtraFactory                $extraFactory,
        \Egoi\Marketing\Model\Egoi                        $egoi,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
    )
    {

        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_layoutFactory = $resultLayoutFactory;
        $this->_egoi = $egoi;
        $this->_listsFactory = $listsFactory;
        $this->_extraFactory = $extraFactory;
        $this->_accountFactory = $accountFactory;
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
        $resultPage->setActiveMenu('Egoi_Marketing::lists')
                   ->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'))
                   ->addBreadcrumb(__('Manage Lists'), __('Manage Lists'));

        return $resultPage;
    }

    /**
     *
     */
    public function execute()
    {

        $auth = $this->_egoi->validateEgoiEnvironment();
        if (!$auth) {
            $this->_redirect('*/account/new');

            return;
        }

        $model = $this->_listsFactory->create()->getList();

        $this->_coreRegistry->register('egoi_list', $model);

    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Egoi_Marketing::lists');
    }
}
