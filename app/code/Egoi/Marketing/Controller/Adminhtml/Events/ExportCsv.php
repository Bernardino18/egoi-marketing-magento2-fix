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
 * Class ExportCsv
 *
 * @package Egoi\Marketing\Controller\Adminhtml\Events
 */
class ExportCsv extends \Egoi\Marketing\Controller\Adminhtml\Events
{

    /**
     * Export subscribers grid to CSV format
     *
     * @return ResponseInterface
     */
    public function execute()
    {

        parent::execute();
        $resultLayout = $this->_layoutFactory->create();
        $fileName = 'events.csv';
        $content = $resultLayout->getLayout()->createBlock('Egoi\Marketing\Block\Adminhtml\Events\Grid')
                                ->getCsvFile();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
