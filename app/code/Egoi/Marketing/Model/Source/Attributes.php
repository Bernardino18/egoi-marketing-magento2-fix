<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model\Source;

/**
 * Class Attributes
 *
 * @package Egoi\Marketing\Model\Source
 */
class Attributes
{

    /**
     * @var \Magento\Eav\Model\Entity\TypeFactory
     */
    protected $_typeFactory;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory
     */
    protected $_attributeCollection;

    /**
     * @param \Magento\Eav\Model\Entity\TypeFactory                               $typeFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $attributeCollection
     */
    public function __construct(
        \Magento\Eav\Model\Entity\TypeFactory                               $typeFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory $attributeCollection
    )
    {

        $this->_typeFactory = $typeFactory;
        $this->_attributeCollection = $attributeCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $type = $this->_typeFactory->create()->loadByCode('customer_address');
        $attributes = $this->_attributeCollection->create()->setEntityTypeFilter($type);
        $return = [];

        foreach ($attributes as $attribute) {
            if (strlen($attribute['frontend_label']) == 0) {
                continue;
            }
            $return[] = ['value' => $attribute['attribute_code'], 'label' => $attribute['frontend_label']];
        }

        return $return;
    }

}
