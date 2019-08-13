<?php

/**
 * @author Alan Barber <alan@cadence-labs.com>
 */
Class Cadence_Fbpixel_Model_Observer
{
    /**
     * @param Varien_Event_Observer $obs
     * @return $this
     */
    public function onSalesQuoteProductAddAfter(Varien_Event_Observer $obs)
    {
        if (!$this->_helper()->isAddToCartPixelEnabled()) {
            return $this;
        }

        $items = $obs->getItems();

        $candidates = array_replace(array(
            'content_ids' => [],
            'value' => 0.00
        ), $this->_getSession()->getAddToCart() ?: array());

        /** @var Mage_Sales_Model_Quote_Item $item */
        foreach ($items as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            $candidates['content_ids'][] = $item->getSku();
            $candidates['value'] += $item->getProduct()->getFinalPrice() * $item->getProduct()->getQty();
        }

        $data = array(
            'content_type' => 'product',
            'content_ids' => $candidates['content_ids'],
            'value' => $candidates['value'],
            'currency' => Mage::app()->getStore()->getCurrentCurrencyCode()
        );

        $this->_getSession()->setAddToCart($data);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $obs
     * @return $this
     */
    public function onWishlistAddProduct(Varien_Event_Observer $obs)
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $obs->getProduct();
        if (!$this->_helper()->isAddToWishlistPixelEnabled() || !$product) {
            return $this;
        }

        $data = [
            'content_type' => 'product',
            'content_ids' => [$product->getSku()],
            'value' => $product->getFinalPrice(),
            'currency' => Mage::app()->getStore()->getCurrentCurrencyCode()
        ];

        $this->_getSession()->setAddToWishlist($data);

        return $this;
    }

    /**
     * @return $this
     */
    public function onInitiateCheckout($obs)
    {
        if (!$this->_helper()->isInitiateCheckoutPixelEnabled()) {
            return $this;
        }
        if (!count(Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems())) {
            return $this;
        }

        $this->_getSession()->setInitiateCheckout();

        return $this;
    }

    /**
     * @param Varien_Event_Observer $obs
     * @return $this
     */
    public function onCatalogControllerProductInitAfter(Varien_Event_Observer $obs)
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $obs->getProduct();
        if (!$this->_helper()->isViewProductPixelEnabled() || !$product) {
            return $this;
        }
        $data = [
            'content_type' => 'product',
            'content_ids' => [$product->getSku()],
            'value' => $product->getFinalPrice(),
            'currency' => Mage::app()->getStore()->getCurrentCurrencyCode(),
            'content_name' => $product->getName()
        ];

        $this->_getSession()->setViewProduct($data);

        return $this;
    }

    /**
     * @param $obs
     * @return $this
     */
    public function onSearch($obs)
    {
        $text = Mage::helper('catalogsearch')->getQueryText();
        if (!$this->_helper()->isSearchPixelEnabled() || !$text || !strlen($text)) {
            return $this;
        }

        $data = [
            'search_string' => $text
        ];

        $this->_getSession()->setSearch($data);

        return $this;
    }

    /**
     * @return Cadence_Fbpixel_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('cadence_fbpixel/session');
    }

    /**
     * @return Cadence_Fbpixel_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper("cadence_fbpixel");
    }
}