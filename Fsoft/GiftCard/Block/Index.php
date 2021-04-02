<?php

namespace Fsoft\GiftCard\Block;

use Fsoft\GiftCard\Model\ResourceModel\History\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;

class Index extends Template
{
    protected $collection;
    protected $resourceConnection;
    protected $customerSession;
    public function __construct(
        Template\Context $context,
        CollectionFactory $collection,
        ResourceConnection $resourceConnection,
        Session $customerSession,
        array $data = []
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->collection = $collection;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }
    public function getList()
    {
        $customerTable = $this->resourceConnection->getTableName('customer_entity');
        $giftcardTable = $this->resourceConnection->getTableName('giftcard_code');
        $customer_id = $this->customerSession->getCustomer()->getId();
        $joinCollection = $this->collection->create();
        $joinCollection
            ->getSelect()
            ->join(
                ['gc' => $giftcardTable],
                "main_table.giftcard_id = gc.giftcard_id",
                [
                'action_time' => 'main_table.action_time',
                'code' => 'gc.code',
                'balance' => 'gc.balance',
                'action' => 'main_table.action'
            ]
            )
            ->join(
                ['ce' => $customerTable],
                "main_table.customer_id = ce.entity_id",
                [
                    'customer_id' => 'ce.entity_id'
                ]
            )
            ->where("customer_id=" . $customer_id);
        return $joinCollection;
    }
}
