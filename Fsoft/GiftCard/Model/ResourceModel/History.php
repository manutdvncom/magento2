<?php

namespace Fsoft\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class History extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'giftcard_history_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('giftcard_history', 'history_id');
    }
}
