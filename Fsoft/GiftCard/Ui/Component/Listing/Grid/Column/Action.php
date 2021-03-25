<?php

namespace Fsoft\GiftCard\Ui\Component\Listing\Grid\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Action extends Column
{
    protected $urlBuilder;
    protected $context;
    protected $priceCurrency;
    public function __construct
    (
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        PriceCurrencyInterface $priceCurrency,
        array $components=[],
        array $data=[]
    )
    {
        $this->priceCurrency = $priceCurrency;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {
            foreach($dataSource['data']['items'] as &$item)
            {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl('giftcard/code/edit', ['id' => $item['giftcard_id']]),
                    'label' => __('Edit'),
                    'hidden' => false
                ];
                $item['balance'] = $this->priceCurrency->format($item['balance'],false);
                $item['amount_used'] = $this->priceCurrency->format($item['amount_used'],false);
            }
        }
        return $dataSource;
    }
}
