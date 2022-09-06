<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Egoi\Marketing\Ui\Component\Listing\Column\Autoresponders;

use Magento\Framework\Data\OptionSourceInterface;
use Egoi\Marketing\Model\AutorespondersFactory;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $autorespondersFactory;

    /**
     * Options constructor.
     *
     * @param AutorespondersFactory $autorespondersFactory
     */
    public function __construct(AutorespondersFactory $autorespondersFactory)
    {

        $this->autorespondersFactory = $autorespondersFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {

        if ($this->options === null) {
            $result = $this->autorespondersFactory->create()->toOptionValues();

            $this->options = $result;
        }

        return $this->options;
    }
}
