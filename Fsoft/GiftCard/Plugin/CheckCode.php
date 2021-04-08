<?php

namespace Fsoft\GiftCard\Plugin;

use Magento\Customer\Model\Session;

class CheckCode
{
    protected $customerSession;
    protected $couponPlugin;

    public function __construct(
        Session $customerSession,
        CouponPostPlugin $couponPlugin
    ) {
        $this->customerSession = $customerSession;
        $this->couponPlugin = $couponPlugin;
    }
    public function beforeSet(\Magento\Quote\Model\CouponManagement $subject, $cartId, $couponCode)
    {
        $customer_id = $this->customerSession->getCustomer()->getId();
        $data = $this->couponPlugin->getGiftCardCode();
        $quote = $subject->quoteRepository->getActive($cartId);
        foreach ($data->getData() as $item) {
            if ($couponCode == $item['code'] && $customer_id == $item['customer_id']) {
                $quote->setCouponCode($couponCode)->collectTotals()->save();
                $subject->quoteRepository->save($quote);
                $test = $quote->getCouponCode();
                $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
                $logger = new \Zend\Log\Logger();
                $logger->addWriter($writer);
                $logger->debug($couponCode);
                $logger->debug($item);
                return [$cartId,$test];
            }
        }

//        return [$cartId,$quote->getCouponCode()];
    }
}
