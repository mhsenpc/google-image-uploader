<?php
	include('phpqrcode/qrlib.php'); 
    
    $qrtext=$_REQUEST['qrtext'];
    
    $qrfile='qrcodes/'. substr( md5( microtime() * time()),1,5) .".png";
    QRcode::png($qrtext, $qrfile ); // creates file 
    
    echo $qrfile;
    
?>