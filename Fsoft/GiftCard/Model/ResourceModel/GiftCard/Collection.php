<?php

namespace Fsoft\GiftCard\Model\ResourceModel\GiftCard;

use Fsoft\GiftCard\Model\GiftCard as Model;
use Fsoft\GiftCard\Model\ResourceModel\GiftCard as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'giftcard_code_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
