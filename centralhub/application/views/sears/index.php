<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sears Products</title>
    </head>
    <body>
        <div id="container">
            <h1>Search Products and Update </h1>
            <div id="body">               
                <?php
                if ($this->session->flashdata('ERROR')) {
                    echo '<p style="color:red;">' . $this->session->flashdata('ERROR') . '</p> <br />';
                }
                if ($this->session->flashdata('SUCCESS')) {
                    echo '<p style="color:blue;">' . $this->session->flashdata('SUCCESS') . '</p> <br />';
                }
                ?>
                <form method="post" action="<?php echo base_url() . 'sears/saveProducts'; ?>" name="sears">
                    <label>Store Name: </label>
                    <select name="storeName">
                        <option value="Sears">Sears</option>
                        <option value="Kmart">Kmart</option>
                    </select>
                    <label>Select Category: </label>                    
                    <?php
                    $option = array('' => 'Select');
                    if (is_array($categories) && !empty($categories)) {
                        foreach ($categories as $key => $value) {
                            $option[$value->categories_id.'|'.$value->category_name] = $value->category_name;
                        }
                    }
                    echo form_dropdown('categories', $option);
                    ?>                    
                    <input type="submit" name="submit" value="Submit"/>
                </form>
		<div>
                <form method="post" action="<?php echo base_url() . 'sears/insertUpdateCategories'; ?>" name="insertupdate">
    		 <label>Select Existing Category for Update: </label>                    
                    <?php
                    $option = array('' => 'Select');
                    if (is_array($categories) && !empty($categories)) {
                        foreach ($categories as $key => $value) {
                            $option[$value->category_id.'|'.$value->categories_id.'|'.$value->category_name] = $value->category_name;
                        }
                    }
                    echo form_dropdown('update_categories', $option);
                    ?>      
		<br/>   
		<label> Enter Category Name: </label><input type="text" name="categories_name">
		<label> Enter Category Ids from magento seprated by ,: </label><input type="text" name="categories_ids">
	        <input type="submit" name="insert" value="Submit"/>
		</form>

		
		</div>
            </div>
        </div>
    </body>
</html>
