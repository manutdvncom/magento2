<?php
namespace Fsoft\GiftCard\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Fsoft\GiftCard\Model\GiftCardFactory;

class Custom extends AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    protected $giftcardFactory;
    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        GiftCardFactory $giftCardFactory
    ) {
        $this->_priceCurrency = $priceCurrency;
        $this->giftcardFactory = $giftCardFactory;
    }
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $giftcard = $this->giftcardFactory->create()->getCollection();
        foreach ($giftcard as $item){
            $baseDiscount = $item['balance'];
            $discount =  $this->_priceCurrency->convert($baseDiscount);
            $total->addTotalAmount('customdiscount', -$discount);
            $total->addBaseTotalAmount('customdiscount', -$baseDiscount);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
            $quote->setCustomDiscount(-$discount);
            return $this;
        }
//        $baseDiscount = 5;
//        $discount =  $this->_priceCurrency->convert($baseDiscount);
//        $total->addTotalAmount('customdiscount', -$discount);
//        $total->addBaseTotalAmount('customdiscount', -$baseDiscount);
//        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
//        $quote->setCustomDiscount(-$discount);
    }
}
