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
 * Class Catalogs
 *
 * @package Egoi\Marketing\Model\Source
 */
class Catalogs
{

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $egoi;

    /**
     * Catalogs constructor.
     *
     * @param \Egoi\Marketing\Model\Egoi $egoi
     */
    public function __construct(
        \Egoi\Marketing\Model\Egoi $egoi
    )
    {

        $this->egoi = $egoi;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        return $this->egoi->getEcommerceCatalogs();
    }

}
