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
class Lists extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'egoi_lists';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'lists';

    /**
     * @var Egoi
     */
    protected $_egoi;

    /**
     * @var ExtraFactory
     */
    protected $_extraFactory;

    /**
     * @var ResourceModel\Extra\CollectionFactory
     */
    protected $_extraCollection;

    /**
     * @var ResourceModel\Lists\CollectionFactory
     */
    protected $_listsCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {

        $this->_init('Egoi\Marketing\Model\ResourceModel\Lists');
    }

    /**
     * Lists constructor.
     *
     * @param Egoi                                                         $egoi
     * @param \Magento\Store\Model\StoreManagerInterface                   $store
     * @param ExtraFactory                                                 $extraFactory
     * @param ResourceModel\Extra\CollectionFactory                        $extraCollection
     * @param ResourceModel\Lists\CollectionFactory                        $listsCollection
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Egoi\Marketing\Model\Egoi                                  $egoi,
        \Magento\Store\Model\StoreManagerInterface                  $store,
        \Egoi\Marketing\Model\ExtraFactory                          $extraFactory,
        \Egoi\Marketing\Model\ResourceModel\Extra\CollectionFactory $extraCollection,
        \Egoi\Marketing\Model\ResourceModel\Lists\CollectionFactory $listsCollection,
        \Magento\Framework\Model\Context                            $context,
        \Magento\Framework\Registry                                 $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource     $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb               $resourceCollection = null,
        array                                                       $data = []
    )
    {

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->_storeManager = $store;
        $this->_egoi = $egoi;
        $this->_extraFactory = $extraFactory;
        $this->_extraCollection = $extraCollection;
        $this->_listsCollection = $listsCollection;
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Exception
     */
    public function save()
    {

        $model = $this->_egoi;
        $data = $this->getData();

        $this->setData('canal_email', '1');
        $this->setData('canal_sms', '1');

        $egoi = $this->_egoi->getLists();
        foreach ($egoi->getData() as $list) {

            if (isset($list['extra_fields']) && is_array($list['extra_fields'])) {
                $i = 0;
                foreach ($list['extra_fields'] as $field) {
                    if (isset($field['ref']) && $field['ref'] == 'store_ud') {
                        $i++;
                    }
                    if ($i == 1) {
                        $this->setData('listnum', $list['listnum']);
                        break 2;
                    }
                }
            }
        }

        if (!$this->getData('listID') && $this->getData('listnum')) {
            $this->setData('listID', $this->getData('listnum'));
            $data['listID'] = $this->getData('listnum');
        }

        $total = $this->_listsCollection->create()->getFirstItem();

        if ($total->getId()) {
            $this->setId($total->getId());
        }

        if ($this->getData('listnum')) {

            if (isset($data['nome'])) {
                $data['name'] = $data['nome'];
            }
            $data['title'] = $data['nome'];
            if (isset($data['nome'])) {
                $this->setData('title', $data['nome']);
            }
            $model->addData($data);
            $model->updateList();
        } else {

            $model->setData($data);
            $model->createList();
            $this->setData('listnum', $model->getData('list_id'));
            $this->setData('title', $data['nome']);
        }

        return parent::save();

    }

    /**
     * @param bool $forceFields
     *
     * @return Lists
     * @throws \Exception
     */
    public function getList($forceFields = false)
    {

        $result = $this->_listsCollection->create()->getFirstItem();

        if (!$result->getId()) {
            $data = [];
            $data['nome'] = 'General';
            $data['title'] = 'General';
            $data['name'] = 'General';
            $data['internal_name'] = '[Magento List]';
            $this->setData($data)->save();

            $result = $this;
        }
        if ($forceFields) {

            $extra = $this->_egoi->setData(['listID' => $this->getData('listnum')])
                                 ->getLists();

            $addExtra = true;
            $idMagentoStoreId = 0;
            foreach ($extra->getData() as $list) {
                if (isset($list['extra_fields']) && is_array($list['extra_fields'])) {
                    foreach ($list['extra_fields'] as $field) {
                        if (isset($field['ref']) && $field['ref'] == 'store_id') {
                            $idMagentoStoreId = $field['id'];
                            $addExtra = false;
                            break 2;
                        }
                    }
                }
            }

            if ($addExtra) {
                $this->_extraFactory->create()->addInitialFields($result->getData('listnum'));
            } else {

                $existsStoreId = $this->_extraCollection->create()
                                                        ->addFieldToFilter('attribute_code', 'store_id')
                                                        ->getFirstItem();

                if (!$existsStoreId->getId()) {
                    $this->_extraFactory->create()
                                        ->setData(
                                            [
                                                'extra_code'     => 'extra_' . $idMagentoStoreId,
                                                'attribute_code' => 'store_id',
                                            ]
                                        )->save();
                }

            }

        }

        return $result;
    }
}
