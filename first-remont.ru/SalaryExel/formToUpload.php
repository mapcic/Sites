<div id="forToUploadDiv">
	<div id="forToUploadDivForm">
		<input type="file" multiple="multiple" accept="application/xml,text/xml">
		<button id="forToUploadDivButton">Загрузить</button>
	</div>
	<div id="forToUploadDivResponse"></div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script language="javascript" type="text/javascript">
	var files;
	 
	jQuery('div#forToUploadDivForm input[type=file]').change(function(){
	    files = this.files;
	});
    
    jQuery('#forToUploadDivButton').click( function(event){
        event.preventDefault();
        var data = new FormData();
        
        jQuery.each( files, function( key, value ){
            data.append( key, value );
        });

        jQuery.ajax({
            url: 'salary/salary.php',
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            success: function( response ){ 
                jQuery('#forToUploadDivResponse').empty();
                jQuery('#forToUploadDivResponse').append('<a href="'.response.'">Скачать</a>');
            },
            error: function( response ){
                jQuery('#forToUploadDivResponse').empty();
                jQuery('#forToUploadDivResponse').append('There is a ajax problem...');
                console.log('Ошибка AJAX запроса');
            }
        });
    });


</script>