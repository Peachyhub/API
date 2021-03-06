<?php
/*
 * File Name: sears_model.php
 * Create on: 24/02/2014
 * Update on: 24/02/2014
 * Version  : 1.0
 * Created By: PB-SIPL
 * Description: This file is using for interface from database 
 */
class Sears_model extends CI_Model {
     /*
     * function Name: getParentCategories
     * Create on: 19/March/2014
     * Update on: 19/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used for get all categories from category table
     */

    public function getParentCategories() {
        $this->db->select('*');
        $this->db->from('tbl_parent_categories');
	$this->db->order_by('category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }


     /*
     * function Name: getMagentoParentCategories
     * Create on: 24/March/2014
     * Update on: 24/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used for get all magento parent categories from category table
     */

    public function getMagentoParentCategories() {
        $this->db->select('*');
        $this->db->from('tbl_mcategory_level1');
	$this->db->order_by('category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }
     /*
     * function Name: getChildCategories
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for get all categories from category table
     */

    public function getChildCategories($parentID) {
        $this->db->select('child_category_id,category_name');
        $this->db->from('tbl_child_categories');
        $this->db->where('parent_category_id', $parentID);
	$this->db->order_by('category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
	return false;
    }


    public function getSubChildCategories($parentID) {
        $this->db->select('child_category_id,subchild_category_id,sub_category_name');
        $this->db->from('tbl_subchild_categories');
        $this->db->where('child_category_id', $parentID);
	$this->db->order_by('sub_category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;

        } 
	return false;
    } 

    
    public function getSub2ChildCategories($parentID) {
        $this->db->select('sub2child_category_id,subchild_category_id,sub2_category_name');
        $this->db->from('tbl_sub2child_categories');
        $this->db->where('subchild_category_id', $parentID);
	$this->db->order_by('sub2_category_name');
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;

        }
	return false;
    } 

     /*
     * function Name: getMagentoLevel2Categoires
     * Create on: 25/03/2014 
     * Update on: 25/03/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for get all categories from category table
     */ 

    public function getMagentoLevel2Categoires($parentID) {
        $this->db->select('level2_mcategory_id,category_name,mcategory_id');
        $this->db->from('tbl_mcategory_level2');
        $this->db->where('level1_mcategory_id', $parentID);
	$this->db->order_by('category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
	return false;
    }

    public function getMagentoLevel3Categoires($parentID) {
        $this->db->select('level3_mcategory_id,category_name,mcategory_id');
        $this->db->from('tbl_mcategory_level3');
        $this->db->where('level2_mcategory_id', $parentID);
	$this->db->order_by('category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
	return false;
    }

    public function getMagentoLevel4Categoires($parentID) {
        $this->db->select('level4_mcategory_id,category_name,mcategory_id');
        $this->db->from('tbl_mcategory_level4');
        $this->db->where('level3_mcategory_id', $parentID);
	$this->db->order_by('category_name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
	return false;
    }


    /*
     * function Name: saveProduct
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for save product details
     */

 public function saveProduct($data = array(),$pTVendor = array()) {
        if (!empty($data['part_number']) && !empty($pTVendor['display_price']) && !empty($pTVendor['cut_price'])) {
            /* Check product is already exist */
            $this->db->select('*');
            $this->db->from('tbl_product_details');
            $this->db->where('part_number', trim($data['part_number']));
	    if(isset($data['mfg_part_number'])){
            $this->db->where('mfg_part_number', $data['mfg_part_number']);
	    }
            $query = $this->db->get();
            if ($query->num_rows()) {
                /* If product is already exist then update it */
                $result = $query->row();   
                            $this->db->select('*');
                            $this->db->from('tbl_product_to_vendor');
                            $this->db->where('part_number', trim($pTVendor['vendor_id'].'-'.$data['part_number']));
                            $this->db->where('vendor_id', trim($pTVendor['vendor_id']));
                            	    if(isset($data['mfg_part_number'])){
                                    $this->db->where('mfg_part_number', $data['mfg_part_number']);
                                    }                                                                  
                                            $queryPtV = $this->db->get();
                                                if ($queryPtV->num_rows()) {
                                                    $resultqueryPtV = $query->row(); 
                                                    $productToVendor['product_id'] = $result->product_id;
                                                    if(isset($pTVendor['retailer_name'])) 
                                                    $productToVendor['retailer_name'] = $pTVendor['retailer_name'];
                                                    if(isset($pTVendor['vendor_id'])) 
                                                    $productToVendor['vendor_id'] = $pTVendor['vendor_id'];
                                                    if(isset($data['part_number']))
                                                    $productToVendor['part_number'] = trim($pTVendor['vendor_id'].'-'.$data['part_number']);
                                                    if(isset($data['mfg_part_number']))
                                                    $productToVendor['mfg_part_number'] = $data['mfg_part_number'];
                                                    if(isset($pTVendor['brand_name']))
                                                    $productToVendor['brand_name'] = $pTVendor['brand_name'];
                                                    if(isset($pTVendor['rating']))
                                                    $productToVendor['rating'] = $pTVendor['rating'];
                                                    if(isset($pTVendor['display_price']))
                                                    $productToVendor['display_price'] =$pTVendor['display_price'];
                                                    if(isset($pTVendor['cut_price']))
                                                    $productToVendor['cut_price'] = $pTVendor['cut_price'];
                                                    if(isset($pTVendor['stock_indicator']))
                                                    $productToVendor['stock_indicator'] = $pTVendor['stock_indicator'];
                                                    if(isset($pTVendor['product_url']))
                                                    $productToVendor['product_url'] = $pTVendor['product_url'];
                                                    if(isset($pTVendor['sale_indicator']))
                                                    $productToVendor['sale_indicator'] = $pTVendor['sale_indicator'];                                                  
                                                    $this->db->where('product_to_vendor_id', $resultqueryPtV->product_to_vendor_id);
                                                    $this->db->update('tbl_product_to_vendor', $productToVendor); 
                                                    
                                                    
                                                } else {

                                                    $productToVendor['product_id'] = $result->product_id;
                                                    if(isset($pTVendor['retailer_name'])) 
                                                    $productToVendor['retailer_name'] = $pTVendor['retailer_name'];
                                                    if(isset($pTVendor['vendor_id'])) 
                                                    $productToVendor['vendor_id'] = $pTVendor['vendor_id'];
                                                    if(isset($data['part_number']))
                                                    $productToVendor['part_number'] = trim($pTVendor['vendor_id'].'-'.$data['part_number']);
                                                    if(isset($data['mfg_part_number']))
                                                    $productToVendor['mfg_part_number'] = $data['mfg_part_number'];
                                                    if(isset($pTVendor['brand_name']))
                                                    $productToVendor['brand_name'] = $pTVendor['brand_name'];
                                                    if(isset($pTVendor['rating']))
                                                    $productToVendor['rating'] = $pTVendor['rating'];
                                                    if(isset($pTVendor['display_price']))
                                                    $productToVendor['display_price'] =$pTVendor['display_price'];
                                                    if(isset($pTVendor['cut_price']))
                                                    $productToVendor['cut_price'] = $pTVendor['cut_price'];
                                                    if(isset($pTVendor['stock_indicator']))
                                                    $productToVendor['stock_indicator'] = $pTVendor['stock_indicator'];
                                                    if(isset($pTVendor['product_url']))
                                                    $productToVendor['product_url'] = $pTVendor['product_url'];
                                                    if(isset($pTVendor['sale_indicator']))
                                                    $productToVendor['sale_indicator'] = $pTVendor['sale_indicator'];
                                                    $this->db->insert('tbl_product_to_vendor', $productToVendor);
                                        }                           
                               
                
		if($result->display_price!=trim($pTVendor['display_price']) || $result->stock_indicator!=trim($pTVendor['stock_indicator']) || $result->cut_price!=trim($data['cut_price'])) {
                $data['product_updated'] = '1';
		if(trim($result->cat_entry_id)==trim($data['cat_entry_id'])) {
		$data['cat_entry_id']=$result->cat_entry_id;
		} else { $data['cat_entry_id']=$result->cat_entry_id.",".$data['cat_entry_id']; }
                $this->db->where('product_id', $result->product_id);
		$this->db->update('tbl_product_details', $data); 
                return 0;
		}
		return false;
               }else{
                /* If product is not exist then insert new */
                $data['created_date'] = date('Y-m-d H:m:s');
                $this->db->insert('tbl_product_details', $data);
                   $productToVendor['product_id'] = $this->db->insert_id();
                   
                   if(isset($pTVendor['vendor_id'])) 
                   $productToVendor['vendor_id'] = $pTVendor['vendor_id'];
                   if(isset($pTVendor['retailer_name'])) 
                   $productToVendor['retailer_name'] = $pTVendor['retailer_name'];
                   if(isset($data['part_number']))
                   $productToVendor['part_number'] =trim($pTVendor['vendor_id'].'-'.$data['part_number']);
                   if(isset($data['mfg_part_number']))
                   $productToVendor['mfg_part_number'] = $data['mfg_part_number'];
                   if(isset($pTVendor['brand_name']))
                   $productToVendor['brand_name'] = $pTVendor['brand_name'];
                   if(isset($pTVendor['rating']))
                   $productToVendor['rating'] = $pTVendor['rating'];
                   if(isset($pTVendor['display_price']))
                   $productToVendor['display_price'] =$pTVendor['display_price'];
                   if(isset($pTVendor['cut_price']))
                   $productToVendor['cut_price'] = $pTVendor['cut_price'];
                   if(isset($pTVendor['stock_indicator']))
                   $productToVendor['stock_indicator'] = $pTVendor['stock_indicator'];
                   if(isset($pTVendor['product_url']))
                   $productToVendor['product_url'] = $pTVendor['product_url'];
                   if(isset($pTVendor['sale_indicator']))
                   $productToVendor['sale_indicator'] = $pTVendor['sale_indicator'];
                   $this->db->insert('tbl_product_to_vendor', $productToVendor);
                return 1;
            }
        }
        return false;
    }
    /*
     * function Name: getSaveproducts
     * Create on: 27/02/2014
     * Update on: 27/02/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to get all not magento inserted products.
     */

    public function getSaveProducts() {
        $this->db->select('*');
        $this->db->from('tbl_product_details');
	$this->db->where('magento_status', 0);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }


    /*
     * function Name: getMappedcategoyList
     * Create on: 26/March/2014
     * Update on: 26/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to get all mapped categories list
     */

    public function getMappedcategoyList() {
        $this->db->select('*');
        $this->db->from('tbl_mapped_categories');
        $this->db->order_by("mapped_categories_id","desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }
 /*
     * function Name: getSimilarProductsInstores
     * Create on: 09/April/2014
     * Update on: 09/April/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to get all mapped categories list
     */
    public function getSimilarProductsInstores($product_id) {
        $this->db->select('*');
        $this->db->from('tbl_product_to_vendor');
	$this->db->where('product_id', $product_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result; 
        }
        return FALSE;;
    }
     /*
     * function Name: getVendorList
     * Create on: 04/April/2014
     * Update on: 04/April/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to get list of all vendors.
     */
    public function getVendorList() {
        $this->db->select('*');
        $this->db->from('tbl_vendor_details');
        $this->db->order_by('vendor_name','');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }

    /*
     * function Name: setMappedcategoyList
     * Create on: 26/March/2014
     * Update on: 26/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to set all mapped categories list
     */

    public function settMappedcategoyList($magentolist, $searslist,$categories_ids,$storeName) {
	$this->db->select('mapped_categories_id');
        $this->db->from('tbl_mapped_categories');
	$this->db->where('magento_category_list', trim($magentolist));
	$this->db->where('magento_category_ids', trim($categories_ids));
	$this->db->where('vendor_category_list', trim($searslist));
	$this->db->where('vendor_name', trim($storeName));
        $query = $this->db->get();
        if ($query->num_rows()) {
        } else {
 	  $data = array('magento_category_list' => $magentolist,'vendor_category_list' => $searslist,'magento_category_ids' => $categories_ids,'vendor_name' => $storeName);
	  $result = $this->db->insert('tbl_mapped_categories', $data); 
	}
        return FALSE;
    } 
     /*
     * function Name: getSaveproducts
     * Create on: 27/02/2014
     * Update on: 27/02/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to get all not magento inserted products.
     */

    public function checkProductStatusFromAPI($partNumber) {
        $this->db->select('part_number');
        $this->db->from('tbl_product_to_vendor');
	$this->db->where('part_number', $partNumber);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
	     return $result;
            }   
      return FALSE;
    }

     /*
     * function Name: getSaveproductsUpdate
     * Create on: 28/02/2014
     * Update on: 28/02/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to get all magento inserted products for updation.
     */

    public function getSaveproductsUpdate() {
        $this->db->select('*');
        $this->db->from('tbl_product_details');
	$this->db->where('magento_status', 1);
	$this->db->where('product_updated',1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE; 

    }
	public function getSaveproductsToInsert() {
        $this->db->select('*');
        $this->db->from('tbl_product_details');
	$this->db->where('magento_status', 0);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }

    }

     /*
     * function Name: Temp table to check the inserted product.
     * Create on: 10/March/2014
     * Update on: 10/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is use to insert magento created product to check out dataed products.
     */

    public function deleteProductFromDetails($sku) {
	$tables = array('tbl_product_details');
	$this->db->where('part_number', $sku);
	$this->db->delete($tables);
    }
    /*
     * function Name: updateMagentoInsertedProducts
     * Create on: 01/March/2014
     * Update on: 01/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: This function is used to update status of inserted products in magento.
     */

    public function updateMagentoInsertedProducts($productPartnumber) {
	$data = array('magento_status' => 1); /* 0= Not created in magento 1= created in magento*/
	$this->db->update('tbl_product_details', $data, array('part_number' => $productPartnumber));
    }

    /*
     * File Name: update magento insertedProductImages status
     * Create on: 06/March/2014
     * Update on: 06/March/2014
     * Version  : 1.0
     * Description: This function is used to update inserted magento images status.
     */

    public function updateMagentoInsertedImageStatus($productPartnumber) {

	$data = array('product_updated' => 0 ); /* 0= Not created in magento 1= created in magento*/
	$this->db->update('tbl_product_details', $data, array('part_number' => $productPartnumber));
    }

    /*
     * File Name: update magento inserted updated products status
     * Create on: 28/March/2014
     * Update on: 28/March/2014
     * Version  : 1.0
     * Description: This function is used to update status of magento updated products.
     */

    public function updateFlagForMagentoUpdatedProducts($productPartnumber) {
	$data = array('product_updated' => 0 ); /* 0= updated into magento 1= Need to update in magento*/
	$this->db->update('tbl_product_details', $data, array('part_number' => $productPartnumber));
    }
    /*
     * function Name: update product description for products
     * Create on: 06/March/2014
     * Update on: 06/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to update required products information.
     */

    public function updateProductDescription($productDetails,$partNumber) {
	$this->db->update('tbl_product_details', $productDetails, array('part_number' => $partNumber));
    }
    /*
     * function Name: Insert and update catgories in central database.
     * Create on: 12/March/2014
     * Update on: 12/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to insert and update categories.
     */

    public function createCategories($createCategories, $cId) {
	if($cId==trim('new')) {	
	$this->db->insert('tbl_category', $createCategories);
	} else	{
	$this->db->update('tbl_category', $createCategories, array('category_id' => $cId));
	}
	return true;
    }
     /*
     * function Name: InsertMagento Products SKUs
     * Create on: 31/March/2014
     * Update on: 31/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to Insert magento products skus
     */

    public function checkAndInsertProductsFromMagento($partNumber) {
	$this->db->select('part_number');
        $this->db->from('tbl_manage_outdated_product');
	$this->db->where('part_number',$partNumber);
        $query = $this->db->get();
        if ($query->num_rows()) {

        } else  {
	$data['part_number']=$partNumber;
        $this->db->insert('tbl_manage_outdated_product', $data);
	}
    } 
	/*
	* function Name: Clean database table 
        * Create on: 31/March/2014.
        * Updated on: 31/March/2014.
        * Version  : 1.0
        * Created By: KP-SIPL
	*/
   public function cleanDatabaseTable($table) {
	$this->db->truncate($table);
	} 
        
        /*
	* function Name: Clean database table 
        * Create on: 16/May/2014
        * Updated on: 16/May/2014
        * Version  : 1.0
        * Created By: KP-SIPL
	*/
   public function cleanDatabaseTableforreinsert() {
        
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
	$this->db->truncate('tbl_parent_categories');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->truncate('tbl_child_categories');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->truncate('tbl_subchild_categories');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        $this->db->truncate('tbl_sub2child_categories');
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

	} 
    /*
     * function Name: Function to insert and fetch  parent categories.
     * Create on: 18/March/2014
     * Update on: 18/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to insert and fetch  parent categories.
     */
	
    public function searsKmartParentCategoires($createCategories) {
	$data['category_name']=mysql_real_escape_string($createCategories);
	$this->db->insert('tbl_parent_categories', $data);
 	return $this->db->insert_id();
    }
 


    /*
     * function Name: Function to insert and fetch  child categories.
     * Create on: 18/March/2014
     * Update on: 18/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to insert and fetch  child categories.
     */

    public function searsKmartChildCategoires($childCategory,$parentId) {
        $data['category_name']=mysql_real_escape_string($childCategory);
	$data['parent_category_id']=$parentId;
          $this->db->insert('tbl_child_categories', $data); return $this->db->insert_id(); 
    }

    /*
     * function Name: Function to insert and fetch  child categories.
     * Create on: 19/March/2014
     * Update on: 19/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to insert and fetch  child categories.
     */

    public function searsKmartSubChildCategoires($subChildCategory,$childId) {
	$data['sub_category_name']=mysql_real_escape_string($subChildCategory);
	$data['child_category_id']=$childId;
        $this->db->insert('tbl_subchild_categories', $data); return $this->db->insert_id(); 

    }
    
    
    /*
     * function Name: Function to insert and fetch  sub2 child categories.
     * Create on: 19/May/2014
     * Update on: 19/May/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Function to insert and fetch  Sub child categories.
     */

    public function searsKmartSub2ChildCategoires($subChildCategory,$childId) {
	$data['sub2_category_name']=mysql_real_escape_string($subChildCategory);
	$data['subchild_category_id']=$childId;
        $this->db->insert('tbl_sub2child_categories', $data);  
                //if ($query->num_rows() > 0) { $result = $query->row(); return $result->subchild_category_id; } else { $this->db->insert('tbl_subchild_categories', $data); return $this->db->insert_id(); }

    }

    /*
     * function Name: saveApiUserDetails
     * Create on: 24/Feb/2014
     * Update on: 24/Feb/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for save API called user information into database 
     */
    public function saveVendorDetails($data){
        if(is_array($data) && !empty($data)){
            
        }
    }
    
    /*
     * function Name: getBrandValue from Central database   
     * Create on: 16/05/2014
     * Update on: 16/05/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description: Funcation is used to fetch the value of brand according to assign.
     */ 

    public function getBrandValueFromCD($brandname) {
        $this->db->select('brand_value');
        $this->db->from('tbl_brand_list');
        $this->db->where('brand_name', $brandname);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row_array();
            return $result;
        }
	return false;
    }
    

}
