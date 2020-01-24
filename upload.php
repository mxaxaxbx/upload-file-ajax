<?php
//get name from post request
$name = isset($_POST['name']) ? $_POST['name']:NULL;
//get file from request
$file = isset($_FILES['file']) ? $_FILES['file']:NULL;
//array to verify type file
$typefile=array('image/gif','image/jpeg','image/png','video/mp4','text/plain','application/pdf','audio/mpeg');

//field name and file are required
if($name && $file){
	//get file size
	$size = $_FILES['file']['size'];

	//If file size is than 5mb create error message
	if($size>5000000){
		$result=array('error'=>'error','reason'=>'File is very large. Try another smaller file.');
	}else{
		//get file type
		$type = $_FILES['file']['type'];

		//For sucurity check the type of file that will be uploaded to the server.
		if(in_array($type,$typefile)){
			//folder to upload file in server
			$path='uploads/';
			//get file extension
			$ext=substr($file['name'],strpos($file['name'],'.')+1);
			//Path, Name and extension of file to process upload
			$path = $path.$name.'.'.$ext;

			//upload file to folder uploads
			if(move_uploaded_file($file['tmp_name'], $path)){
				//create success message upload
                $result=array('ok'=>true,'message'=>'File Upload Success.','path'=>$path);
            }else{
            	//create message show errors try uploading
                $result=array('error'=>'error','reason'=>'An Error Occurred. Try again later.');
            }

		}else{
			//create error message if file type is invalid
			$result=array('error'=>'error','reason'=>'File type is invalid.');
		}
	}
}else{
	//create error message if name or file request is null 
	$result=array('error'=>'error','reason'=>'Enter File Name and load file from your computer.');
}

//header json response
header('Content-Type: application/json');
//convert result array to json for load in script js
echo json_encode($result);
?>
