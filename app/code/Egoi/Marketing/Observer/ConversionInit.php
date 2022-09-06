<?php

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class ConversionInit
 *
 * @package Egoi\Marketing\Observer
 */
class ConversionInit implements ObserverInterface
{

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata
     */
    protected $cookieMetadataFactory;

    /**
     * @var \Egoi\Marketing\Helper\Data
     */
    protected $egoiHelper;

    /**
     * @param \Egoi\Marketing\Helper\Data                           $egoiHelper
     * @param \Magento\Framework\Stdlib\CookieManagerInterface      $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata $publicCookieMetadata
     */
    public function __construct(
        \Egoi\Marketing\Helper\Data                           $egoiHelper,
        \Magento\Framework\Stdlib\CookieManagerInterface      $cookieManager,
        \Magento\Framework\Stdlib\Cookie\PublicCookieMetadata $publicCookieMetadata
    )
    {

        $this->egoiHelper = $egoiHelper;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $publicCookieMetadata;
    }

    /**
     * @param \Magento\Framework\Event\Observer $event
     */
    public function execute(\Magento\Framework\Event\Observer $event)
    {

        try {
            /** @var \Magento\Framework\App\RequestInterface $request */
            $request = $event->getEvent()->getRequest();

            if ($this->egoiHelper->isTrackAvailable() &&
                !$this->egoiHelper->getCustomerSession()->getData('checked_cookie') &&
                $this->egoiHelper->getCustomerSession()->getCustomerId()) {


                if (!$this->egoiHelper->getSubscriberCookie()) {
                    $this->egoiHelper->setCookieByEmail($this->egoiHelper->getCustomerEmail());
                }

                $this->egoiHelper->getCustomerSession()->setData('checked_cookie', true);

            }

            if ($request->getParam('utm_source') == 'e-goi') {
                $metadata = $this->cookieMetadataFactory->setDuration(3600 * 24 * 3)
                                                        ->setPath('/');

                $this->cookieManager->setPublicCookie(
                    \Egoi\Marketing\Model\Conversions::COOKIE_NAME,
                    json_encode($request->getParams()),
                    $metadata
                );
            }

        } catch (\Exception $e) {

        }
    }

}
