<?php

/**
 * Enhanced markup tags syntax
 *
 * @category   LCB
 * @package    LCB_InstantCheckout
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Syntax_Model_Template_Filter extends Mage_Widget_Model_Template_Filter {

    /**
     * General product directives output
     *
     * @param array $construction
     * @return string
     */
    public function productDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);
        $id = null;
        $attribute = null;

        if (isset($params['id'])) {
            $id = intval($params['id']);
        }

        if (isset($params['sku'])) {
            $id = Mage::getModel('catalog/product')->getResource()->getIdBySku($params['sku']);
        }

        if (isset($params['attribute'])) {
            $attribute = $params['attribute'];
        }

        if ($id && $attribute) {

            $product = Mage::getModel('catalog/product')->load($id);
            return $product->getData($attribute);
        }

        return '';
    }

    /**
     * Product price output
     *
     * @param array $construction
     * @return string
     */
    public function priceDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);
        $id = null;

        if (isset($params['id'])) {
            $id = intval($params['id']);
        }

        if (isset($params['sku'])) {
            $id = Mage::getModel('catalog/product')->getResource()->getIdBySku($params['sku']);
        };

        if ($id) {

            $product = Mage::getModel('catalog/product')->load($id);

            if (isset($params['type'])) {
                $type = $params['type'];
                switch ($type) {
                    case "price":
                        $price = $product->getPrice();
                        break;
                    case "special_price":
                        $price = $product->getSpecialPrice();
                        break;
                    default:
                        $price = $product->getFinalPrice();
                }
            } else {
                $price = $product->getFinalPrice();
            }

            return Mage::helper('core')->currency($price, true, false);
        }

        return '';
    }

}
