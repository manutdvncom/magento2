<?php

namespace Fsoft\GiftCard\Plugin;

use Fsoft\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckCode
{
    protected $giftcardCollectionFactory;

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
        foreach ($code as $item) {
//            echo "<pre>";
//            print_r($item);
//            echo "</pre>";
//            die;
            if ($cartId) {
                $quote->setCouponCode($couponCode);
                $subject->quoteRepository->save($quote->collectTotals());
                if ($couponCode == $item['code']) {
                    return true;
                }
            }
        }
    }
    public function beforeGet(\Magento\Quote\Model\CouponManagement $subject, $cartId)
    {
//        $quote = $subject->quoteRepository->getActive($cartId);
//        return $quote->getCouponCode();

    }
}
