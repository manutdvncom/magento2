<?php

namespace Fsoft\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;
class Code extends Action implements HttpGetActionInterface
{
    protected $pageFactory;
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
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
