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
use Magento\Customer\Model\Session;
class Code extends Action implements HttpGetActionInterface
{
    protected $joinFactory;
    protected $resourceConnection;
    protected $pageFactory;
    protected $customerSession;
    public function __construct(Context $context, PageFactory $pageFactory, ResourceConnection $resourceConnection, CollectionFactory $joinFactory, Session $customerSession)
    {
        $this->joinFactory = $joinFactory;
        $this->resourceConnection = $resourceConnection;
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Gift Card'));
        $block = $resultPage->getLayout()->getBlock('my.giftcard');
        if ($block) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        return $resultPage;
    }
}
