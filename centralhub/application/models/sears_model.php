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
     * File Name: getCategories
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for get all categories from category table
     */

    public function getCategories() {
        $this->db->select('*');
        $this->db->from('tbl_category');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }

    /*
     * File Name: saveProduct
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for save product details
     */

    public function saveProduct($data = array()) {
        if (!empty($data['part_number'])) {
            /* Check product is already exist */
            $this->db->select('product_id,part_number,cat_entry_id');
            $this->db->from('tbl_product_details');
            $this->db->where('part_number', $data['part_number']);
            $this->db->where('cat_entry_id', $data['cat_entry_id']);
            $this->db->where('mfg_part_number', $data['mfg_part_number']);
            $query = $this->db->get();
            if ($query->num_rows()) {
                /* If product is already exist then update it */
                $result = $query->row();              
                $this->db->where('product_id', $result->product_id);
		$this->db->update('tbl_product_details', $data); 
                return 0;
            }else{
                /* If product is not exist then insert new */
                $data['created_date'] = date('Y-m-d H:m:s');
                $this->db->insert('tbl_product_details', $data);
                return 1;
            }
        }
        return false;
    }
    
    /*
     * File Name: saveApiUserDetails
     * Create on: 24/02/2014
     * Update on: 24/02/2014
     * Version  : 1.0
     * Created By: PB-SIPL
     * Description: This function is used for save API called user information into database 
     */
    public function saveVendorDetails($data){
        if(is_array($data) && !empty($data)){
            
        }
    }

}
