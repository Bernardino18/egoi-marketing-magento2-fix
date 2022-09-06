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
 * Class WebsitePush
 *
 * @package Egoi\Marketing\Model\Source
 */
class WebsitePush
{

    /**
     * @var \Egoi\marketing\Model\Egoi
     */
    protected $egoi;

    /**
     * WebsitePush constructor.
     *
     * @param \Egoi\Marketing\Model\Egoi $egoi
     */
    public function __construct(\Egoi\Marketing\Model\Egoi $egoi
    )
    {

        $this->egoi = $egoi;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $return = [];

        $sites = $this->egoi->getWebPushSiteList();
        $return[] = [
            'value' => 0,
            'label' => __('No'),
        ];
        $return[] = [
            'value' => 1,
            'label' => __('Yes - Create New Website Automatically'),
        ];
        foreach ($sites as $site) {

            $return[] = [
                'value' => $site['app_code'],
                'label' => $site['name'],
            ];
        }

        return $return;
    }

}
