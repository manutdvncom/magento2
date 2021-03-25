<?php

namespace Fsoft\GiftCard\Model;

use Fsoft\GiftCard\Model\ResourceModel\GiftCard as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class GiftCard extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'giftcard_code_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
