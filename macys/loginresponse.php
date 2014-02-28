<?php /*
Filename: loginresponse.php
Function:  
Auther  : SIPL Developer
Created : 17-Feb-2014
Modified: 
*/ ?>
sdffffffffffffffffff
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "www.google.com");
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch)
echo $data;
?>
