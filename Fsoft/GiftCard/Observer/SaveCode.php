<?php

namespace Fsoft\GiftCard\Observer;

use Fsoft\GiftCard\Model\GiftCardFactory;
use Fsoft\GiftCard\Model\HistoryFactory;
use Fsoft\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Math\Random;
use Magento\Store\Model\StoreManagerInterface;

class SaveCode implements \Magento\Framework\Event\ObserverInterface
{
    protected $historyFactory;
    protected $customerSession;
    protected $randomString;
    protected $giftCardFactory;
    protected $giftCardCollection;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        HistoryFactory $historyFactory,
        Session $customerSession,
        Random $randomString,
        GiftCardFactory $giftCardFactory,
        CollectionFactory $giftCardCollection,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->historyFactory = $historyFactory;
        $this->customerSession = $customerSession;
        $this->randomString = $randomString;
        $this->giftCardFactory = $giftCardFactory;
        $this->giftCardCollection = $giftCardCollection;
        $this->_storeManager = $storeManager;
    }
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        /** @var \Magento\Quote\Model\Quote $quote */
        /** @var \Magento\Sales\Model\Order $order */
        $giftcardcollection = $this->giftCardCollection->create();
        $customer_id = $this->customerSession->getCustomer()->getId();
        $increment_id = $order->getIncrementId();
        $order_id = !empty($order->getId()) ? $order->getId() : null;
        $quote_id = !empty($quote->getId()) ? $quote->getId() : null;
        $history = $this->historyFactory->create();
        $giftcard = $this->giftCardFactory->create();
        $random = $this->randomString->getRandomString(12);
        foreach ($order->getAllItems() as $item) {
            $giftcard_balance = $item->getProduct()->getResource()->getAttributeRawValue($item->getProduct()->getId(), 'giftcard_amount', $this->_storeManager->getStore()->getId());
            $order_created_at = $item->getData('created_at');
            if ($order_id && $quote_id) {
                foreach ($giftcardcollection->getItems() as $gcitem) {
                    $gc_id = $gcitem['giftcard_id'];
                    if ($gc_id) {
                        $history->addData([
                            'giftcard_id' => $gc_id,
                            'customer_id' => $customer_id,
                            'amount' => $giftcard_balance,
                            'action' => __('Created From #') . $increment_id,
                            'action_time' => $order_created_at
                        ])->save();
                    }
                }
                $giftcard->addData([
                    'code' => $random,
                    'balance' => $giftcard_balance,
                    'amount_used' => '1500',
                    'created_from' => __('Created From #') . $increment_id
                ])->save();
            }
        }
//        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->debug($increment_id);
    }
}
