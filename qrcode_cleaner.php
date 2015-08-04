<?php
  if ($handle = opendir('qrcodes')) {

    while (false !== ($file = readdir($handle))) {
        if (filectime("qrcodes/" .$file)< (time()-7200)) {  //2 hours 7200
          unlink("qrcodes/".$file);
        }
    }
  }
?>