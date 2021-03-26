<?php

namespace Fsoft\GiftCard\Block;

use Magento\Framework\View\Element\Template;
use Fsoft\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;
class Index extends Template
{
    protected $collection;
    public function __construct(Template\Context $context, CollectionFactory $collection, array $data = [])
    {
        $this->collection = $collection;
        parent::__construct($context, $data);
    }
    public function getList(){
        $data = $this->collection->create();
        return $data->getItems();
    }
}
