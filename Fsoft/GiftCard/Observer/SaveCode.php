<?php

namespace Fsoft\GiftCard\Observer;

use Magento\Framework\Event\Observer;

class SaveCode implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->debug('TTTTTTTTTTTTTT');
        return $this;
    }
}
