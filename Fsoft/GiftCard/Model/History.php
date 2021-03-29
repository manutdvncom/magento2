<?php

namespace Fsoft\GiftCard\Model;

use Fsoft\GiftCard\Model\ResourceModel\History as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class History extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'giftcard_history_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
