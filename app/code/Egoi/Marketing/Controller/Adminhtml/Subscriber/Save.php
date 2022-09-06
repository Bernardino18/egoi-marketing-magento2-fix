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
 * Class Save
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Subscriber
 */
class Save extends \Egoi\Marketing\Controller\Adminhtml\Subscriber
{

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        parent::execute();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if data sent
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $model = $this->_coreRegistry->registry('egoi_subscriber');

            $id = $this->getRequest()->getParam('id');
            $model->setData($data);

            try {

                if ($id) {
                    $model->setId($id);
                }
                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the subscriber.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'id' => $model->getId(),
                        ]
                    );
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Subscriber.'));
            }

            $this->_getSession()->setFormData($data);

            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        }

        return $resultRedirect->setPath('*/*/');
    }

}
