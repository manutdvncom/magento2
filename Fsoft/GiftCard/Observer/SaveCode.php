<?php

namespace Fsoft\GiftCard\Observer;

use Fsoft\GiftCard\Model\GiftCardFactory;
use Fsoft\GiftCard\Model\HistoryFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Math\Random;

class SaveCode implements \Magento\Framework\Event\ObserverInterface
{
    protected $historyFactory;
    protected $customerSession;
    protected $randomString;
    protected $giftCardFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        HistoryFactory $historyFactory,
        Session $customerSession,
        Random $randomString,
        GiftCardFactory $giftCardFactory,
        array $data = []
    ) {
        $this->historyFactory = $historyFactory;
        $this->customerSession = $customerSession;
        $this->randomString = $randomString;
        $this->giftCardFactory = $giftCardFactory;
    }
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        $customer_id = $this->customerSession->getCustomer()->getId();
        $increment_id = $order->getIncrementId();
        $order_id = !empty($order->getData('order_id')) ? $order->getData('order_id') : null;
        $history = $this->historyFactory->create();
        $giftcard = $this->giftCardFactory->create();
        $random = $this->randomString->getRandomString(12);
        foreach ($order->getAllItems() as $item) {
            if ($order_id) {
                $history->load($order_id);
                $history->addData([
                    'giftcard_id' => $order_id,
                    'customer_id' => $customer_id,
                    'amount' => $item->getData('price'),
                    'action' => $item->getData('sku'),
                    'action_time' => $item->getData('created_at')
                ])->save();
            }
        }

        foreach ($quote->getAllItems() as $qitem) {
            $quoteItems[$qitem->getId()] = $qitem;
        }
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug($increment_id);
        $logger->debug($quote->get());
        return $this;
    }
}
