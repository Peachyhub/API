<?php require_once 'app/Mage.php';
Mage::app('default'); // Default or your store view name.
?>
<?php
  $categoryarray = array("Cloths", "Bags", "Beauty");
  $parentid=2; // parent id which you want sub category
  $categories=explode(',',Mage::getModel('catalog/category')->load($parentid)->getChildren());
	$existingcategories =array();
  foreach($categories as $cat){ $category=Mage::getModel('catalog/category')->load($cat); ?>
  <?php $existingcategories[] = $category->getName();?>
  <?php } ?> 
 <?php 

//get a new category object
$category = Mage::getModel('catalog/category');
$category->setStoreId(0); // 0 = default/all store view. If you want to save data for a specific store view, replace 0 by Mage::app()->getStore()->getId().

$resultcatarray = array_diff($categoryarray, $existingcategories);

if(!empty($resultcatarray)):

foreach($resultcatarray as $category):
$general['name'] = $category;
$general['path'] = "1/2"; 
$general['description'] = $category;
$general['meta_title'] = $category; //Page title
$general['meta_keywords'] = $category;
$general['meta_description'] = "Some description to be found by meta search robots.";
$general['landing_page'] = "";
$general['display_mode'] = "PRODUCTS_AND_PAGE"; 
$general['is_active'] = 1;
$general['include_in_menu'] = 1;
$general['is_anchor'] = 1;
$general['available_sort_by'] = 'asc';
$general['default_sort_by'] = 'asc';
$general['url_key'] = $category;//url to be used for this category's page by magento.
$general['showtext'] = "0";
/* @var $api Mage_Catalog_Model_Category_Api */
$api = Mage::getModel('catalog/category_api');
echo "Success! Id: ".$api->create('1/2',$general,$api->currentStore());
endforeach;

else:

echo "No new category to create.";
endif;
?>
