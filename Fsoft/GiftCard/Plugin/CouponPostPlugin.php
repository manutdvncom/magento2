<?php
namespace Fsoft\GiftCard\Plugin;

use Magento\Checkout\Controller\Cart\CouponPost;
use Fsoft\GiftCard\Model\GiftCardFactory;
use Magento\Customer\Model\Session;
use Fsoft\GiftCard\Model\ResourceModel\History\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
class CouponPostPlugin
{
    protected $giftcardFactory;
    protected $customer;
    protected $collection;
    protected $resourceConnection;
    public function __construct
    (
        GiftCardFactory $giftCardFactory,
        Session $customer,
        CollectionFactory $collection,
        ResourceConnection $resourceConnection
    )
    {
        $this->giftcardFactory = $giftCardFactory;
        $this->customer = $customer;
        $this->collection = $collection;
        $this->resourceConnection = $resourceConnection;
    }
    protected function getGiftCardCode(){
        $customerTable = $this->resourceConnection->getTableName('customer_entity');
        $giftcardTable = $this->resourceConnection->getTableName('giftcard_code');
        $customer_id = $this->customer->getCustomer()->getId();
        $joinCollection = $this->collection->create();
        $joinCollection
            ->getSelect()
            ->join(
                ['gc' => $giftcardTable],
                "main_table.giftcard_id = gc.giftcard_id",
                [
                    'code' => 'gc.code',
                ]
            )
            ->join(
                ['ce' => $customerTable],
                "main_table.customer_id = ce.entity_id",
                [
                    'customer_id' => 'ce.entity_id'
                ]
            )
            ->where("customer_id=" . $customer_id);
        return $joinCollection;
    }

    public function aroundExecute(CouponPost $subject, callable $proceed)
    {
        $giftcardCode = $subject->getRequest()->getParam('remove') == 1
            ? ''
            : trim($subject->getRequest()->getParam('coupon_code'));

        $giftcard = $this->giftcardFactory->create();
        $customer_id = $this->customer->getCustomer()->getId();
        $giftcard->load($giftcardCode,'code');
        $escaper = $subject->_objectManager->get(\Magento\Framework\Escaper::class);
//        $oldGiftCard = $giftcard->getCode();
        $codeLength = strlen($giftcardCode);
//        if (!$codeLength && !strlen($oldGiftCard)) {
//            return $subject->_goBack();
//        }
//        echo __METHOD__ . " - Before proceed() </br>";
        $result = $proceed();
//        echo __METHOD__ . " - After proceed() </br>";
        try {
            $data = $this->getGiftCardCode();
            foreach ($data->getData() as $item) {
//                    echo "<pre>";
//                    print_r($item);
//                    echo "<br>";
//                    print_r($giftcard->getId());
//                    echo "</pre>";
//                    die;
                if ($codeLength && $item['code'] == $giftcardCode) {
                    if ($giftcard->getId() && $item['customer_id'] == $customer_id) {
                        $subject->_checkoutSession->getQuote()->setCouponCode($giftcardCode)->save();
                        $subject->messageManager->addSuccessMessage(
                            __(
                                'You used coupon code "%1".',
                                $escaper->escapeHtml($giftcardCode)
                            )
                        );
                    } else {
                        $subject->messageManager->addErrorMessage(
                            __(
                                'The coupon code "%1" is not valid.',
                                $escaper->escapeHtml($giftcardCode)
                            )
                        );
                    }
                } else {
                    if ($item['customer_id'] == $customer_id && $giftcard->getId() && $giftcardCode == $giftcard->getCode()) {
                        $subject->messageManager->addSuccessMessage(
                            __(
                                'You used coupon code "%1".',
                                $escaper->escapeHtml($giftcardCode)
                            )
                        );
                    } else {
                        $subject->messageManager->addErrorMessage(
                            __(
                                'The coupon code "%1" is not valid.',
                                $escaper->escapeHtml($giftcardCode)
                            )
                        );
                    }
                }
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $subject->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $subject->messageManager->addErrorMessage(__('We cannot apply the coupon code.'));
            $subject->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
        }

        return $result;
    }
}
