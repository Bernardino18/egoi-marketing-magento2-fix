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

/**
 * Class Save
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Autoresponders
 */
class Save extends \Egoi\Marketing\Controller\Adminhtml\Autoresponders
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
     * @param Action\Context                                     $context
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Magento\Framework\Registry                        $registry
     * @param \Magento\Catalog\Model\ProductFactory              $productFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date     $dateFilter
     * @param \Magento\Framework\Stdlib\DateTime\DateTime        $dateTime
     * @param \Egoi\Marketing\Model\AutorespondersFactory        $autorespondersFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
     * @param \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory
     * @param \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
     */
    public function __construct(
        Action\Context                                     $context,
        \Magento\Framework\View\Result\PageFactory         $resultPageFactory,
        \Magento\Framework\Registry                        $registry,
        \Magento\Catalog\Model\ProductFactory              $productFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date     $dateFilter,
        \Magento\Framework\Stdlib\DateTime\DateTime        $dateTime,
        \Egoi\Marketing\Model\AutorespondersFactory        $autorespondersFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Backend\Model\View\Result\ForwardFactory  $resultForwardFactory,
        \Magento\Framework\View\Result\LayoutFactory       $resultLayoutFactory
    )
    {

        parent::__construct(
            $context,
            $resultPageFactory,
            $registry,
            $autorespondersFactory,
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
        // check if data sent
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('id');

            $model = $this->_coreRegistry->registry('egoi_autoresponder');

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Autoresponder no longer exists.'));

                return $resultRedirect->setPath('*/*/');
            }

            $inputFilter = new \Zend_Filter_Input(
                ['to_date' => $this->_dateFilter, 'from_date' => $this->_dateFilter],
                [],
                $data
            );
            $data = $inputFilter->getUnescaped();

            if (!isset($data['store_ids'])) {
                $data['store_ids'] = [0];
            }
            if (array_search(0, $data['store_ids']) !== false) {
                $data['store_ids'] = [];
            }
            $data['store_ids'] = implode(',', $data['store_ids']);

            if (!isset($data['payment_method'])) {
                $data['payment_method'] = [];
            }
            $data['payment_method'] = implode(',', $data['payment_method']);

            if (!isset($data['shipping_method'])) {
                $data['shipping_method'] = [];
            }
            $data['shipping_method'] = implode(',', $data['shipping_method']);

            if (array_search(10000, $data['customer_groups']) !== false) {
                $data['customer_groups'] = [];
            }
            $data['customer_groups'] = implode(',', $data['customer_groups']);

            $model->setData('controller', true);

            // try to save it
            try {

                $model->addData($data);
                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the Autoresponder.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'id'     => $model->getId(),
                            'tab_id' => $this->getRequest()->getParam('active_tab'),
                        ]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the Autoresponder.')
                );
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'id'     => $model->getId(),
                    'tab_id' => $this->getRequest()->getParam('active_tab'),
                ]
            );

        }

        return $resultRedirect->setPath('*/*/');

    }

}
