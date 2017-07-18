<script language="javascript" type="text/javascript">
function submitOrderForm( event ){
	event.preventDefault( );
	
	var values = { };
	values[ 'status' ] = jQuery(this).find('select').attr('value');
	jQuery.each( jQuery( this ).find( 'input' ).serializeArray( ), function( i, field ){
		values[ field.name ] = field.value;
	});
	
	if( values[ 'action' ] == 'change' ){
		divChangeSaveHtml = '<div id="orderChangeSaveDivWrap"><div class="orderChangeSaveDivWrap"></div><div id="orderChangeSaveDiv"><h2>Внести изменения</h2><form id="orderChangeSaveForm"><table border="0" class="ipod"><tr class="zags"><td>Номер квитанции:</td><td>Устройство:</td><td>Описание ремонта:</td><td>Статус:</td><td>Дата добавления:</td><td>Дата изменения:</td></tr><tr><td>' + values['custom_id'] + '</td><td> <input type="text" name="title" value="' + values['title'] + '" /></td><td> <input type="text" name="description" value="' + values['description'] + '" /></td><td> <select name="status"><option value="не выбрано">Выберите из списка</option><option value="выдан">выдан</option><option value="ремонт выполнен">ремонт выполнен</option><option value="на диагностике">на диагностике</option><option value="на чистке">на чистке</option><option value="в процессе ремонта">в процессе ремонта</option><option value="в ожидании запчастей">в ожидании запчастей</option><option value="ожидание решения клиента">ожидание решения клиента</option><option value="возврат">возврат</option><option value="отказ">отказ</option></select></td><td>' + values['add_date'] + '</td><td>' + values['last_change_date'] + '</td></tr></table><input type="hidden" name="id" value="' + values['id'] + '" /><input type="hidden" name="action" value="save" /><input type="submit" class="btnshowcart" value="Сохранить"/><button class="btnshowcart">Отмена</button></form></div></div>';
		jQuery('body').append(divChangeSaveHtml);
		jQuery('#orderChangeSaveDivWrap select option[value="'+values['status']+'"]').attr('selected','selected');
		jQuery('form#orderChangeSaveForm').submit(submitOrderForm);
		jQuery('form#orderChangeSaveForm button').click( function( event ){
			event.preventDefault( );
			jQuery( '#orderChangeSaveDivWrap' ).remove( );
		});
		return;
	}

	jQuery.ajax({
		type: 'POST',
		url: '/tomahawk/listOrder.php',
		cache: false,
		dataType: 'json',
		data: { params : JSON.stringify( values ) },
		success: function( data ){
			jQuery( '#orderChangeSaveDivWrap' ).remove( );
			showMessege( data );
			printOrderList( );
		},
		error: function( data ){
			showMessege( 'Во время выполнения запроса произошла ошибка' );
		}
	});
}

function printOrderList( ){
	jQuery( 'div#orderAddDiv input[ type="text" ]' ).attr( 'value','' );
	jQuery( 'div#orderAddDiv select option[ value="не выбрано" ]' ).attr( 'selected','selected' );

	var trHeadHtml = '<tr class="zags"><td>Номер квитанции:</td><td>Устройство:</td><td>Описание ремонта:</td><td>Статус:</td><td>Дата добавления:</td><td>Дата изменения:</td></tr>',
	values = {};
	values['action'] = 'getList';
	table = jQuery( '#orderListDiv table' );
	jQuery.ajax({
		type: 'POST',
		url: '/tomahawk/listOrder.php',
		dataType: 'json',
		cache: false,
		data: { params : JSON.stringify( values ) },
		success: function( data ){
			table.empty( );
			table.append( trHeadHtml );
			jQuery.each(data, function( i, value ){
				if( value.status != 'hide' && value.status != 'deleted' ){
					table.append('<tr><td>' + value.custom_id + '</td><td>' + value.title + '</td><td>' + value.description + '</td><td>' + value.status + '</td><td>' + value.add_date + '</td><td>' + value.last_change_date + '</td><td> <form class="orderChangeForm"><input type="hidden" name="id" value="' + value.id + '" /><input type="hidden" name="custom_id" value="' + value.custom_id + '" /><input type="hidden" name="title" value="' + value.title + '" /><input type="hidden" name="description" value="' + value.description + '" /><input type="hidden" name="status" value="' + value.status + '" /><input type="hidden" name="add_date" value="' + value.add_date + '" /><input type="hidden" name="last_change_date" value="' + value.last_change_date + '" /><input type="hidden" name="action" value="change" /><input type="submit" value="Изменить" class="btnshowcart" /></form> </td>				<td> <form class="orderHideForm"><input type="hidden" name="id" value="' + value.id + '" /><input type="hidden" name="action" value="hide" /><input type="submit" value="x Скрыть" class="btnshowcart" /></form> </td></tr>');
				}
			});
			jQuery('div#orderListDiv form').submit(submitOrderForm);
		}
	});
}

function showMessege( text ){
	jQuery('body').append('<div id="orderStatusRequest" style="display: none">'+text+'</div>');
	jQuery('#orderStatusRequest').show(3000);
	setTimeout(function() { jQuery('#orderStatusRequest').hide(3000) }, 1000);
	jQuery('#orderStatusRequest').remove();
}

jQuery(document).ready(function(){
	jQuery('form').submit(submitOrderForm);
	printOrderList( );
});

</script>