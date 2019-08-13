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

    public function getVisitorPixelId()
    {
        return Mage::getStoreConfig("cadence_fbpixel/visitor/pixel_id");
    }

    public function getConversionPixelId()
    {
        return Mage::getStoreConfig("cadence_fbpixel/conversion/pixel_id");
    }
}