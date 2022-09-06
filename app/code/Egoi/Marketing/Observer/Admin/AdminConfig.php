<?php

namespace Egoi\Marketing\Observer\Admin;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AdminConfig
 *
 * @package Egoi\Marketing\Observer\Admin
 */
class AdminConfig implements ObserverInterface
{

    /**
     * @var \Egoi\marketing\Model\Egoi
     */
    protected $egoi;

    /**
     * @var
     */
    protected $scopeConfig;

    /**
     * AdminConfig constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scope
     * @param \Egoi\Marketing\Model\Egoi                         $egoi
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scope,
        \Egoi\Marketing\Model\Egoi                         $egoi
    )
    {

        $this->scopeConfig = $scope;
        $this->egoi = $egoi;
    }

    /**
     * @param \Magento\Framework\Event\Observer $event
     */
    public function execute(\Magento\Framework\Event\Observer $event)
    {

        if ($event->getEvent()
                  ->getName() == 'admin_system_config_changed_section_egoi') {

            $enabled = $this->scopeConfig->getValue('egoi/info/webpush');

            if ($enabled == 1) {
                $this->egoi->createWebPushSite();
            }
        }

    }

}
