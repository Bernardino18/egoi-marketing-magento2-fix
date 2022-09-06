<?php

/**
 * E-goi.com
 *
 * @title      E-Goi Multi-channel Marketing
 * @package    E-Goi
 * @copyright  Copyright (c) 2012-2018 E-Goi - http://e-goi.com
 */

namespace Egoi\Marketing\Model;

/**
 * Class Autoresponders
 *
 * @package Egoi\Marketing\Model
 */
class Extra extends \Magento\Framework\Model\AbstractModel
{

    const AUTO_MAPPING = ['store_id', 'store_name', 'store_code'];

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'egoi_extra';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'extra';

    /**
     * @var Egoi
     */
    protected $_egoi;

    /**
     * @var ResourceModel\Extra\CollectionFactory
     */
    protected $_extraCollection;

    /**
     * @var ListsFactory
     */
    protected $_listsFactory;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('Egoi\Marketing\Model\ResourceModel\Extra');
    }

    /**
     * Extra constructor.
     *
     * @param ListsFactory                                                 $listsFactory
     * @param Egoi                                                         $egoi
     * @param ResourceModel\Extra\CollectionFactory                        $extraCollection
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     *
     * @internal param ExtraFactory $extraFactory
     */
    public function __construct(
        \Egoi\Marketing\Model\ListsFactory                          $listsFactory,
        \Egoi\Marketing\Model\Egoi                                  $egoi,
        \Egoi\Marketing\Model\ResourceModel\Extra\CollectionFactory $extraCollection,
        \Magento\Framework\Model\Context                            $context,
        \Magento\Framework\Registry                                 $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource     $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb               $resourceCollection = null,
        array                                                       $data = []
    )
    {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->_egoi = $egoi;
        $this->_listsFactory = $listsFactory;
        $this->_extraCollection = $extraCollection;
    }

    /**
     * @param $list
     */
    public function addInitialFields($list = false)
    {

        $this->addField('store_id', $list);
    }

    /**
     * @param      $name
     * @param bool $list
     *
     * @return bool|Extra
     */
    public function addField($name, $list = false)
    {

        if (!$list) {
            $list = $this->_listsFactory->create()->getList()->getData('listnum');
        }

        $extra = $this->_egoi
            ->setData(['listID' => $list])
            ->getLists();

        foreach ($extra->getData() as $extraField) {
            if (isset($extraField['extra_fields']) && is_array($extraField['extra_fields'])) {
                foreach ($extraField['extra_fields'] as $field) {
                    if (isset($field['ref']) && $field['ref'] == $name) {
                        return true;
                    }
                }
            }
        }

        $data = ['listID' => $list, 'name' => $name];
        $result = $this->_egoi->setData($data)->addExtraField();

        return $this->setData(
            [
                'extra_code'     => 'extra_' . $result->getData('new_id'),
                'attribute_code' => $name,
                'system'         => 1,
            ]
        )
                    ->save();

    }

    /**
     * @param     $data
     * @param int $system
     */
    public function updateExtra($data, $system = 0)
    {

        $collection = $this->_extraCollection->create();

        foreach ($collection as $item) {
            $item->delete();
        }

        foreach ($data as $key => $value) {

            if ($value == '0') {
                continue;
            }

            $new = [];
            $new['attribute_code'] = $value;
            $new['extra_code'] = $key;
            $new['system'] = $system;

            $this->setData($new)->save();
        }
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {

        return $this->_extraCollection->create();
    }

}
