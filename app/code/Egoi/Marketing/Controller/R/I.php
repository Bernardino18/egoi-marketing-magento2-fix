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

namespace Egoi\Marketing\Controller\R;

/**
 * Class I
 *
 * @package Egoi\Marketing\Controller\Products
 */
class I extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Egoi\Marketing\Model\AutorespondersFactory
     */
    protected $_autoresponders;

    /**
     * I constructor.
     *
     * @param \Egoi\Marketing\Model\AutorespondersFactory $autorespondersFactory
     * @param \Magento\Framework\App\Action\Context       $context
     */
    public function __construct(
        \Egoi\Marketing\Model\AutorespondersFactory $autorespondersFactory,
        \Magento\Framework\App\Action\Context       $context
    )
    {

        $this->_autoresponders = $autorespondersFactory;
        parent::__construct($context);
    }

    /**
     *
     */
    public function execute()
    {

        $url = $this->_autoresponders->create()
                                     ->getRedirect($this->getRequest()->getParam('c'));

        if ($url) {
            header('LOCATION: ' . $url);
            die();
        }

        $this->messageManager->addErrorMessage(__('Ligação inválida'));
        header('LOCATION: /');
        die();
    }

}
