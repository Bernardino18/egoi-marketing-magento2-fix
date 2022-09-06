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

namespace Egoi\Marketing\Cron;

/**
 * Class SyncCatalog
 *
 * @package Egoi\Marketing\Cron
 */
class SyncCatalog
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $systemStore;

    /**
     * @var \Egoi\Marketing\Model\Egoi
     */
    protected $egoi;

    /**
     * SyncCatalog constructor.
     *
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Egoi\Marketing\Model\Egoi        $egoi
     */
    public function __construct(
        \Magento\Store\Model\System\Store $systemStore,
        \Egoi\Marketing\Model\Egoi        $egoi
    )
    {

        $this->systemStore = $systemStore;
        $this->egoi = $egoi;
    }

    /**
     */
    public function execute()
    {

        foreach ($this->systemStore->getStoreOptionHash() as $storeId => $storeName) {

            $this->egoi->buildProductsFeed($storeId);

        }

    }

}

