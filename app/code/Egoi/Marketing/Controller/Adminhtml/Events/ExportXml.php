<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Controller\Adminhtml\Events;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ExportXml
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Events
 */
class ExportXml extends \Egoi\Marketing\Controller\Adminhtml\Events
{

    /**
     * Export subscribers grid to XML format
     *
     * @return ResponseInterface
     */
    public function execute()
    {

        parent::execute();
        $resultLayout = $this->_layoutFactory->create();
        $fileName = 'events.xml';
        $content = $resultLayout->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Events\Grid')
                                ->getExcelFile();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
