<!DOCTYPE html>
<html lang="en">
    <head>
<script src="<?php echo base_url() .'js/jquery-1.8.3.min.js'; ?>"></script>
        <meta charset="utf-8">
        <title>Categories Mapping</title>
    </head> 
    <body>
        <div id="container">
            <h4>Select categories from magento and according to selection map them into central database by supplying ID into textbox seprated by , </h4>
            <div id="body">               
                <?php
                if ($this->session->flashdata('ERROR')) {
                    echo '<p style="color:red;">' . $this->session->flashdata('ERROR') . '</p> <br />';
                }
                if ($this->session->flashdata('SUCCESS')) {
                    echo '<p style="color:blue;">' . $this->session->flashdata('SUCCESS') . '</p> <br />';
                }
                ?>
		<form method="post" action="<?php echo base_url() . 'sears/fetchProductsFromSearsKmart'; ?>" name="sears">
                    <label>Magento Category: </label>                    
                    <?php $option = array('' => 'Select');
                    if (is_array($magentoParentCategories) && !empty($magentoParentCategories)) {
                        foreach ($magentoParentCategories as $key => $value) {
                            $option[$value->level1_mcategory_id."|".$value->mcategory_id."|".$value->category_name] = $value->category_name."(".$value->mcategory_id.")";
                        }
                    } 
                    echo form_dropdown('magentoParentCategories', $option," ",'id="magentoParentCategories"');
                    ?>  
		<?php 	$option = array('' => 'Select');
		echo form_dropdown('magentoLevel2Categories', $option," ",'id="magentoLevel2Categories"');
		?>
		<?php 	$option = array('' => 'Select');
		echo form_dropdown('magentoLevel3Categories', $option," ",'id="magentoLevel3Categories"');
		?>
		<?php 	$option = array('' => 'Select');
		echo form_dropdown('magentoLevel4Categories', $option," ",'id="magentoLevel4Categories"');
		?>
		<?php echo "<br/>";?>
 <h4>Supply categories Id and insert them into central database by using submit button. </h4>
		<label>Store Name: </label>
                    <select name="storeName">
			<?php foreach($getVendorList as $vendorName) { ?>
                        <option value="<?php echo $vendorName->vendor_name.'|'.$vendorName->vendor_code.'|'.$vendorName->vendor_key; ?>"><?php echo ucwords($vendorName->vendor_name); ?></option>
			<?php } ?>
                    </select>
                    <label>Select Category: </label>                    
                    <?php	
                    $option = array('' => 'Select');
                    if (is_array($parentCategories) && !empty($parentCategories)) {
                        foreach ($parentCategories as $key => $value) {
                            $option[$value->parent_category_id."|".$value->category_name] = $value->category_name."(".$value->parent_category_id.")";
                        }
                    } 
                    echo form_dropdown('parentCategories', $option," ",'id="parentCategories"');
                    ?>   
		<?php 
		$option = array('' => 'Select');
		echo form_dropdown('childCategories', $option," ",'id="childCategories"');
		?>
		<?php 
		$option = array('' => 'Select');
		echo form_dropdown('subChildCategories', $option," ",'id="subChildCategories"');
		?>
		<label>Magento Cat IDs Seprated By , : </label><input type="text" name="categories_ids">
	        <input type="submit" name="insert" value="Submit"/>

		</form>

 
        <script type="text/javascript">
	     /* Function to get chlid categories from child table*/
  	     $(document).ready(function () {
       		 $('#parentCategories').change(function () {
	         var catIdName = $(this).val();
           	 $.ajax({
               	 url: "<?php echo base_url() . 'sears/ajaxCallForChildCategories'; ?>",
               	 async: false,
	         type:"post",
                 dataType: "json",
                 data: { parentCatID:catIdName },
                 success: function(result) {
		 if(result === false){
			alert('No child category found can leave it.');
		 } else {
	         var dropDown='<option value="">Select</option>';
				for(var value in result) { 
					if(result[value]['child_category_id'] != null && result[value]['child_category_id'] !='') 
					dropDown+='<option value="'+result[value]["child_category_id"]+"|"+result[value]["category_name"]+'">'+result[value]["category_name"]+"("+result[value]["child_category_id"]+")"+'</option>'; 
					}
					$('#childCategories').html(dropDown); 
					$('#subChildCategories').html('<option value="">Select</option>'); 
			}
	
                	}
            	})
        	});

	     $('#childCategories').change(function () {
	     /* Function to get sub categories from SubCategories table*/
	     	    var catIdName = $(this).val();
            		$.ajax({
             		   url: "<?php echo base_url() . 'sears/ajaxCallForSubCategories'; ?>",
             		   async: false,
			   type:"post",
                	   dataType: "json",
                           data: { parentCatID:catIdName },
                           success: function(result) {
		 if(result === false){
			alert('No child category found can leave it.');
		 } else {

		           var dropDown='<option value="">Select</option>';
					for(var value in result) { 
						if(result[value]['child_category_id'] != null && result[value]['child_category_id'] !='') 
						dropDown+='<option value="'+result[value]["subchild_category_id"]+"|"+result[value]["sub_category_name"]+'">'+result[value]["sub_category_name"]+"("+result[value]["subchild_category_id"]+")"+'</option>'; 

					}
					$('#subChildCategories').html(dropDown); 
			}


                			}
            			})
        		});

       		 $('#magentoParentCategories').change(function () {
	         var catIdName = $(this).val();
           	 $.ajax({
               	 url: "<?php echo base_url() . 'sears/ajaxCallFormagentoLevel1'; ?>",
               	 async: false,
	         type:"post",
                 dataType: "json",
                 data: { magentoParentCatID:catIdName },
                 success: function(result) {
		 if(result === false){
			alert('No child category found can leave it.');
		 } else {
	         var dropDown='<option value="">Select</option>';
				for(var value in result) { 
					if(result[value]['level2_mcategory_id'] != null && result[value]['level2_mcategory_id'] !='') 
					dropDown+='<option value="'+result[value]["level2_mcategory_id"]+"|"+result[value]["mcategory_id"]+"|"+result[value]["category_name"]+'">'+result[value]["category_name"]+"("+result[value]["mcategory_id"]+")"+'</option>'; 
					}
					$('#magentoLevel2Categories').html(dropDown); 
					$('#magentoLevel3Categories').html('<option value="">Select</option>'); 
					$('#magentoLevel4Categories').html('<option value="">Select</option>'); 
                	}
		}
            	})
        	});

       		 $('#magentoLevel2Categories').change(function () {
	         var catIdName = $(this).val();
           	 $.ajax({
               	 url: "<?php echo base_url() . 'sears/ajaxCallFormagentoLevel2'; ?>",
               	 async: false,
	         type:"post",
                 dataType: "json",
                 data: { magentoParentCatID:catIdName },
                 success: function(result) {
		 if(result === false){
			alert('No child category found can leave it.');
		 } else {
	         var dropDown='<option value="">Select</option>';
				for(var value in result) { 
					if(result[value]['level3_mcategory_id'] != null && result[value]['level3_mcategory_id'] !='') 
					dropDown+='<option value="'+result[value]["level3_mcategory_id"]+"|"+result[value]["mcategory_id"]+"|"+result[value]["category_name"]+'">'+result[value]["category_name"]+"("+result[value]["mcategory_id"]+")"+'</option>'; 
					}
					$('#magentoLevel3Categories').html(dropDown); 
					$('#magentoLevel4Categories').html('<option value="">Select</option>'); 
		}
	
                	}
            	})
        	});


       		 $('#magentoLevel3Categories').change(function () {
	         var catIdName = $(this).val();
           	 $.ajax({
               	 url: "<?php echo base_url() . 'sears/ajaxCallFormagentoLevel3'; ?>",
               	 async: false,
	         type:"post",
                 dataType: "json",
                 data: { magentoParentCatID:catIdName },
                 success: function(result) {
		 if(result === false){
			alert('No child category found can leave it.');
		 } else {
	         var dropDown='<option value="">Select</option>';
				for(var value in result) { 
					if(result[value]['level4_mcategory_id'] != null && result[value]['level4_mcategory_id'] !='') 
					dropDown+='<option value="'+result[value]["level4_mcategory_id"]+"|"+result[value]["mcategory_id"]+"|"+result[value]["category_name"]+'">'+result[value]["category_name"]+"("+result[value]["mcategory_id"]+")"+'</option>'; 
					} 
					$('#magentoLevel4Categories').html(dropDown); 
					$('#subChildCategories').html('<option value="">Select</option>'); 
		  }
                	}
            	})
        	});

	});
	</script>



            </div>

        </div>
<h5>On clicking below links new added products in central database will be added into magento
<div><a href="http://www.peachyhub.com/centralhub/sears/magentoAPI" target="_blank">Click to create products.</a></div>
<?php  if (is_array($getMappedcategoyList) && !empty($getMappedcategoyList)) { ?>
<div align="center">
<table align="center" border="1"> 
<tr><td></td><td></td><td align="center"><h3>Category Mapping Chart</h3> </td><td></td><td></td></tr>
<tr><td>S.No</td><td>Magento Categories</td><td>Magento Mapped ID</td><td>Vendor Name</td><td>Sears/ Kmart Categories</td></tr>
<?php foreach($getMappedcategoyList as $mappedcategoires) { ?>
<tr><td><?php echo $mappedcategoires->mapped_categories_id; ?></td><td><?php echo $mappedcategoires->magento_category_list; ?></td><td><?php echo $mappedcategoires->magento_category_ids; ?></td><td><?php echo $mappedcategoires->vendor_name; ?></td><td><?php echo $mappedcategoires->vendor_category_list; ?></td></tr>
<?php }  ?>	


</tabel>
</div>
<?php } else { echo "No category is mapped"; }?>

    </body>
</html>
