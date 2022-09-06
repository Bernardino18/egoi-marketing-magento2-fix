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
 * Conversions controller
 */
class Conversions extends \Magento\Backend\App\Action
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
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $conversionsFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Egoi\Marketing\Model\AutorespondersFactory       $conversionsFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory  $fileFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     */
    public function __construct(
        Action\Context                                    $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry,
        \Egoi\Marketing\Model\ConversionsFactory          $conversionsFactory,
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
        $this->conversionsFactory = $conversionsFactory;
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

        return $this->_authorization->isAllowed('Egoi_Marketing::conversions');
    }
}
