CARGAR ARCHIVOS CON AJAX Y PHP
En este artículo voy crear un script JavaScript que nos servirá para realizar una conexión por AJAX con un servidor en la que mandemos datos y archivos adjuntos. Gracias al uso de AJAX podremos subir archivos sin necesidad de recargar la página actual.
Para hacer posible este objetivo existe un objeto nativo JavaScript llamado FormData que nos permitirá generar la estructura necesaria para enviar datos con el método POST. 
Usé Bootstrap para facilitar la tarea de crear la interfaz dónde finalmente el usuario podrá visualizar la información de alertas de errores y de terminado durante la operación y JQuery para facilitar la edición y ejecución de AJAX en el script.
Crear UI HTML
Durante la creación de la plantilla usé bootstrap para facilitar la creación de la interfaz gráfica.
<! DOCTYPE html>
<html>
<head>
	<title>Upload File With AJAX</title>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
</head>
Dentro del <body></body> Cree el formulario WEB
<h1 class="text-primary text-center">Upload File With AJAX AND PHP.</h1>
Y separé los archivos adjuntos de los demás datos con el atributo enctype dentro de la etiqueta <form> 
<form id="file" enctype="multipart/form-data" method="POST">
<div class="form-group col-md-8">
		<label for="name">File Name:(*)</label>
		<input type="text" name="name" id="name" class="form-control" placeholder="Enter the file Name" autofocus required/>
		<label for="name">Select File:(*)</label>
		<input type="file" name="file" id="file" class="form-control" required/>
		<br/>
		<button type="submit" class="btn btn-success pull-right">Upload File</button>
		<i><b> (*)Required:</b> All fields are required.</i>
	</div>
</form>
El formulario lleva el atributo id con el valor file para reconocer el evento submit con JQuery.
Luego creé 3 <div> para mostrar la  carga del archivo, otro para mostrar posibles errores que pueda haber durante la carga.
<div id="loading" class="col-md-8"></div>
<div id="error" class="col-md-8"></div>
<div id="success" class="col-md-8"></div>
Luego cargué la biblioteca JQuery:
<script type="text/javascript" src="js/jquery.min.js"></script>
Y después el script de carga AJAX:
<script type="text/javascript" src="js/script.js"></script>
Crear Script de Carga
Con JQuery reconocí el evento submit de JavaScript que lleva por defecto el formulario.
$('form#file').submit(function(e){
Detuve la acción de enviar con el evento:
e.preventDefault();
Luego Inicializo las funciones de AJAX:
$.ajax({
url:'upload.php',
	type:'POST',
	data: new FormData(this), 
	//ContenType: false; ya que estamos cargando archivos
	contentType:false,
	//No queremos guardar la cache en el navegador
	cache:false,
	processData:false,
//cargamos la interfaz xhr para medir el total de guardado en el servidor por medio de la petición.
xhr:function(){
//Crear objeto XMLHttp 
	var xhr=new window.XMLHttpRequest(); 
	xhr.upload.addEventListener('progress',function(evt){
		if(evt.lengthComputable){
			//Calcular progreso
			var percentComplete=evt.loaded/evt.total;
			//Calcular porcentaje
percentComplete=parseInt(percentComplete*100);
			//Crear elemento HTML para mostrar el total
			$('div#loading').html(
'<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="'+percentComplete+'" aria-valuemin="0" aria-valuemax="100" style="width: '+percentComplete+'%;"><span class="sr-only">'+percentComplete+'% Complete</span></div></div>'
			);
		}
	},false);
	return xhr;
},
//Al finalizar la carga
success:function(data){
	//eliminar barra de carga
	$('div#loading').html(null);
//crear elemento HTML para mostrar el mensaje de finalizado y el enlace de descarga del archivo subido.
	if(data.ok){
		$('div#success').html(
'<div class="alert alert-success" role="alert">'+
			data.message+
'<a href="'+data.path+'" download>&nbsp;Download File</a>'+
			'</div>'
		);
//Crear elemento HTML para mostrar posibles errores
	}else if(data.error){
		$('div#success').html(null);
		$('div#loading').html(null);
		$('div#error').html('<div class="alert alert-danger" role="alert">'+data.reason+'</div>');
	}
},
//Mostrar  errores durante la carga
error:function(e){
$('div#success').html(null);
	$('div#loading').html(null);
	$('div#error').html('<div class="alert alert-danger" role="alert">'+JSON.stringify(e)+'</div>');
}
});
});
Subirlo al servidor con PHP
El Archivo PHP encargado de transferir los archivos al servidor evaluará el tamaño y el tipo de archivo que va a ser subido. De esta manera evitamos problemas de seguridad en caso que la memoria colapse con el tiempo de espera, o que el tipo de archivo no sea válido de almacenar.  
<?php
//obtener nombre de la solicitud post
$name = isset($_POST['name']) ? $_POST['name']:NULL;
//obtener archivo de la solicitud
$file = isset($_FILES['file']) ? $_FILES['file']:NULL;
//Con este array verifico el tipo de archivo. Sí está aquí se puede subir al servidor
$typefile=array('image/gif','image/jpeg','image/png','video/mp4','text/plain','application/pdf','audio/mpeg');

//Los campos de nombre y seleccionar archivo son obligatorios
if($name && $file){
	//obtener tamaño del archivo
	$size = $file['size'];
	
//Sí el archivo es superior a los 5Mb, crear un array $result mostrando las razones 
	if($size>5000000){
$result=array('error'=>'error','reason'=>'File is very large. Try another smaller file.');
	}else{
		//obtener tipo de archivo
		$type = $file['type'];
		
		//Verificar que el tipo de archivo sea correcto
		if(in_array($type,$typefile)){
//Carpeta en el servidor donde serán subidos los archivos
			$path='uploads/';
			//obtener la extensión del archivo
			$ext=substr($file['name'],strpos($file['name'],'.')+1);
			//Ruta, Nombre y extensión del archivo para ser cargado
			$path = $path.$name.'.'.$ext;

			//Subir archivo al servidor
			if(move_uploaded_file($file['tmp_name'], $path)){
				//Crear mensaje de finalización de subida
                			$result=array('ok'=>true,'message'=>'File Upload Success.','path'=>$path);
			}else{
            			//Crear mensaje de error si el archivo no fue subido correctamente
                			$result=array('error'=>'error','reason'=>'An Error Occurred. Try again later.');
            		}
}else{
			//Crear mensaje de error si el tipo de archivo es invalido
			$result=array('error'=>'error','reason'=>'File type is invalid.');
		}
	}
}else{
	//Crear mensaje de error si el nombre y el achivo no llegaron a la solicitud 
	$result=array('error'=>'error','reason'=>'Enter File Name and load file from your computer.');
}

//Crear encabezado json como respuesta HTTP
header('Content-Type: application/json');
//Convertir array del resultado a formato JSON para ser leído en el script.js
echo json_encode($result);
Resultado
Con estos tres archivos, podemos crear una interfaz de subida de archivos en PHP y AJAX y subirlas directamente en un servidor Web.
 
Éste código  y más lo encuentras en mi GitHub






