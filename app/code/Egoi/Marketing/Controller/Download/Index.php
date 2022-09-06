<?php

namespace Egoi\Marketing\Controller\Download;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Index
 *
 * @package Egoi\Marketing\Controller\Callback
 */
class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Filesystem                    $filesystem
     * @param \Magento\Newsletter\Model\SubscriberFactory      $subscriberFactory
     * @param \Magento\Framework\App\Action\Context            $context
     */
    public function __construct(
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem                    $filesystem,
        \Magento\Newsletter\Model\SubscriberFactory      $subscriberFactory,
        \Magento\Framework\App\Action\Context            $context
    )
    {

        $this->_subscriberFactory = $subscriberFactory;
        $this->_fileSystem = $filesystem;
        $this->_fileFactory = $fileFactory;

        parent::__construct($context);
    }

    /**
     *
     */
    public function execute()
    {

        $tmpDir = $this->_fileSystem->getDirectoryWrite(DirectoryList::TMP)->getAbsolutePath();
        $token = file_get_contents($tmpDir . 'egoi_token.txt');

        if ($token != $this->getRequest()->getParam('token')) {

            $this->messageManager->addErrorMessage(__('Invalid token'));

            return $this->_redirect('/');
        }

        return $this->_fileFactory->create(
            'egoi_export.csv',
            file_get_contents($tmpDir . '/egoi_export.csv'),
            DirectoryList::VAR_DIR
        );

    }

}
