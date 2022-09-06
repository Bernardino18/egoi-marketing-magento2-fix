<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Events;

use Magento\Backend\App\Action;

/**
 * Class MassDelete
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Events
 */
class MassDelete extends \Egoi\Marketing\Controller\Adminhtml\Events
{

    /**
     * @var \Egoi\Marketing\Model\EventsFactory
     */
    protected $_eventsFactory;

    /**
     * @param Action\Context                                    $context
     * @param \Magento\Framework\View\Result\PageFactory        $resultPageFactory
     * @param \Magento\Framework\Registry                       $registry
     * @param \Egoi\Marketing\Model\AutorespondersFactory       $autorespondersFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory  $fileFactory
     * @param \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory
     * @param \Egoi\Marketing\Model\EventsFactory               $eventsFactory
     */
    public function __construct(
        Action\Context                                    $context,
        \Magento\Framework\View\Result\PageFactory        $resultPageFactory,
        \Magento\Framework\Registry                       $registry,
        \Egoi\Marketing\Model\AutorespondersFactory       $autorespondersFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\App\Response\Http\FileFactory  $fileFactory,
        \Magento\Framework\View\Result\LayoutFactory      $resultLayoutFactory,
        \Egoi\Marketing\Model\EventsFactory               $eventsFactory
    )
    {

        parent::__construct(
            $context,
            $resultPageFactory,
            $registry,
            $autorespondersFactory,
            $resultForwardFactory,
            $fileFactory,
            $resultLayoutFactory
        );

        $this->_eventsFactory = $eventsFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {

        parent::execute();
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectInterface = $this->_redirect;
        $subscribersIds = $this->getRequest()->getParam('events');

        if (!is_array($subscribersIds)) {
            $this->messageManager->addErrorMessage(__('Please select one or more events.'));
        } else {
            try {
                foreach ($subscribersIds as $record) {
                    $delete = $this->_eventsFactory->create()->load($record);

                    $id = $delete->getAutoresponderId();
                    if ($delete->getSent() == 1) {
                        $ar = $this->_autorespondersFactory->create()->load($id);
                        $ar->setData('number_subscribers', $ar->getData('number_subscribers') - 1)->save();
                    }
                    $delete->delete();
                }
                $this->messageManager->addSuccessMessage(
                    __(
                        'Total of %1 record(s) were deleted.',
                        count($subscribersIds)
                    )
                );
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting the events.'));
            }
        }

        return $resultRedirect->setPath($redirectInterface->getRefererUrl());
    }
}
