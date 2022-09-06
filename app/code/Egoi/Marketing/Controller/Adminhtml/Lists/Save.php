<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Lists;

use Magento\Backend\App\Action;

/**
 * Class Save
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Lists
 */
class Save extends \Egoi\Marketing\Controller\Adminhtml\Lists
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $_dateFilter;

    /**
     * @var
     */
    protected $_coreDate;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * Save constructor.
     *
     * @param Action\Context                                     $context
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Magento\Framework\Registry                        $registry
     * @param \Egoi\Marketing\Model\AccountFactory               $accountFactory
     * @param \Egoi\Marketing\Model\ListsFactory                 $listsFactory
     * @param \Egoi\Marketing\Model\ExtraFactory                 $extraFactory
     * @param \Egoi\Marketing\Model\Egoi                         $egoi
     * @param \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
     * @param \Magento\Catalog\Model\ProductFactory              $productFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date     $dateFilter
     * @param \Magento\Framework\Stdlib\DateTime\DateTime        $dateTime
     * @param \Egoi\Marketing\Model\AutorespondersFactory        $autorespondersFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        Action\Context                                     $context,
        \Magento\Framework\View\Result\PageFactory         $resultPageFactory,
        \Magento\Framework\Registry                        $registry,
        \Egoi\Marketing\Model\AccountFactory               $accountFactory,
        \Egoi\Marketing\Model\ListsFactory                 $listsFactory,
        \Egoi\Marketing\Model\ExtraFactory                 $extraFactory,
        \Egoi\Marketing\Model\Egoi                         $egoi,
        \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory,
        \Magento\Catalog\Model\ProductFactory              $productFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date     $dateFilter,
        \Magento\Framework\Stdlib\DateTime\DateTime        $dateTime,
        \Egoi\Marketing\Model\AutorespondersFactory        $autorespondersFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    )
    {

        parent::__construct(
            $context,
            $resultPageFactory,
            $registry,
            $accountFactory,
            $listsFactory,
            $extraFactory,
            $egoi,
            $resultForwardFactory,
            $resultLayoutFactory
        );

        $this->_coreDate = $dateTime;
        $this->_dateFilter = $dateFilter;
        $this->_scopeConfig = $scopeConfigInterface;
        $this->_productFactory = $productFactory;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {

        parent::execute();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPostValue()) {

            $model = $this->_listsFactory->create()->getList();

            try {
                $extra = [];

                foreach ($data as $key => $element) {
                    if (stripos($key, 'extra_') !== false) {
                        $extra[$key] = $element;
                    }
                }
                $model->addData($data)->save();

                if ($data['listID']) {
                    $this->_extraFactory->create()->updateExtra($extra, 1);
                }

                $this->messageManager->addSuccessMessage(__('List was successfully saved'));
                $this->_getSession()->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_getSession()->setFormData($data);

                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        }

        return $resultRedirect->setRefererUrl();

    }

}
