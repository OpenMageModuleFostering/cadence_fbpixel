<?php

/**
 * @author Alan Barber <alan@cadence-labs.com>
 */
class Cadence_Fbpixel_Helper_Data extends Mage_Core_Helper_Abstract
{
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
}