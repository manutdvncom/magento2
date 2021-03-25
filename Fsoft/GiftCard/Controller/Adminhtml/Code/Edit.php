<?php
namespace Fsoft\GiftCard\Controller\Adminhtml\Code;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Fsoft\GiftCard\Model\GiftCardFactory;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Registry;

class Edit extends \Magento\Backend\App\Action
{
    protected $giftCardFactory;
    protected $resultRedirect;
    protected $resultFactory;
    protected $registry;
    public function __construct(Context $context, GiftCardFactory $giftCardFactory, RedirectFactory $redirectFactory, Registry $registry, ResultFactory $resultFactory)
    {
        parent::__construct($context);
        $this->giftCardFactory = $giftCardFactory;
        $this->resultRedirect = $redirectFactory;
        $this->registry = $registry;
        $this->resultFactory = $resultFactory;
    }
    protected function _initAction()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $data = $this->giftCardFactory->create();
//        echo "<pre>";var_dump($id); echo "</pre>"; die;
        if ($id){
            $data->load($id);
            if(!$data->getId()){
                $this->getMessageManager()->addErrorMessage(__('This page no longer exists!!!'));
                $resultRedirect = $this->resultRedirect->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->registry->register('giftcard',$data);
        $resultPage = $this->_initAction();
        return $resultPage->getConfig()->getTitle()->prepend($data->getId() ? $data->getCode() : __('HelloWorld'));
    }
}
