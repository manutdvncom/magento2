<?php

namespace Fsoft\GiftCard\Model\ResourceModel\History;

use Fsoft\GiftCard\Model\History as Model;
use Fsoft\GiftCard\Model\ResourceModel\History as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'giftcard_history_collection';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
