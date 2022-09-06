<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Autoresponders;

/**
 * Class Edit
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Autoresponders
 */
class Edit extends \Egoi\Marketing\Controller\Adminhtml\Autoresponders
{

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
        $resultPage->setActiveMenu('Egoi_Marketing::autoresponders')
                   ->addBreadcrumb(__('Multi Channel Marketing'), __('Multi Channel Marketing'))
                   ->addBreadcrumb(__('Manage Autoresponders'), __('Manage Autoresponders'));

        return $resultPage;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {

        parent::execute();
        $id = $this->getRequest()->getParam('id');
        $model = $this->_coreRegistry->registry('egoi_autoresponder');

        if ($id) {
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Autoresponder no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }

        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        if (!$model->getData('store_id')) {
            $model->setData('store_id', '0');
        }
        $model->setData("store_id", explode(',', $model->getData('store_id')));
        $model->setData("payment_method", explode(',', $model->getData('payment_method')));
        $model->setData("shipping_method", explode(',', $model->getData('shipping_method')));

        if (!$model->getData('customer_groups')) {
            $model->setData('customer_groups', '10000');
        }
        $model->setData("customer_groups", explode(',', $model->getData('customer_groups')));

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Autoresponder') : __('New Autoresponder'),
            $id ? __('Edit Autoresponder') : __('New Autoresponder')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Autoresponders'));
        $resultPage->getConfig()->getTitle()
                   ->prepend($model->getId() ? $model->getName() : __('New Autoresponder'));

        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Autoresponders\Edit')
        )
                   ->addLeft(
                       $resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Autoresponders\Edit\Tabs')
                   );

        return $resultPage;
    }

}
