<?php

namespace Fsoft\GiftCard\Observer;

use Magento\Framework\Event\Observer;
use Fsoft\GiftCard\Model\HistoryFactory;
use Fsoft\GiftCard\Model\GiftCardFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Math\Random;
class SaveCode implements \Magento\Framework\Event\ObserverInterface
{
    protected $historyFactory;
    protected $giftcardFactory;
    protected $customerSession;
    protected $randomString;
    public function __construct
    (
        HistoryFactory $historyFactory,
        GiftCardFactory $giftcardFactory,
        Session $customerSession,
        Random $randomString,
        array $data = []
    )
    {
        $this->historyFactory = $historyFactory;
        $this->giftcardFactory = $giftcardFactory;
        $this->customerSession = $customerSession;
        $this->randomString = $randomString;
    }
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();
        $customer_id = $this->customerSession->getCustomer()->getId();
        $history = $this->historyFactory->create();
        $giftcard = $this->giftcardFactory->create();
        $order_id = $order->getId();
        $quote_id = $quote->getId();
        $items = $order->getAllItems();
        $quote_item = $quote->getAllItems();
        foreach($items as $item){
            if ($order_id){
                $history->load($order_id);
                $newData = [
                    'giftcard_id' => $item->getData('order_id'),
                    'customer_id' => $customer_id,
                    'amount' => $item->getData('price'),
                    'action' => $item->getData('sku'),
                    'action_time' =>$item->getData('created_at')
                ];
                $history->addData($newData);
                $history->save();
            }
        }
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug('TTTTTTTTTTTTTT');
        $logger->debug('TTTTTTTTTTTTTT');
    }
}
