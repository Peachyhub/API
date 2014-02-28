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
                        <option value="sears">Sears</option>
                        <option value="Kmart">Kmart</option>
                    </select>
                    <label>Select Category: </label>                    
                    <?php
                    $option = array('' => 'Select');
                    if (is_array($categories) && !empty($categories)) {
                        foreach ($categories as $key => $value) {
                            $option[$value->category_id.'|'.$value->category_name] = $value->category_name;
                        }
                    }
                    echo form_dropdown('categories', $option);
                    ?>                    
                    <input type="submit" name="submit" value="Submit"/>
                </form>
            </div>
        </div>
    </body>
</html>
