<?php
namespace Ecomteck\OrderCustomAttributes\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Ecomteck\OrderCustomAttributes\Model\Data\OrderCustomAttributes;

class AdminhtmlSalesOrderCreateProcessData implements ObserverInterface
{
    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $requestData = $observer->getRequest();
        $sellerCode = isset($requestData['seller_code']) ? $requestData['seller_code'] : null;

        $data = [];
        $data['before-shipping-address-fields']['seller_code'] = $sellerCode;

        /** @var \Magento\Sales\Model\AdminOrder\Create $orderCreateModel */
        $orderCreateModel = $observer->getOrderCreateModel();
        $quote = $orderCreateModel->getQuote();

        //set the quote data
        $quoteData  = json_encode($data);

        $quote->setData(OrderCustomAttributes::ORDER_CUSTOM_ATTRIBUTES_FIELD_NAME, $quoteData);

        return $this;
    }
}
