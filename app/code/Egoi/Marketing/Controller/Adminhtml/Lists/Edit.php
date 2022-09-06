<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Lists;

/**
 * Class Edit
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Lists
 */
class Edit extends \Egoi\Marketing\Controller\Adminhtml\Lists
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        parent::execute();

        $model = $this->_coreRegistry->registry('egoi_list');

        $extra = $this->_extraFactory->create()->getExtra();
        foreach ($extra as $item) {
            $model->setData($item->getData('extra_code'), $item->getData('attribute_code'));
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->messageManager->addNoticeMessage(
            'Add the following extra fields to your E-Goi list, to sync that information automatically: store_id, store_name, store_code.'
        );

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(__('Edit List'), __('Edit List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Lists'));
        $resultPage->getConfig()->getTitle()->prepend($model->getName());

        $resultPage->addContent($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Lists\Edit'))
                   ->addLeft($resultPage->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Lists\Edit\Tabs'));

        return $resultPage;
    }

}
