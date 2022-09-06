<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml\System\Config\Form\Field;

/**
 * Class TestTransactional
 *
 * @package Egoi\Marketing\Block\Adminhtml\System\Config\Form\Field
 */
class TestTransactional extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element
    )
    {

        $url = $this->getUrl('egoi/account/transactional');

        return '<button onclick="window.location=\'' . $url . '\'" class="scalable" type="button" ><span><span><span>' . __(
                'Test Now'
            ) . '</span></span></span></button>';
    }

}
