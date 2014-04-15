<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * File Name: sears.php
 * Create on: 24/02/2014
 * Update on: 24/02/2014
 * Version  : 1.0
 * Created By: PB-SIPL
 * Description: This file is used for get product details form Sears and Kmart and update into database 
 */

class Sears extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('sears_model');
    }

    /*
     * Name : index
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description : This function is used for display search products by category 
     */

    public function index() {
        $data = array();
        $data['categories'] = $this->sears_model->getCategories();
        $this->load->view('sears/index', $data);
    }

   public function categoryMapping() {
        $data = array();
        $data['parentCategories'] = $this->sears_model->getParentCategories();
        $data['magentoParentCategories'] = $this->sears_model->getMagentoParentCategories();
        $data['getMappedcategoyList'] = $this->sears_model->getMappedcategoyList();
        $data['getVendorList'] = $this->sears_model->getVendorList();


        $this->load->view('sears/categories', $data);
    }
    /*
     * Name : index
     * Create on: 12/March/2014
     * Update on: 12/March/2014   
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : This function is use to update and insert created categories. 
     */
    public function insertUpdateCategories() {
        $categories_name = $this->input->post('categories_name');
        $categories_ids = $this->input->post('categories_ids');
        $update_categories = explode("|", $this->input->post('update_categories'));
	$data['category_name']= $categories_name;
	$data['categories_id']= $categories_ids;
        if (empty($categories_name)) {
            $this->session->set_flashdata('ERROR', 'Please enter category name');
            redirect(base_url() . 'sears');
        }
        if (empty($categories_ids)) { 
            $this->session->set_flashdata('ERROR', 'Please enter category ids seprated by ,');
            redirect(base_url() . 'sears');
        }
        if (!empty($categories_name) && !empty($categories_ids) && !empty($update_categories)) {
	   	$cId = $update_categories['0'];
		$respose = $this->sears_model->createCategories($data,$cId);
		$msg="Category has beeen updated ". $data['category_name'];
		if($respose){
                    $this->session->set_flashdata('SUCCESS', $msg);
                    redirect(base_url() . 'sears');
		}
	}
        if (!empty($categories_name) && !empty($categories_ids)) {
	$cId = 'new';
	$respose = $this->sears_model->createCategories($data,$cId);
	$msg="Category ".$data['category_name']." has beeen created.";
		if($respose){
                    $this->session->set_flashdata('SUCCESS', $msg);
                    redirect(base_url() . 'sears');
		}

	}
   }

/*
     * Name : fetchProductsFromSearsKmart
     * Create on: 19/March/2014
     * Update on: 19/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : This function is use fetchProductsFromSearsKmart according to selection of categories. 
     */
    public function fetchProductsFromSearsKmart() {
         $storeName = explode('|', $this->input->post('storeName'));
      	 $parentCategories = explode("|",$this->input->post('parentCategories'));
         $childCategories = explode("|",$this->input->post('childCategories'));
         $subChildCategories = explode("|",$this->input->post('subChildCategories'));
         $categories_ids = $this->input->post('categories_ids');
	 $magentoParentCategories = explode("|",$this->input->post('magentoParentCategories'));
         $magentoLevel2Categories = explode("|",$this->input->post('magentoLevel2Categories'));
         $magentoLevel3Categories = explode("|",$this->input->post('magentoLevel3Categories'));
         $magentoLevel4Categories = explode("|",$this->input->post('magentoLevel4Categories'));
 	
       if (empty($categories_ids)) {
            $this->session->set_flashdata('ERROR', 'Please enter magento categorires IDs into textbox.');
            redirect(base_url() . 'sears/categoryMapping');
        }

       if (empty($parentCategories)) {
            $this->session->set_flashdata('ERROR', 'Please select category.');
            redirect(base_url() . 'sears/categoryMapping');
        }
        if (empty($storeName)) {
            $this->session->set_flashdata('ERROR', 'Please choose store name.');
            redirect(base_url() . 'sears/categoryMapping');
        }

	if(isset($parentCategories[1]) && isset($childCategories[1]) && isset($subChildCategories[1])) {
	 	 $searsKmartCategorystring = $parentCategories[1]."(".$parentCategories[0].")"."#".$childCategories[1]."(".$childCategories[0].")"."#".$subChildCategories[1]."(".$subChildCategories[0].")";
	} else if(isset($parentCategories[1]) && isset($childCategories[1])) {
		 $searsKmartCategorystring = $parentCategories[1]."(".$parentCategories[0].")"."#".$childCategories[1]."(".$childCategories[0].")";
	} else {
		 $searsKmartCategorystring = $parentCategories[1]."(".$parentCategories[0].")";
	}

	if(isset($magentoParentCategories[2]) && isset($magentoLevel2Categories[2]) && isset($magentoLevel3Categories[2]) && isset($magentoLevel4Categories[2])) { 
	 	 $magentoCategoryString = $magentoParentCategories[2]."(".$magentoParentCategories[1].")"."#".$magentoLevel2Categories[2]."(".$magentoLevel2Categories[1].")"."#".$magentoLevel3Categories[2]."(".$magentoLevel3Categories[1].")"."#".$magentoLevel4Categories[2]."(".$magentoLevel4Categories[1].")";
	} else if(isset($magentoParentCategories[2]) && isset($magentoLevel2Categories[2]) && isset($magentoLevel3Categories[2])) {
 	  	 $magentoCategoryString = $magentoParentCategories[2]."(".$magentoParentCategories[1].")"."#".$magentoLevel2Categories[2]."(".$magentoLevel2Categories[1].")"."#".$magentoLevel3Categories[2]."(".$magentoLevel3Categories[1].")";
	} else if(isset($magentoParentCategories[2]) && isset($magentoLevel2Categories[2])) {
 		 $magentoCategoryString = $magentoParentCategories[2]."(".$magentoParentCategories[1].")"."#".$magentoLevel2Categories[2]."(".$magentoLevel2Categories[1].")";
	} else {
 		 $magentoCategoryString = $magentoParentCategories[2]."(".$magentoParentCategories[1].")";
	}

	if(isset($storeName) && isset($parentCategories) && isset($childCategories) && isset($subChildCategories)) {
         $categoriesdata=str_replace("&","%26",$parentCategories[1]."|". $childCategories[1]."|".$subChildCategories[1]);
	} else if(isset($storeName) && isset($parentCategories) &&  isset($childCategories)) {
         $categoriesdata=str_replace("&","%26",$parentCategories[1]."|". $childCategories[1]);
	} else if(isset($storeName) && isset($parentCategories)) {
         $categoriesdata=str_replace("&","%26",$parentCategories[1]);
	} else {
        $this->session->set_flashdata('ERROR', 'Please select store and categories correctlly.');
        redirect(base_url() . 'sears/categoryMapping');
	
	}
        $searsapiKey = SEARSAPIKEY;

 if (!empty($searsapiKey) && !empty($storeName) && !empty($categoriesdata)) {
            $categoryId = $categories_ids;
            /* Call API */
             $url = "http://api.developer.sears.com/v2.1/products/browse/products/" .$storeName[0]. "/json/?category=".$categoriesdata."&apikey=$searsapiKey";
            /*  Initiate curl  */
            $ch = curl_init();
            /* Disable SSL verification */
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            /* Will return the response, if false it print the response */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            /* Set the url */
            curl_setopt($ch, CURLOPT_URL, $url);
            /* Execute */
            $jsonResult = curl_exec($ch);
	    curl_close($ch);
            $result = json_decode($jsonResult, true);

            if (isset($result['SearchResults']) && !empty($result['SearchResults'])) {
                if (isset($result['SearchResults']['Products']) && !empty($result['SearchResults']['Products'])) {
                    $data = array();
                    $insert = 0;
                    $update = 0;

                    foreach ($result['SearchResults']['Products'] as $key => $val) {
                        if (isset($val['Id']['PartNumber'])) {
                            $data['part_number'] = trim($storeName[1].'-'.$val['Id']['PartNumber']);
                        }
                        if (isset($val['Id']['CatEntryId'])) {
                            $data['cat_entry_id'] = $val['Id']['CatEntryId'];
                        }

                        if (isset($val['Id']['MfgPartNumber'])) {
                            $data['mfg_part_number'] = $val['Id']['MfgPartNumber'];
                        }

			if (isset($val['Description']['Name']) && isset($storeName) && $val['Id']['PartNumber']) {
				$productName=trim($val['Description']['Name']);		
				/* convert string */
				$convertedProductName = $this->productUrlString($productName); 
            		   /* Creating Sears products url */
                            if($storeName[0]==trim("sears")){
			    	$data['product_url'] = trim("http://www.sears.com/".$convertedProductName."/p-".$val['Id']['PartNumber']);
				$data['retailer_name'] = trim("sears");
			    }
                           /* Creating Kmart products url */
			    if($storeName[0]==trim("kmart")) {
			    	$data['product_url'] = trim("http://www.kmart.com/".$convertedProductName."/p-".$val['Id']['PartNumber']);
				$data['retailer_name'] = trim("kmart");
			    }
			}

			if(isset($productExtraDeatils['short_description'])) {
                           $data['short_description'] = $productExtraDeatils['short_description'];
			}
			if(isset($productExtraDeatils['long_description'])) {
                            $data['long_description'] = $productExtraDeatils['long_description'];
			}
			if(isset($productExtraDeatils['product_images'])) {
                            $data['product_images'] = $productExtraDeatils['product_images'];
			}

			if (isset($val['Description']['Name'])) {
                            $data['product_name'] = $val['Description']['Name'];
                        }
                        if (isset($val['Description']['BrandName'])) {
                            $data['brand_name'] = $val['Description']['BrandName'];
                        }
                        if (isset($val['Description']['ImageURL'])) {
                            $data['image_url'] = $val['Description']['ImageURL'];
                        }
                        if (isset($val['Description']['ReviewRating']['Rating']) && !empty($val['Description']['ReviewRating']['Rating'])) {
                            $data['rating'] = $val['Description']['ReviewRating']['Rating'];
                        }
                        if (isset($val['Price']['DisplayPrice'])) {
                            $data['display_price'] = $val['Price']['DisplayPrice'];
                        }
                        if (isset($val['Price']['CutPrice'])) {
                            $data['cut_price'] = $val['Price']['CutPrice'];
                        }
                        if (isset($val['Availability']['SaleIndicator'])) {
                            $data['sale_indicator'] = $val['Availability']['SaleIndicator'];
                        }
                        if (isset($val['Availability']['StockIndicator'])) {
                            $data['stock_indicator'] = $val['Availability']['StockIndicator'];
                        }

			$productExtraDeatils = $this->saveProductInformation($storeName[0],$val['Id']['PartNumber']); 
			/* Function to insert products details*/
                        $data['category_id'] = $categoryId;
                        $data['vendor_id'] = $storeName[1]; 
                        /* Update data into database */
                        $respose = $this->sears_model->saveProduct($data);
                        if ($respose == 1) {
                            $insert++;
                        } elseif ($respose == 0) {
                            $update++;
                        }
                    }
                    if ($insert <= 1) {
                        $insert = "$insert product is";
                    } else {
                        $insert = "$insert products are";
                    }
                    if ($update <= 1) {
                        $update = "$update product is";
                    } else {
                        $update = "$update products are";
                    }
                    $msg = "$insert  inserted and $update updated.";
                    $this->session->set_flashdata('SUCCESS', $msg);
		    $this->sears_model->settMappedcategoyList($magentoCategoryString,$searsKmartCategorystring,$categories_ids,$storeName[0]);
                    redirect(base_url() . 'sears/categoryMapping');
                } else {
                    $this->session->set_flashdata('ERROR', 'Products not found. Please try again!');
                    redirect(base_url() . 'sears/categoryMapping');
                }
            }
        }
    }

    /*
     * Name : index
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description : This function is used save all search products  
     */

    public function saveProducts() {
        $storeName = explode('|', $this->input->post('storeName'));
        $categories = $this->input->post('categories');
        if (empty($categories)) {
            $this->session->set_flashdata('ERROR', 'Please select category');
            redirect(base_url() . 'sears');
        }
        if (empty($storeName)) {
            $this->session->set_flashdata('ERROR', 'Please choose store name');
            redirect(base_url() . 'sears');
        }
        /* Sears API key  */
        $searsapiKey = SEARSAPIKEY;

        if (!empty($searsapiKey) && !empty($storeName) && !empty($categories)) {
            $cat = explode('|', $categories);
            $categoryName = $cat[1];
            $categoryId = $cat[0];
            /* Call API */
            $url = "http://api.developer.sears.com/v2.1/products/search/".$storeName[0]."/json/keyword/" . $categoryName . "?apikey=$searsapiKey";
            /*  Initiate curl  */
            $ch = curl_init();
            /* Disable SSL verification */
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            /* Will return the response, if false it print the response */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            /* Set the url */
            curl_setopt($ch, CURLOPT_URL, $url);
            /* Execute */
            $jsonResult = curl_exec($ch);
	    curl_close($ch);
            $result = json_decode($jsonResult, true);
            if (is_array($result['SearchResults']) && !empty($result['SearchResults'])) {
                if (is_array($result['SearchResults']['Products']) && !empty($result['SearchResults']['Products'])) {
                    $data = array();
                    $insert = 0;
                    $update = 0;
                    foreach ($result['SearchResults']['Products'] as $key => $val) {
                        if (isset($val['Id']['PartNumber'])) {
                            $data['part_number'] = trim($storeName[1].'-'.$val['Id']['PartNumber']);
                        }
                        if (isset($val['Id']['CatEntryId'])) {
                            $data['cat_entry_id'] = $val['Id']['CatEntryId'];
                        }

                        if (isset($val['Id']['MfgPartNumber'])) {
                            $data['mfg_part_number'] = $val['Id']['MfgPartNumber'];
                        }

			if (isset($val['Description']['Name']) && isset($storeName) && $val['Id']['PartNumber']) {
				$productName=trim($val['Description']['Name']);		
				/* convert string */
				$convertedProductName = $this->productUrlString($productName); 
            		   /* Creating Sears products url */
                            if($storeName[0]==trim("sears")){
			    	$data['product_url'] = trim("http://www.sears.com/".$convertedProductName."/p-".$val['Id']['PartNumber']);
				$data['retailer_name'] = trim("sears");
			    }
                           /* Creating Kmart products url */
			    if($storeName[0]==trim("kmart")) {
			    	$data['product_url'] = trim("http://www.kmart.com/".$convertedProductName."/p-".$val['Id']['PartNumber']);
				$data['retailer_name'] = trim("kmart");
			    }
			}

			if (isset($val['Description']['Name'])) {
                            $data['product_name'] = $val['Description']['Name'];
                        }
                        if (isset($val['Description']['BrandName'])) {
                            $data['brand_name'] = $val['Description']['BrandName'];
                        }
                        if (isset($val['Description']['ImageURL'])) {
                            $data['image_url'] = $val['Description']['ImageURL'];
                        }
                        if (isset($val['Description']['ReviewRating']['Rating']) && !empty($val['Description']['ReviewRating']['Rating'])) {
                            $data['rating'] = $val['Description']['ReviewRating']['Rating'];
                        }
                        if (isset($val['Price']['DisplayPrice'])) {
                            $data['display_price'] = $val['Price']['DisplayPrice'];
                        }
                        if (isset($val['Price']['CutPrice'])) {
                            $data['cut_price'] = $val['Price']['CutPrice'];
                        }
                        if (isset($val['Availability']['SaleIndicator'])) {
                            $data['sale_indicator'] = $val['Availability']['SaleIndicator'];
                        }
                        if (isset($val['Availability']['StockIndicator'])) {
                            $data['stock_indicator'] = $val['Availability']['StockIndicator'];
                        }
                        $data['category_id'] = $categoryId;
                        $data['vendor_id'] = $storeName[1]; /* Currently vendor id is hard coded */
                        /* Update data into database */
                        $respose = $this->sears_model->saveProduct($data);
                        if ($respose == 1) {
                            $insert++;
                        } elseif ($respose == 0) {
                            $update++;
                        }
                    }
                    if ($insert <= 1) {
                        $insert = "$insert product is";
                    } else {
                        $insert = "$insert products are";
                    }
                    if ($update <= 1) {
                        $update = "$update product is";
                    } else {
                        $update = "$update products are";
                    }
                    $msg = "$insert  inserted and $update updated.";
                    $this->session->set_flashdata('SUCCESS', $msg);
                    redirect(base_url() . 'sears');
                } else {
                    $this->session->set_flashdata('ERROR', 'Products not found. Please try again!');
                    redirect(base_url() . 'sears');
                }
            }
        }
    }
	
   /* API to fetch product details */
    public function saveProductDetails($retailername,$productPartNumber) { 
	    $searsapiKey = SEARSAPIKEY;
            $url = "http://api.developer.sears.com/v2.1/products/details/".$retailername."/json/".$productPartNumber."?apikey=$searsapiKey";
	   // $url= "http://api.developer.sears.com/v2.1/products/details/kmart/json/027VA66529912P?apikey=NfrmXqgKlTbBnPZeUYanttwkeLlXAfVY";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url); 
            $jsonResult = curl_exec($ch);
	    curl_close($ch);
            $result = json_decode($jsonResult, true);
            $data=array();

    if (isset($result['ProductDetail']['SoftHardProductDetails']) && !empty($result['ProductDetail']['SoftHardProductDetails'])) {
    $data['ResponseCode'] = $result['ProductDetail']['StatusData']['ResponseCode'];
	$data['short_description'] = str_replace(array("<![CDATA[","]]>")," ",$result['ProductDetail']['SoftHardProductDetails']['Description']['shortdescription']);
	 $data['long_description'] = str_replace(array("<![CDATA[","]]>")," ",$result['ProductDetail']['SoftHardProductDetails']['Description']['longdescription']);
       if(isset($result['ProductDetail']['SoftHardProductDetails']['Description']['Images']['ImageURLs']) && !empty($result['ProductDetail']['SoftHardProductDetails']['Description']['Images']['ImageURLs']) ) {
	 $productImagesUrl="";
		foreach($result['ProductDetail']['SoftHardProductDetails']['Description']['Images']['ImageURLs']['ImageURL'] as $multipleimages) {
 		$productImagesUrl.= str_replace(array("<![CDATA[","]]>")," ",$multipleimages)."||";
	         }
	         $data['product_images'] = substr($productImagesUrl, 0, -2);
		} else {
		  $data['product_images'] = str_replace(array("<![CDATA[","]]>")," ",$result['ProductDetail']['SoftHardProductDetails']['Description']['Images']['MainImageUrl']);
		}
if(isset($data)) {
$returndata= array('short_description'=>$data['short_description'],'long_description'=>$data['long_description'],'product_images'=>$data['product_images'], 'product_exist'=>$data['ResponseCode']);
return $returndata;
}
else {
$returndata= array('short_description'=>"1",'long_description'=>"1",'product_images'=>"1", 'product_exist'=>"1");
return $returndata;
}
	} else {
	$returndata= array('short_description'=>"1",'long_description'=>"1",'product_images'=>"1", 'product_exist'=>"1");
return $returndata;
}
   } 

    /*Funtion to check and update product information*/
    public function saveProductInformation($retailername,$partnumber) {
    return $this->saveProductDetails($retailername,$partnumber);
       // $getProducts=$this->sears_model->getSaveproductsToInsert();
	//if(!empty($getProducts) && is_array($getProducts)){
	/* Loop to fetched products from the database*/
		//foreach($getProducts as $productdeatils) {
			//if($saveProductDetails = $this->saveProductDetails($productdeatils->retailer_name,$productdeatils->part_number)) {
			//$this->sears_model->updateProductDescription($saveProductDetails,$productdeatils->part_number);
			//} 
	
		//}
	//}
    }

    /* function to call magento API for insetion of products */

    public function magentoAPI() {
        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        );

        $proxy = new SoapClient('http://www.peachyhub.com/api/v2_soap?wsdl=1', $options);
        $sessionId = $proxy->login(APIUSER, APIPASSWORD);
        /* Display all product list */
        //$productList = $proxy->catalogProductList($sessionId);
        $attributeSets = $proxy->catalogProductAttributeSetList($sessionId);
        $attributeSet = current($attributeSets);
        $getProducts=$this->sears_model->getSaveProducts();

	if(isset($getProducts) && count($getProducts)>1) {
	/* Loop to fetched products from the database*/
	foreach($getProducts as $productdeatils) {
	/* To check blank values */
	if($productdeatils->cut_price==0) { 
	$displaPrice = $productdeatils->display_price;
	} else {
	$displaPrice = $productdeatils->cut_price;
	}
	/* To check blank short description  */
	if(strlen($productdeatils->short_description)>1) { 
        	$productShortDescription = $productdeatils->short_description;
	} else {
            	$productShortDescription = $productdeatils->product_name; 
	}
	/* To check blank long description */
	if(strlen($productdeatils->long_description)>1) { 
        	$productLongDescription = $productdeatils->long_description;
	} else {
                    $productLongDescription = $productdeatils->product_name;
	}
	/* To check blank images string */
	if(strlen($productdeatils->product_images)>1) { 
        $productImageString = $productdeatils->product_images;
	} else {
       $productImageString = $productdeatils->image_url;
	}
            
	/* function to update magento inserted products*/	
	$this->sears_model->updateMagentoInsertedProducts($productdeatils->part_number);
	if(isset($productdeatils->category_id)){ 
	$categoryIdsMagento = $productdeatils->category_id;
	} else { $categoryIdsMagento = "2"; }
        $result = $proxy->catalogProductCreate($sessionId, 'simple', $attributeSet->set_id, $productdeatils->part_number, array(
            'categories' => explode(",",$categoryIdsMagento),
            'name' => $productdeatils->product_name,
            'description' => $productLongDescription,
            'short_description' => $productShortDescription,
            'weight' => '10',         
            'status' => '1',
	    'url_key' => $productdeatils->product_name,
            'url_path' => $productdeatils->product_name,
            'visibility' => '4',
            'price' => $displaPrice,
            'tax_class_id' => 0,
            'meta_title' => $productdeatils->product_name,
            'meta_keyword' => $productdeatils->product_name,
            'meta_description' => $productdeatils->product_name,
            'is_in_stock' => $productdeatils->stock_indicator,
            'additional_attributes' => array('single_data' => array( 
						array('key' => 'url_transfer', 'value' => $productdeatils->product_url), 
						array('key' => 'special_price', 'value' => $productdeatils->display_price),
						array('key' => 'brand', 'value' => $productdeatils->brand_name),
						array('key' => 'rating', 'value' => $productdeatils->rating),
                                                array('key' => 'cat_entity_id', 'value' => $productdeatils->cat_entity_id),
                                                array('key' => 'mfg_part_numbers', 'value' => $productdeatils->mfg_part_number),
                                                array('key' => 'sale_indicator', 'value' => $productdeatils->sale_indicator),
						array('key' => 'mfg_part_number', 'value' => $productdeatils->mfg_part_number)
	    )) 
        ));
	/* Code to creating product images*/

	if($productdeatils->magento_image_status==0) {
	$productImages = $productImageString;
	 	if(!empty($productImages)) {
	       $this->sears_model->updateMagentoInsertedImageStatus($productdeatils->part_number);
		
		$explotedImages = explode('||',$productImages);
            
			foreach($explotedImages as $singleImages) {
			$image = new Imagick(trim($singleImages));
   			$image->setImageFormat('jpeg');
    	 		$documentRoot = $_SERVER['DOCUMENT_ROOT'].'/productimage/image/productimages.jpeg';
   			$image->writeImage($documentRoot);
       			$newFile = array(
            		'content' => base64_encode(file_get_contents(trim($documentRoot))),
            		'mime' => 'image/jpeg'
        	);
     		  $result = $proxy->catalogProductAttributeMediaCreate($sessionId, $productdeatils->part_number, array('file' => $newFile, 'label' => $productdeatils->product_name, 'position' => '0', 'types' => array('small_image', 'thumbnail', 'image'), 'exclude' => 0));
			}
      		}
	}


	} echo "Products Inserted sucessfully.";
      } else { echo "No new product found for insertion.";}
    }


     /*
     * Name : index
     * Create on: 06/March/2014
     * Update on: 06/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : function to format product name string 
     */
    public function productUrlString($productName) {
			$convertOnewSpace = preg_replace('/\s{1,}/', '-', $productName);;
			$convertTwoHiphen = str_replace("/", "-", $convertOnewSpace);
			$convertedProductName = strtolower($convertTwoHiphen);
	   return $convertedProductName;
    }


     /*
     * Name : index
     * Create on: 06/March/2014
     * Update on: 06/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : function to convert <!CDATA wordings to blank
     */
    public function convertCdataToString($cdataString) {
	$withOutCdataString =  str_replace(array("<![CDATA[","]]>")," ",$cdataString);
	return $withOutCdataString;
    }
     /*
     * Name : index
     * Create on: 31/March/2014
     * Update on: 31/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : function to check and remove out dated products from magento.
     */

 public function checkOutdatedProductsFromAPI() {
        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        );
        $proxy = new SoapClient('http://www.peachyhub.com/api/v2_soap?wsdl=1', $options);
        $sessionId = $proxy->login(APIUSER, APIPASSWORD);
        /* Display all product list */
	//$this->sears_model->cleanDatabaseTable('tbl_manage_outdated_product');
     	$productList = $proxy->catalogProductList($sessionId);
	if(isset($productList)){
	foreach($productList as $sku) {
	//$this->sears_model->checkAndInsertProductsFromMagento($sku->sku);
	$outDatedList = $this->sears_model->checkProductStatusFromAPI($sku->sku);
        $correctSku = explode("-", $sku->sku);
	    $searsapiKey = SEARSAPIKEY;
            $url = "http://api.developer.sears.com/v2.1/products/details/".$outDatedList[0]->retailer_name."/json/".$correctSku[1]."?apikey=$searsapiKey"; 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url); 
            $jsonResult = curl_exec($ch);
	    curl_close($ch);
            $result = json_decode($jsonResult, true);
       	    if(isset($result['statusData']['ResponseCode']) && $result['statusData']['ResponseCode']==1){
               	$this->sears_model->deleteProductFromDetails(trim($outDatedList[0]->part_number));
		$proxy->catalogProductDelete($sessionId, trim($outDatedList[0]->part_number));
		}
			} echo "Product Updated Successfully.";
	} else { echo "No action required"; } 

    }

     /*
     * Name : index
     * Create on: 18/March/2014
     * Update on: 18/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : Function is used to get all the categories names from sears and kmart.
     */

 public function fetchSearsKmartCategories() {
 	    $searsapiKey =  SEARSAPIKEY;
           // $url = "http://api.developer.sears.com/v2.1/categories/details/".$retailername."/json/".$productPartNumber."?apikey=$searsapiKey";
	    $url= "http://api.developer.sears.com/v2.1/products/browse/categories/Sears/json?category=&apikey=".$searsapiKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url); 
            $jsonResult = curl_exec($ch);
	    curl_close($ch);
            $result = json_decode($jsonResult, true);
 	   if($result['SearchResults']['Status']['ResponseCode']==0){
		$countCategories=0;
		foreach($result['SearchResults']['Verticals'] as $parentcategories) {
		$VerticalNameCategories = trim($parentcategories['VerticalName']);
		$parentCategory = $this->sears_model->searsKmartParentCategoires($VerticalNameCategories); // Insert Parent categories
		$parentCounter=$countCategories++;
		$url= "http://api.developer.sears.com/v2.1/products/browse/categories/Sears/json?category=".str_replace("&","%26",$VerticalNameCategories)."&apikey=".$searsapiKey;
            	$ch = curl_init();
           	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
           	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          	curl_setopt($ch, CURLOPT_URL, $url); 
           	$jsonResult = curl_exec($ch);
	  	curl_close($ch);
           	$result = json_decode($jsonResult, true);
 			if($result['SearchResults']['Status']['ResponseCode']==0 && isset($result['SearchResults']['NavGroups'][0]['ShopByCategories']))  {
				$subcount=0;
				foreach($result['SearchResults']['NavGroups'][0]['ShopByCategories'] as $subcategories) {
				$childCounter=$subcount++;
				$childCategory = $this->sears_model->searsKmartChildCategoires($subcategories['CategoryName'], $parentCategory); // Insert child category 
				$url= "http://api.developer.sears.com/v2.1/products/browse/categories/Sears/json?category=".str_replace("&","%26",$VerticalNameCategories)."|".str_replace("&","%26",$subcategories['CategoryName'])."&apikey=".$searsapiKey;
            			$ch = curl_init();
           			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
           			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          			curl_setopt($ch, CURLOPT_URL, $url); 
           			$jsonResult = curl_exec($ch);
	  			curl_close($ch);
           			$result = json_decode($jsonResult, true);
 					if($result['SearchResults']['Status']['ResponseCode']==0 && isset($result['SearchResults']['NavGroups'][0]['ShopByCategories']))  {		$secoundLevelcout=0;
					foreach($result['SearchResults']['NavGroups'][0]['ShopByCategories'] as $subcategories) {
					$secoundLevelcoutchildCounter=$secoundLevelcout++;
					$this->sears_model->searsKmartSubChildCategoires($subcategories['CategoryName'], $childCategory);
					}
				}


				}
			}

		}
	}
    }

     /*
     * Name : index
     * Create on: 19/March/2014
     * Update on: 19/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : Funcation to getchild categories data.
     */
    public function ajaxCallForChildCategories() {
		$id = trim($this->input->post('parentCatID'));
		$idOnly=explode("|",$id);
	        echo json_encode($this->sears_model->getChildCategories($idOnly[0]));
    }

    public function ajaxCallForSubCategories() {
		$id = trim($this->input->post('parentCatID'));
		$idOnly=explode("|",$id);
	        echo json_encode($this->sears_model->getSubChildCategories($idOnly[0]));
    }

	/*
     * Name : index
     * Create on: 25/March/2014
     * Update on: 25/March/2014
     * Version  : 1.0
     * Created By: KP-SIPL
     * Description : Funcation to getchild categories data for magento categories.
     */
    public function ajaxCallFormagentoLevel1() {
		$id = trim($this->input->post('magentoParentCatID'));
		$idOnly=explode("|",$id);
	        echo json_encode($this->sears_model->getMagentoLevel2Categoires($idOnly[0]));
    }

    public function ajaxCallFormagentoLevel2() {
		$id = trim($this->input->post('magentoParentCatID'));
		$idOnly=explode("|",$id);
	        echo json_encode($this->sears_model->getMagentoLevel3Categoires($idOnly[0]));
    }
    public function ajaxCallFormagentoLevel3() {
		$id = trim($this->input->post('magentoParentCatID'));
		$idOnly=explode("|",$id);
	        echo json_encode($this->sears_model->getMagentoLevel4Categoires($idOnly[0]));
    }
     /*
     * Name : index
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description : function to update existing inserted products  
     */
    public function magentoUpdateAPI() {
        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        );
        $proxy = new SoapClient('http://www.peachyhub.com/api/v2_soap?wsdl=1', $options);
        $sessionId = $proxy->login(APIUSER, APIPASSWORD);
        /* Display all product list */
        $productList = $proxy->catalogProductList($sessionId);
        $attributeSets = $proxy->catalogProductAttributeSetList($sessionId);
        $attributeSet = current($attributeSets);
        $getProducts=$this->sears_model->getSaveproductsUpdate();
        	if(isset($getProducts) && count($getProducts)>1) {
	/* Loop to fetched products from the database*/
	foreach($getProducts as $productdeatils) {

	if($productdeatils->cut_price==0) {
	$displaPrice = $productdeatils->display_price;
	} else {
	$displaPrice = $productdeatils->cut_price; 
	}

	/* To check blank short description  */
	if(strlen($productdeatils->short_description)>1) { 
        	$productShortDescription = $productdeatils->short_description;
	} else {
            	$productShortDescription = $productdeatils->product_name;
	}
	/* To check blank long description */
	if(strlen($productdeatils->long_description)>1) { 
            	$productLongDescription = $productdeatils->long_description;
	} else {
            	$productLongDescription = $productdeatils->product_name;
	}
	/* To check blank images string */
	if(strlen($productdeatils->product_images)>1) { 
        	$productImageString = $productdeatils->product_images;
	} else {
            	$productImageString = $productdeatils->image_url;
	}
	if(isset($productdeatils->category_id)){ 
	$categoryIdsMagento = $productdeatils->category_id;
	} else { $categoryIdsMagento = "2"; }
        $result = $proxy->catalogProductUpdate($sessionId,$productdeatils->part_number, array(
            'categories' => explode(",", $categoryIdsMagento),
            'name' => $productdeatils->product_name,  
            'description' => $productLongDescription,
            'short_description' => $productShortDescription,
            'price' => $displaPrice,
            'is_in_stock' => $productdeatils->stock_indicator,
	    'additional_attributes' => array('single_data' => array( 
						array('key' => 'url_transfer', 'value' => $productdeatils->product_url), 
						array('key' => 'special_price', 'value' => $productdeatils->display_price),
						array('key' => 'brand', 'value' => $productdeatils->brand_name),
						array('key' => 'rating', 'value' => $productdeatils->rating),
                                                array('key' => 'cat_entity_id', 'value' => $productdeatils->cat_entity_id),
                                                array('key' => 'mfg_part_numbers', 'value' => $productdeatils->mfg_part_number),
                                                array('key' => 'sale_indicator', 'value' => $productdeatils->sale_indicator),
						array('key' => 'mfg_part_number', 'value' => $productdeatils->mfg_part_number)
	    )) 

        ));
	/* Code to creating product images*/   
	
	$this->sears_model->updateFlagForMagentoUpdatedProducts($productdeatils->part_number);
	if($productdeatils->magento_image_status==0)  {

	$productImages = $productImageString;
	 	if(!empty($productImages)) {
		$explotedImages = explode('||',$productImages);
			$this->sears_model->updateMagentoInsertedImageStatus($productdeatils->part_number);
			foreach($explotedImages as $singleImages) {
			$image = new Imagick(trim($singleImages));
   			$image->setImageFormat('jpeg');
    	 		$documentRoot = $_SERVER['DOCUMENT_ROOT'].'/productimage/image/productimages.jpeg';
   			$image->writeImage($documentRoot);
       			$newFile = array(
            		'content' => base64_encode(file_get_contents(trim($documentRoot))),
            		'mime' => 'image/jpeg'
        	);
     		  $result = $proxy->catalogProductAttributeMediaCreate($sessionId, $productdeatils->part_number, array('file' => $newFile, 'label' => $productdeatils->product_name, 'position' => '0', 'types' => array('small_image', 'thumbnail', 'image'), 'exclude' => 0));

		      }
      		}
	}
		} echo "Products Updated sucessfully.";
	} else { echo "No updated prodcuts found.";}
	}


}

