<?php
@include ('uploadlibrary.inc.php');
include('phpqrcode/qrlib.php');

include("curl.php");
$allowedExts = array("gif", "jpeg", "jpg", "png");
$qrfile='qrcodes/'. substr( md5( microtime() * time()),1,5) .".png";

$uploadtype = $_POST['uploadtype'];
if ($uploadtype == 'fromcomputer') {
    
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension =strtolower( end($temp));


    //if is image
    if ((($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/x-png")
    || ($_FILES["file"]["type"] == "image/png"))
    && in_array($extension, $allowedExts)) {
      if ($_FILES["file"]["error"] > 0) {
	    http_response_code(403);
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
      } else {
            //upload to google
            $filename = $_FILES['file']['tmp_name'];

            try{
                //upload file to google
                $allsizes = @UploadInPicasa($filename);
                
                //get full size
                $up_file= $allsizes['thumb72'];
        		$up_file= str_replace("https://","http://",$up_file);
                $up_file= str_replace("/s72/","/s5000/",$up_file);
                
                //get thumb72
				$thumb72=$allsizes['thumb72'];
                $thumb72= str_replace("https://","http://",$thumb72);
                
                //create qr code
                QRcode::png($up_file, $qrfile); // creates file
                
                //send data in json format
                $returndata=array('image'=>$up_file,
                    'thumb72'=>$thumb72,
                    'qrcode'=>$qrfile
                );
                echo json_encode($returndata);
                
                
                
            }
            catch(Exception $e){
				http_response_code(403);
                echo $e;
            }

      }
    } else {
	  http_response_code(403);
      echo "Invalid file";
    }
}
else
{
        //first download to a temp file
        $fn = tempnam('/tmp','hmvm_').".jpg";
        file_put_contents($fn, curl_download($_POST['url']));
        //upload file to google
        $allsizes = @UploadInPicasa($fn);
        
        //get full size
        $up_file= $allsizes['thumb72'];
		$up_file= str_replace("https://","http://",$up_file);
        $up_file= str_replace("/s72/","/s5000/",$up_file);
        
        //get thumb72
		$thumb72=$allsizes['thumb72'];
        $thumb72= str_replace("https://","http://",$thumb72);
        
        //create qr code
        QRcode::png($up_file, $qrfile); // creates file
        
        //send data in json format
        $returndata=array(
            'image'=>$up_file,
            'thumb72'=>$thumb72,
            'qrcode'=>$qrfile
        );
        echo json_encode($returndata);
        unlink($fn);
}


?>