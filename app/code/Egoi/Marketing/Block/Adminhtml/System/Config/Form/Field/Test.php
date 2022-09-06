<?php

namespace Egoi\Marketing\Block\Adminhtml\System\Config\Form\Field;

/**
 * Class Rebuild
 *
 * @package Egoi\Marketing\Block\Adminhtml\System\Config\Form\Field
 */
class Test extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element
    )
    {

        $url = $this->getUrl('egoi/autoresponders/validate');

        return '<button  onclick="window.location=\'' . $url . 'number/\'+$F(\'egoi_test_number\')" class="scalable" type="button" ><span><span><span>' . __(
                'Test Now'
            ) . '</span></span></span></button>';
    }

}
