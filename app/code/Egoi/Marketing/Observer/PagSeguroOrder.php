<?php
/**
 *
 * Licentia, Unipessoal LDA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://licentia.pt/magento-license.txt
 *
 * @title      Licentia Panda - Magento® Sales Automation Extension
 * @package    Licentia
 * @author     Bento Vilas Boas <bento@licentia.pt>
 * @copyright  Copyright (c) 2012-2017 Licentia - https://licentia.pt
 * @license    https://licentia.pt/magento-license.txt
 *
 */

namespace Egoi\Marketing\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class PagSeguroOrder
 *
 * @package Egoi\Marketing\Observer
 */
class PagSeguroOrder implements ObserverInterface
{

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $_autorespondersFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_cutomerSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Framework\Session\SessionManager
     */
    protected $sessionManager;

    /**
     * PagSeguroOrder constructor.
     *
     * @param \Magento\Customer\Model\Session             $session
     * @param \Magento\Sales\Model\OrderFactory           $orderFactory
     * @param \Egoi\Marketing\Model\AutorespondersFactory $autorespondersFactory
     */
    function __construct(
        \Magento\Framework\Session\SessionManager   $sessionManager,
        \Magento\Customer\Model\Session             $session,
        \Magento\Sales\Model\OrderFactory           $orderFactory,
        \Egoi\Marketing\Model\AutorespondersFactory $autorespondersFactory
    )
    {

        $this->sessionManager = $sessionManager;
        $this->orderFactory = $orderFactory;
        $this->_cutomerSession = $session;
        $this->_autorespondersFactory = $autorespondersFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $event
     */
    public function execute(\Magento\Framework\Event\Observer $event)
    {

        try {

            $redirect = $this->session()->payment_link;

            $orderId = $this->_cutomerSession->getData('egoi_pagseguro');
            $order = $this->orderFactory->create()->load($orderId);

            if ($order->getId()) {
                $this->_autorespondersFactory->create()->newOrder($order, $redirect);
                $this->_cutomerSession->setData('egoi_pagseguro', null);
            }

        } catch (\Exception $e) {

        }
    }

    /**
     * Get session
     *
     * @return object
     */
    private function session()
    {

        return (object) $this->sessionManager->getData('pagseguro_payment');
    }

}
