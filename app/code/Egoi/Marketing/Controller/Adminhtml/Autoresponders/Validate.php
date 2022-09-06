<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Autoresponders;

use Magento\Backend\App\Action;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Validate
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Autoresponders
 */
class Validate extends \Egoi\Marketing\Controller\Adminhtml\Autoresponders
{

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $_egoi;

    /**
     * Validate constructor.
     *
     * @param StoreManagerInterface                             $storeManagerInterface
     * @param \Egoi\Marketing\Model\Egoi                        $egoi
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Egoi\Marketing\Model\AutorespondersFactory       $autorespondersFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface        $storeManagerInterface,
        \Egoi\Marketing\Model\Egoi                        $egoi,
        Action\Context                                    $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry,
        \Egoi\Marketing\Model\AutorespondersFactory       $autorespondersFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
    )
    {

        $this->_egoi = $egoi;
        $this->_storeManager = $storeManagerInterface;

        parent::__construct(
            $context,
            $resultPageFactory,
            $registry,
            $autorespondersFactory,
            $resultForwardFactory,
            $resultLayoutFactory
        );
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        parent::execute();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $params = $this->getRequest()->getParams();
        $number = $params['number'];

        /** @var \Egoi\Marketing\Model\Egoi $egoi */
        $egoi = $this->_egoi;
        /** @var \Magento\Store\Model\StoreManager $store */
        $store = $this->_storeManager;

        if (!$egoi->validateNumber($number)) {

            $this->messageManager->addErrorMessage(__('Please insert a valid Phone Number xxx-xxxxxx'));

            return $resultRedirect->setRefererUrl();
        }

        $result = $egoi->send($number, 'Test Message from Magento Store', $store->getDefaultStoreView()->getId());

        if ($result === true) {
            $this->messageManager->addSuccessMessage(__('Message Sent'));
        } else {
            $this->messageManager->addErrorMessage(__('ERROR: Check your settings' . $result));
        }

        return $resultRedirect->setRefererUrl();
    }
}
