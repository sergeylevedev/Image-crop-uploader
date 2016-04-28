<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
</head>
<!--FORMS-->
<body>
<div class="container-fluid">
  <div class="row">
    	<div class="col-sm-4">
		<h1 class="col-sm-4-heading">Crop and Upload your Image!</h1>
		<form id="img-crop-form" action="upload.php" method="post" enctype="multipart/form-data">
			</br>
    			<input id="img-file-input" type="file" class="form-control">
			</br>
			<div id="img-upload-output"></div>
			</br>
			<div id="progress-div"><div id="progress-bar" class="progress-bar" role="progressbar" aria-valuenow="70"
  aria-valuemin="0" aria-valuemax="100" style="width:0%"></div></div>
			</br>
			<input id="img-file-crop" value="SELECT IMAGE" type="button" onclick="validateImage()" class="btn btn-default">
			<input id="img-file-upload" value="UPLOAD" type="submit" class="btn btn-default">
			</br></br>
		</form>
	</div>
	<div id="img-file-output" class="col-sm-8"></div>
  </div>
</div>
</body>
<!--JAVASCRIPT-->
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery.form.min.js"></script>
<script src="js/jquery.Jcrop.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var options = { 
		target:   '#img-upload-output', 
                beforeSubmit: function() {
		    	$('#img-file-upload').hide();
        		$('#img-file-crop').hide();
        		$('#img-file-input').hide();
        		$('#img-file-output').hide();
			$('#img-upload-output').html("Uploading, please wait...");
                    	$("#progress-bar").width('0%');
                },
                uploadProgress: function (event, position, total, percentComplete){	
                    	$("#progress-bar").width(percentComplete + '%');
                    	$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
                },
		error:function () {
			$('#img-upload-output').html("<b style='color:red;'>Image Upload failed, please contact Support</b></br>You may now close this window.");
		},
		success:function () {
                    	$('#img-upload-output').html("<b style='color:green;'>Image received in good order</b></br>You may now close this window.");
                },
                resetForm: true 
        };

	$('#img-crop-form').submit(function(){
		$(this).ajaxSubmit(options);
        	return false
	});

	$('#close').hide();
	$('#img-file-upload').hide();
});

//FUNCTIONS
function validateImage() {
	 if (window.File && window.FileReader && window.FileList && window.Blob) {
                if( !$('#img-file-input').val()) {
                        $('#img-upload-output').html("<b style='color:red;'>Error: No file selected</b></br>");
			return
                }

                var fileSize = $('#img-file-input')[0].files[0].size;
                var fileType = $('#img-file-input')[0].files[0].type;
                
                if (fileType != 'image/png' && fileType != 'image/jpeg') {
                        $('#img-upload-output').html("Selected File Type: " + fileType +"</br><b style='color:red;'>Error: File must be in .png OR .jpg format</b>");
			return
                }
        
                if (fileSize > 524288) {
                        $('#img-upload-output').html("Selected File Size: " + fileSize / 1000 +"KB</br><b style='color:red;'>Error: File must be less than 512KB in size</b>");
			return
                }

                var image = $('#img-file-input')[0].files[0];
                renderImage(image);

		$('#img-file-upload').hide();
        }
        else
        {
                $('#img-upload-output').html("<b>Error: Your current browser lacks HTML file API Support</b>");
		return
        }
}

function renderImage(file) {
	var dimensions = new FileReader;
	dimensions.readAsDataURL(file);

	dimensions.onload = function(event) { //omf
    		var image = new Image;

		image.src = dimensions.result;

    		image.onload = function() {
			if (image.width > 800 || image.height > 600 || image.width < 400 || image.height < 150) {
				$('#img-upload-output').html("Selected Image Width: " + image.width + "px</br>Selected Image Height: " + image.height + "px</br><b style='color:red;'>Error: </br>Max Width: 800px ; Max Height: 600px </br>Min Width: 400px ; Min Height: 150px")
			}
			else
			{
				var url = event.target.result;
				$('#img-upload-output').html("<b style='color:green;'>Click on your image to start cropping!</b>");
                		$('#img-file-output').html("<img id='img-file-cropme' src='" + url + "' />")
                		cropImage();
			}
    		};
	};
}

function showUpload() {
	$('#img-file-upload').show();
}

function hideUpload() {
	$('#img-file-upload').hide();
	$('#img-upload-output').html("<b style='color:green;'>Click on your image to start cropping!</b>");
}

function showCoords(c)
  {
	// Coordinate variables:
	// c.x, c.y, c.x2, c.y2, c.w, c.h
	$('#img-upload-output').show();
	$('#img-upload-output').html("<b>Cropping Coordinates :</b></br>X : " + c.x + "</br>Y : " + c.y + "</br>Width : " + c.w + "</br>Height : " + c.h);
  };

function cropImage() {
	//Starting Jcrop with numerous arguments...
	$(function() {
        	$('#img-file-cropme').Jcrop({
					onSelect: showUpload,
					minSize: [350, 150],
					maxSize: [700, 300], 
					onChange: showCoords, 
					onRelease: hideUpload});
	});
}
</script>
</html>
