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
class Support extends \Magento\Backend\App\Action
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
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Egoi\Marketing\Model\AccountFactory
     */
    protected $_accountFactory;

    /**
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     */
    public function __construct(
        \Egoi\Marketing\Model\AccountFactory               $accountFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem                      $filesystem,
        Action\Context                                     $context,
        \Magento\Framework\View\Result\PageFactory         $resultPageFactory,
        \Magento\Framework\Registry                        $registry,
        \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
    )
    {

        $this->_accountFactory = $accountFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_fileSystem = $filesystem;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_layoutFactory = $resultLayoutFactory;
        parent::__construct($context);

    }

    /**
     *
     */
    public function execute()
    {

    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Egoi_Marketing::support');
    }
}
