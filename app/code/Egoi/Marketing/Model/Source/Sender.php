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
 * Class Sender
 *
 * @package Egoi\Marketing\Model\Source
 */
class Sender
{

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $_egoi;

    /**
     * Sender constructor.
     *
     * @param \Egoi\Marketing\Model\Egoi $egoi
     */
    public function __construct(
        \Egoi\Marketing\Model\Egoi $egoi
    )
    {

        $this->_egoi = $egoi;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $attributes = $this->_egoi->getSenders();
        $return = [];

        foreach ($attributes->getData() as $attribute) {

            if (!isset($attribute['fromid'])) {
                continue;
            }
            $return[] = ['value' => $attribute['fromid'], 'label' => $attribute['sender']];
        }

        return $return;
    }

}
