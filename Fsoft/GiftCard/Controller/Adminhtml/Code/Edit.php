<?php
namespace Fsoft\GiftCard\Controller\Adminhtml\Code;

use Fsoft\GiftCard\Model\GiftCardFactory;
use Fsoft\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    protected $giftCardFactory;
    protected $resultRedirect;
    protected $collection;
    protected $registry;
    protected $pageFactory;
    public function __construct(
        Context $context,
        GiftCardFactory $giftCardFactory,
        RedirectFactory $redirectFactory,
        Registry $registry,
        CollectionFactory $collection,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->giftCardFactory = $giftCardFactory;
        $this->resultRedirect = $redirectFactory;
        $this->registry = $registry;
        $this->collection = $collection;
        $this->pageFactory = $pageFactory;
    }
    protected function _initAction()
    {
        $resultPage = $this->pageFactory->create();
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $data = $this->giftCardFactory->create();
//        echo "<pre>";print_r($items['code']); echo "</pre>"; die;
        if ($id) {
            $data->load($id);
            if (!$data->getId()) {
                $this->getMessageManager()->addErrorMessage(__('This page no longer exists!!!'));
                $resultRedirect = $this->resultRedirect->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
//        $this->registry->register('giftcard_code_model', $data);
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend($data->getId() ? __('Code #') . $data->getCode() : __('HelloWorld'));
        return $resultPage;
    }
}
