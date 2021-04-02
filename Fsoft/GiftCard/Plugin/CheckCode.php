<?php

namespace Fsoft\GiftCard\Plugin;

use Fsoft\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckCode
{
    private $giftcardCollectionFactory;
    public function __construct(
        CollectionFactory $giftcardCollectionFactory
    ) {
        $this->giftcardCollectionFactory = $giftcardCollectionFactory;
    }
    public function beforeSet(\Magento\Quote\Model\CouponManagement $subject, $cartId, $couponCode)
    {
        $quote = $subject->quoteRepository->getActive($cartId);
        $collection = $this->giftcardCollectionFactory->create()
            ->addFieldToSelect('code');
        $code = $collection->getData();
        foreach ($code as $item){
            if ($quote) {
                if ($couponCode == $item['code']) {
                    throw new NoSuchEntityException(__("Hello."));
                }
            }
        }
    }

    public function afterSet(\Magento\Quote\Model\CouponManagement $subject, $cartId, $couponCode)
    {
//        $quote = $subject->quoteRepository->getActive($cartId);
//        $collection = $this->giftcardCollectionFactory->create()
//            ->addFieldToSelect('code');
//        $code = $collection->getData();
//        foreach ($code as $item){
//            if ($quote) {
//                if ($couponCode == $item['code']) {
//                    throw new NoSuchEntityException(
//                        __("The coupon code isn't valid. Verify the code and try again.")
//                    );
//                }
//            }
//        }
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug('Test after set');
    }
}
