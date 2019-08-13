<?php

/**
 * @author Alan Barber <alan@cadence-labs.com>
 */
class Cadence_Fbpixel_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_order;

    public function isVisitorPixelEnabled()
    {
        return Mage::getStoreConfig("cadence_fbpixel/visitor/enabled");
    }

    public function isConversionPixelEnabled()
    {
        return Mage::getStoreConfig("cadence_fbpixel/conversion/enabled");
    }

    public function isAddToCartPixelEnabled()
    {
        return Mage::getStoreConfig("cadence_fbpixel/add_to_cart/enabled");
    }

    public function isAddToWishlistPixelEnabled()
    {
        return Mage::getStoreConfig('cadence_fbpixel/add_to_wishlist/enabled');
    }

    public function isInitiateCheckoutPixelEnabled()
    {
        return Mage::getStoreConfig('cadence_fbpixel/inititiate_checkout/enabled');
    }

    public function isViewProductPixelEnabled()
    {
        return Mage::getStoreConfig('cadence_fbpixel/view_product/enabled');
    }

    public function isSearchPixelEnabled()
    {
        return Mage::getStoreConfig('cadence_fbpixel/search/enabled');
    }

    public function getVisitorPixelId()
    {
        return Mage::getStoreConfig("cadence_fbpixel/visitor/pixel_id");
    }

    public function getConversionPixelId()
    {
        return Mage::getStoreConfig("cadence_fbpixel/conversion/pixel_id");
    }

    /**
     * @param $event
     * @param $data
     * @return string
     */
    public function getPixelHtml($event, $data = false)
    {
        $id = $this->getVisitorPixelId();
        $json = '';
        $query = '';
        if ($data) {
            $json = ', ' . json_encode($data);
        }
        $html = <<<HTML
    <!-- Begin Facebook {$event} Pixel -->
    <script type="text/javascript">
        fbq('track', '{$event}'{$json});
    </script>
    <!-- End Facebook {$event} Pixel -->
HTML;
        return $html;
    }

    public function getOrderIDs()
    {
        $orderIDs = array();

        foreach($this->_getOrder()->getAllVisibleItems() as $item){
            $product = Mage::getModel('catalog/product')->load( $item->getProductId() );
            $orderIDs = array_merge($orderIDs, $this->_getProductTrackID($product));
        }

        return json_encode($orderIDs);
    }

    protected function _getOrder(){
        if(!$this->_order){
            $orderId = Mage::getSingleton('checkout/type_onepage')->getCheckout()->getLastOrderId();
            $this->_order =  Mage::getModel('sales/order')->load($orderId);
        }

        return $this->_order;
    }

    protected function _getProductTrackID($product)
    {
        $productType = $product->getTypeID();

        if($productType == "grouped") {
            return $this->_getProductIDs($product);
        } else {
            return $this->_getProductID($product);
        }
    }

    protected function _getProductIDs($product)
    {
        $group = Mage::getModel('catalog/product_type_grouped')->setProduct($product);
        $group_collection = $group->getAssociatedProductCollection();
        $ids = array();

        foreach ($group_collection as $group_product) {

            $ids[] = $this->_getProductID($group_product);
        }

        return $ids;
    }

    protected function _getProductID($product)
    {
        return array(
            $product->getSku()
        );
    }

    public function getOrderItemsCount()
    {
        $order = $this->_getOrder();
        return count($order->getItemsCollection());
    }
}