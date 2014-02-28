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

    /*
     * Name : index
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description : This function is used save all search products  
     */

    public function saveProducts() {
        $storeName = $this->input->post('storeName');
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
        $searsapiKey = trim("NfrmXqgKlTbBnPZeUYanttwkeLlXAfVY");

        if (!empty($searsapiKey) && !empty($storeName) && !empty($categories)) {
            $cat = explode('|', $categories);
            $categoryName = $cat[1];
            $categoryId = $cat[0];
            /* Call API */
            $url = "http://api.developer.sears.com/v2.1/products/search/" . $storeName . "/json/keyword/" . $categoryName . "?apikey=$searsapiKey";
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
            $result = json_decode($jsonResult, true);
//            echo "<pre>";
//             print_r($result);             

            if (is_array($result['SearchResults']) && !empty($result['SearchResults'])) {
                if (is_array($result['SearchResults']['Products']) && !empty($result['SearchResults']['Products'])) {
                    $data = array();
                    $insert = 0;
                    $update = 0;
                    foreach ($result['SearchResults']['Products'] as $key => $val) {
                        if (isset($val['Id']['PartNumber'])) {
                            $data['part_number'] = $val['Id']['PartNumber'];
                        }
                        if (isset($val['Id']['CatEntryId'])) {
                            $data['cat_entry_id'] = $val['Id']['CatEntryId'];
                        }
                        if (isset($val['Id']['MfgPartNumber'])) {
                            $data['mfg_part_number'] = $val['Id']['MfgPartNumber'];
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
                        $data['vendor_id'] = 1; /* Currently vendor id is hard coded */
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

    public function magentoAPI() {
        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        );

        $proxy = new SoapClient('http://www.peachyhub.com/api/v2_soap?wsdl=1', $options);
        $sessionId = $proxy->login('testuser', 'test123');
        /* Display all product list */
       $productList = $proxy->catalogProductList($sessionId);
       // echo "<pre>";  print_r($productList);
       // die();
        // get attribute set
        $attributeSets = $proxy->catalogProductAttributeSetList($sessionId);
        $attributeSet = current($attributeSets);
      //  echo "<pre>";
       // print_r($attributeSets); // die();
        $result = $proxy->catalogProductCreate($sessionId, 'simple', $attributeSet->set_id, '12546987s', array(
            'categories' => array(2),
            'name' => 'Product name',
            'description' => 'Product description',
            'short_description' => 'Product short description',
            'weight' => '10',         
            'status' => '1',
            'url_key' => 'product-url-key',
            'url_path' => 'product-url-path',
            'visibility' => '4',
	    'url_transfer' => '1234567891',
            'price' => '100',
            'tax_class_id' => 0,
            'meta_title' => 'Product meta title',
            'meta_keyword' => 'Product meta keyword',
            'meta_description' => 'Product meta description',
            'is_in_stock' => 1
        ));
        $result = $proxy->catalogProductUpdate($sessionId,'product_sku', array(
            'categories' => array(2),
            'websites' => array(1),
            'name' => 'Product name',
            'description' => 'Product description',
            'short_description' => 'Product short description',
            'weight' => '10',
            'status' => '1',
            'url_key' => 'product-url-key',
            'url_path' => 'product-url-path',
            'visibility' => '4',
            'price' => '100',
            'tax_class_id' => 0,
            'meta_title' => 'Product meta title',
            'meta_keyword' => 'Product meta keyword',
            'meta_description' => 'Product meta description',
            'is_in_stock' => 1
        ));

        $productId = 18;      
        $newFile = array(
            'content' => base64_encode(file_get_contents("http://c.shld.net/rpx/i/s/i/spin/image/spin_prod_862981412")),
            'mime' => 'image/jpeg'
        );
       // $result = $proxy->catalogProductAttributeMediaCreate($sessionId, $productId, array('file' => $newFile, 'label' => 'New label', 'position' => '50', 'types' => array('small_image', 'thumbnail', 'image'), 'exclude' => 0));


        echo "<pre>";
        print_r($result);
    }

}
