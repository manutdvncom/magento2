<?php

namespace Fsoft\GiftCard\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class GiftCard extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'giftcard_code_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('giftcard_code', 'giftcard_id');
    }
}
