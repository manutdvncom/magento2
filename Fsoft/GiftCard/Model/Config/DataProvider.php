<?php

namespace Fsoft\GiftCard\Model\Config;

use Fsoft\GiftCard\Model\GiftCardFactory;
use Fsoft\GiftCard\Model\ResourceModel\GiftCard\CollectionFactory;
use Magento\Backend\App\Action;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        Action $request,
        array $meta = [],
        array $data = []
    )
    {
        $this->request = $request;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->_loadedData[$item->getId()] = $item->getData();
        }
        return $this->_loadedData;
    }
//    public function getMeta()
//    {
//        $meta = parent::getMeta(); // TODO: Change the autogenerated stub
//        $id = $this->request->getRequest()->getParam('id');
//        if(isset($id)){
//            $meta['giftcard_form']['children']['code']['arguments']['data']['config']['disabled'] = 1;
//        }
//        else{
//            $meta['giftcard_form']['children']['code']['arguments']['data']['config']['disabled'] = 0;
//        }
//        return $meta;
//    }
}
