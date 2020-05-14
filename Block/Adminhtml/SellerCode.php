<?php
namespace Ecomteck\OrderCustomAttributes\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Sales\Block\Adminhtml\Order\Create\AbstractCreate;
use Ecomteck\OrderCustomAttributes\Model\Data\OrderCustomAttributes;

class SellerCode extends Template
{

    /**
    * @var \Magento\Framework\Registry
    */

    protected $_registry;

    /**
     * @var Json
     */
    private $json;

    /**
     * DeliveryDate constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $quote = $objectManager->create('\Magento\Backend\Model\Session\Quote');

        $order =  $quote->getOrder();

        return $order;
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrderId()
    {
        return $this->getOrder()->getEntityId();
    }

    /**
     * @return mixed
     */
    public function getSellerCode()
    {
        $order = $this->getOrder();
        $code = "";

        if( ! is_null($order) )
        {
            $attributes = $order->getData(OrderCustomAttributes::ORDER_CUSTOM_ATTRIBUTES_FIELD_NAME);

            $code = $this->parseCode($attributes);

        }

        // return $order->increment_id;
        return $code;
    }

    private function parseCode($attributes)
    {
        $code = "";

        $array = json_decode($attributes, true);

        if(isset($array['before-shipping-address-fields']))
        {
            if( isset( $array['before-shipping-address-fields']['seller_code'] ) )
            {
                $code = $array['before-shipping-address-fields']['seller_code'];
            }
        }
        else {
            return "";
        }

        return $code;
    }
}
