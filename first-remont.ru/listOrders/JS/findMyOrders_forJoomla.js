<script language="javascript" type="text/javascript">
function submitOrderForm( event ){
	jQuery('div#searchFormDiv div#searchFormDivResponse').empty();
	event.preventDefault( );
	var values = { };
	jQuery.each( jQuery( this ).find( 'input' ).serializeArray( ), function( i, field ){
		values[ field.name ] = field.value;
	});
	var emptyResponse = '<br /><h1>К сожалению, такого номера квитанции не существует!</h1><br /><br />Номер квитанции находиться на самой квитанции!)<br />';
	var emptyTable = '<br /><h1>Результат:</h1><table id="searchFormDivTable" border="0" class="ipod"><tr class="zags"><td>Номер квитанции:</td><td>Устройство:</td><td>Ремонт:</td><td>Статус:</td></tr>';

	jQuery.ajax({
		type: 'POST',
		url: '/tomahawk/listOrder.php',
		cache: false,
		dataType: 'json',
		data: { params : JSON.stringify( values ) },
		success: function( data ){
			response = !data? emptyResponse : emptyTable+'<tr><td>' + data.custom_id + '</td><td>'+data.title+'</td><td>'+data.description+'</td><td>'+data.status+'</td></tr></table>'
			jQuery('div#searchFormDiv div#searchFormDivResponse').append(response);
		},
		error: function( data ){
			showMessege( 'Во время выполнения запроса произошла ошибка' );
		}
	});
}
jQuery(document).ready(function(){
	jQuery('form').submit(submitOrderForm);
});
</script>