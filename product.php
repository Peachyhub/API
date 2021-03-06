<?php
    error_reporting(E_ALL | E_STRICT);
    $mageFilename = 'app/Mage.php';
    require_once $mageFilename;
    $app = Mage::app('default'); 
     
    ini_set('display_errors', 1);
 
 
    $api = new Mage_Catalog_Model_Product_Api();
     
    $attribute_api = new Mage_Catalog_Model_Product_Attribute_Set_Api();
    $attribute_sets = $attribute_api->items();
     
    $productData = array(); 
    $productData['website_ids'] = array(1); 
    $productData['categories'] = array(8);
 
    $productData['status'] = 1;
     
    $productData['name'] = utf8_encode('sipl');
    $productData['description'] = utf8_encode('sipl desc');
    $productData['short_description'] = utf8_encode('sipl short desc');
     
    $productData['price'] = 12.34;
    $productData['weight'] = 23.25;
    $productData['tax_class_id'] =2;
    $productData['page_layout'] ='two_columns_left';
         
    $new_product_id = $api->create('simple',$attribute_sets[0]['set_id'],'ND3',$productData);
     
    print_r($new_product_id);
     
    $stockItem = Mage::getModel('cataloginventory/stock_item');
    $stockItem->loadByProduct( $new_product_id );
     
    $stockItem->setData('use_config_manage_stock', 1);
    $stockItem->setData('qty', 100);
    $stockItem->setData('min_qty', 0);
    $stockItem->setData('use_config_min_qty', 1);
    $stockItem->setData('min_sale_qty', 0);
    $stockItem->setData('use_config_max_sale_qty', 1);
    $stockItem->setData('max_sale_qty', 0);
    $stockItem->setData('use_config_max_sale_qty', 1);
    $stockItem->setData('is_qty_decimal', 0);
    $stockItem->setData('backorders', 0);
    $stockItem->setData('notify_stock_qty', 0);
    $stockItem->setData('is_in_stock', 1);
    $stockItem->setData('tax_class_id', 0);
     
    $stockItem->save();
     
    $product = Mage::getModel('catalog/product')->load($new_product_id);
     
    $product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
    $product->addImageToMediaGallery ('http://www.smartvm.com/images/video-splitters.jpg', array ('image','small_image','thumbnail'), false, false); 
    $product->addImageToMediaGallery ('http://www.smartvm.com/images/video-splitters.jpg', array ('image','small_image','thumbnail'), false, false); 
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
 
    $product->save();
     
     
    $product = Mage::getModel('catalog/product')->load($new_product_id);
    $optionData =   array(
                        "title" => "Custom Text Field Option Title 1",
                        "type" => "field",
                        "is_require" => 1,
                        "sort_order" => 1,
                        "price" => 0,
                            "price_type" => "fixed",
                            "sku" => "",
                            "max_characters" => 15
                    );
     
    $product->setHasOptions(1);
    $option = Mage::getModel('catalog/product_option')
              ->setProductId($new_product_id)
              ->setStoreId(1)
              ->addData($optionData);
    $option->save();
    $product->addOption($option);
     
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
 
    $product->save();
     
    $product = Mage::getModel('catalog/product')->load($new_product_id);
    $optionData =   array(
                        "title" => "Custom Text Field Option Title 2",
                        "type" => "field",
                        "is_require" => 1,
                        "sort_order" => 2,
                        "price" => 0,
                            "price_type" => "fixed",
                            "sku" => "",
                            "max_characters" => 25
                    );
     
    $product->setHasOptions(1);
    $option = Mage::getModel('catalog/product_option')
              ->setProductId($new_product_id)
              ->setStoreId(1)
              ->addData($optionData);
    $option->save();
    $product->addOption($option);
     
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID); 
 
    $product->save();
?>
