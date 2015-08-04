<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Resize image</title>
      <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
      <!-- Optional theme -->
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css" />
      <!-- Latest compiled and minified JavaScript -->
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
      <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
      <script>

       $(function(){
        $( "#slider" ).slider({
            min:10,
            range: "min",
            max: 1000,
            value:72,
            slide: refreshsize,
            change: refreshsize
            
            });
       })  
        var original_image="<?php echo $_REQUEST['img']; ?>";
        refreshsize();
        $(function(){
            $("#txtimage").val(original_image);
        })
        
       
        function refreshsize(){
            $(document).ready(function(){
                
                var slider = $( "#slider" ).slider( "value" ); 
                var newimage= $("#txtimage").val();
                    newimage=original_image.replace("/s72/","/s"+ slider + "/" );
                    $("#imagepreview").attr("src",newimage);
                    $("#txtimage").val(newimage);
                    
                    $("#viewimage").attr("href",newimage);

                    //load qr code
                    $.post( "getqrcode.php", { 'qrtext':$("#txtimage").val() })
                        .done(function( data ) {
                            $("#qrcode").attr("src",data);
                            
                    })
            })
        }
        
      </script>
   </head>
   <body>
      <div class="container">
         <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
               <img id="imagepreview" src="<?php echo $_REQUEST['img']; ?>" />
            </div>
            <div class="col-md-4">
               <img id="qrcode" src="#" />
            </div>
         </div>
         <div style="height: 50px;"></div>
         <div>
            <div id="slider" style="background: #729fcf;" ></div>
         </div>
         <div style="height: 20px;"></div>
         <div class="row">
            <input class="col-md-8 form-control" type="text" id="txtimage" value="" />
         </div>
         <div style="height: 10px;"></div>
         <div class="row">
            <div class="col-md-4">
            <a class="btn btn-success" id="viewimage" target="_blank" >View image</a>
            </div>
         </div>
      </div>
   </body>
</html>