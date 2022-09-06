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
 * Class Method
 *
 * @package Egoi\Marketing\Model\Source
 */
class Method
{

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $return = [];
        $return[] = ['value' => 'transactional', 'label' => __('Transactional API')];
        $return[] = ['value' => 'campaign', 'label' => __('Campaign API')];

        return $return;
    }

}
