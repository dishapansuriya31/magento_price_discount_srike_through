<?php
namespace Kitchen\CustomDiscount\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Quote\Model\Quote\Item;

class PricePlugin
{
    /**
     * @param FinalPrice $subject
     * @param float|null $result
     * @return float|null
     */
    public function afterGetValue(FinalPrice $subject, $result)
    {
        $product = $subject->getProduct();
        if ($product->getData('discount_text_price')) {
            $discountPercent = (float)$product->getData('discount_text_price');
            
            $originalPrice = $product->getPrice();

            if ($result !== null && $result < $originalPrice) {
                $originalPrice = $result; 
            }
            
            $discountedPrice = $originalPrice * ($discountPercent / 100);
            return $discountedPrice;
        }
        return $result;
    }

   
    public function afterGetPrice(\Magento\Quote\Model\Quote\Item\PriceProcessor $subject, $result, Item $item)
    {
        $product = $item->getProduct();
        if ($product->getData('discount_text_price')) {
            $discountPercent = (float)$product->getData('discount_text_price');
            $originalPrice = $product->getPrice();
            $item->setCustomPrice($originalPrice); 
            $item->setOriginalCustomPrice($originalPrice);
            $result = $originalPrice;
        }
        return $result;
    }
}
