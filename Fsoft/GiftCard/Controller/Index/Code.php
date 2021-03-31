<?php

namespace Fsoft\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\ResourceConnection;
use Fsoft\GiftCard\Model\ResourceModel\History\CollectionFactory;
class Code extends Action implements HttpGetActionInterface
{
    protected $joinFactory;
    protected $resourceConnection;
    protected $pageFactory;
    public function __construct(Context $context, PageFactory $pageFactory, ResourceConnection $resourceConnection, CollectionFactory $joinFactory)
    {
        $this->joinFactory = $joinFactory;
        $this->resourceConnection = $resourceConnection;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $customerTable = $this->resourceConnection->getTableName('customer_entity');
        $joinCollection = $this->joinFactory->create();
        $joinCollection
            ->join(
                ['ot' => $customerTable],
                "main_table.customer_id = ot.customer_id"
            );

        echo $joinCollection->getSelect();
        die;
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Gift Card'));
        $block = $resultPage->getLayout()->getBlock('my.giftcard');
        if ($block) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        return $resultPage;
    }
}
