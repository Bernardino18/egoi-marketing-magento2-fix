<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Subscriber;

/**
 * Class Edit
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Subscriber
 */
class Edit extends \Egoi\Marketing\Controller\Adminhtml\Subscriber
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {

        parent::execute();
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_coreRegistry->registry('egoi_subscriber');

        // 2. Initial checking
        if ($id) {
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This subscriber no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        // 3. Set entered data if was error when we do save
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Subscriber') : __('New Subscriber'),
            $id ? __('Edit Subscriber') : __('New Subscriber')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Subscribers'));
        $resultPage->getConfig()->getTitle()
                   ->prepend($model->getId() ? $model->getName() : __('New Subscriber'));

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Subscriber\Edit'))
                   ->addLeft(
                       $resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Subscriber\Edit\Tabs')
                   );

        return $resultPage;
    }

}
