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
class Domains
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Domains constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {

        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {

        $return = [];
        $return[] = ['value' => '', 'label' => __('-- None --')];

        $url = 'https://www51.e-goi.com/api/public/mail/domains';

        $data = [
            "apikey" => $this->_scopeConfig->getValue('egoi/info/api_key'),
        ];

        $data = \Zend_Json::encode($data);
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($output) {
                $result = \Zend_Json::decode($output);

                foreach ($result as $item) {

                    $return[] = ['value' => $item['domain'], 'label' => $item['domain']];
                }
            }
        } catch (\Exception $e) {
            $return = [];
        }

        return $return;
    }

}
