<?php

namespace Fsoft\GiftCard\Controller\Adminhtml\Code;

use Magento\Backend\App\Action;
use Fsoft\GiftCard\Model\GiftCardFactory;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Backend\Model\Auth\Session;

class Save extends Action
{
    private $resultRedirect;
    private $giftCardFactory;
    private $authSession;

    public function __construct(
        Action\Context $context,
        GiftCardFactory $giftCardFactory,
        RedirectFactory $redirectFactory,
        Session $authSession
    )
    {
        parent::__construct($context);
        $this->giftCardFactory = $giftCardFactory;
        $this->resultRedirect = $redirectFactory;
        $this->authSession = $authSession;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $id = !empty($data['giftcard_id']) ? $data['giftcard_id'] : null;
        $adminName = $this->authSession->getUser()->getUserName();
        $newData = [
            'code' => $data['code'],
            'balance' => $data['balance'],
            'amount_used' => $data['amount_used'],
            'created_from' => $adminName
        ];

        $post = $this->giftCardFactory->create();
        if ($id) {
            $post->load($id);
            $this->getMessageManager()->addSuccessMessage(__('Edit thành công'));
        } else {
            $this->getMessageManager()->addSuccessMessage(__('Save thành công.'));
        }
        try{
            $post->addData($newData);
            $post->save();
            return $this->resultRedirect->create()->setPath('giftcard/code/index');
        }catch (\Exception $e){
            $this->getMessageManager()->addErrorMessage(__('Save thất bại.'));
        }
    }
}
