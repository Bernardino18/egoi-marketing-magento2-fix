<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Block\Adminhtml;

/**
 * Class Events
 *
 * @package Egoi\Marketing\Block\Adminhtml
 */
class Account extends \Magento\Backend\Block\Template
{

    /**
     * @var \Egoi\Marketing\Model\AccountFactory
     */
    protected $_accountFactory;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $egoi;

    /**
     * Account constructor.
     *
     * @param \Egoi\Marketing\Model\Egoi                    $egoi
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Egoi\Marketing\Model\AccountFactory          $accountFactory
     * @param \Magento\Backend\Block\Widget\Context         $context
     * @param array                                         $data
     */
    public function __construct(
        \Egoi\Marketing\Model\Egoi                    $egoi,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Egoi\Marketing\Model\AccountFactory          $accountFactory,
        \Magento\Backend\Block\Widget\Context         $context, array $data = []
    )
    {

        $this->egoi = $egoi;
        $this->_moduleList = $moduleList;
        $this->_accountFactory = $accountFactory;

        parent::__construct($context, $data);
    }

    protected function _construct()
    {

        $result = $this->_accountFactory->create()->getAccount();

        $version = $this->_moduleList->getOne('Egoi_Marketing')['setup_version'];

        $this->setTitle(__('Account Details'));
        $this->setCompany($result);
        $this->setVersion($version);

        $this->egoi->checkEcommerceCatalogs();

        $this->setData('catalogs', $this->egoi->getActiveEcommerceCatalogs());

        $this->setTemplate('account/account.phtml');

    }
}
