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
 * Class Delete
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Subscriber
 */
class Delete extends \Egoi\Marketing\Controller\Adminhtml\Subscriber
{

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
        $model = $this->_coreRegistry->registry('egoi_subscriber');

        if ($model->getId()) {
            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the subscriber.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while deleting the Subscriber.')
                );
            }

            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        } else {
            $this->messageManager->addErrorMessage(__('We can\'t find a subscriber to delete.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
