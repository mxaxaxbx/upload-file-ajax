//submit action form event
$('form#file').submit(function(e){
	//prevent default event for this
	e.preventDefault();
	//ajax method
	$.ajax({
		//URL where the data will be processed
		url:'upload.php',
		//method send data
		type:'POST',
		//create object from this form data
		data: new FormData(this),
		//Content Type is false by the attachment load
		contentType:false,
		//Dont save cache in the browser
		cache:false,
		processData:false,
		//Http progress
		xhr:function(){
			//Create XMLHttpe object to measure loading progress
			var xhr=new window.XMLHttpRequest();
			//get 'progress' attr for xhr 
			xhr.upload.addEventListener('progress',function(evt){
				if(evt.lengthComputable){
					//calculate progress 
					var percentComplete=evt.loaded/evt.total;
					//calc percentage of progress
					percentComplete=parseInt(percentComplete*100);
					//create HTML bootstrap loading bar
					$('div#loading').html(
						'<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="'+percentComplete+'" aria-valuemin="0" aria-valuemax="100" style="width: '+percentComplete+'%;"><span class="sr-only">'+percentComplete+'% Complete</span></div></div>'
					);
				}
			},false);
			return xhr;
		},
		beforeSend:function(data){
			console.log('cargando...');
		},
		success:function(data){
			//delete loading bar
			$('div#loading').html(null);
			//create Bootstrap alert frame to display end message and download link
			if(data.ok){
				$('div#success').html(
					'<div class="alert alert-success" role="alert">'+
					data.message+
					'<a href="'+data.path+'" download>&nbsp;Download File</a>'+
					'</div>'
				);
			//create Bootstrap alert frame to display error message of type file and  file size
			}else if(data.error){
				$('div#success').html(null);
				$('div#loading').html(null);
				$('div#error').html('<div class="alert alert-danger" role="alert">'+data.reason+'</div>');
			}
		},
		error:function(e){
			//create Bootstrap alert frame to display errors in operation
			$('div#success').html(null);
			$('div#loading').html(null);
			$('div#error').html('<div class="alert alert-danger" role="alert">'+JSON.stringify(e)+'</div>');
		}
	});
});
