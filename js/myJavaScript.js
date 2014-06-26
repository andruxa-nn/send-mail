function editItem(self) {
	var name = $(self).parent().siblings(':eq(1)');
	if( newName = prompt(name.text()) ) {
		$.ajax({
			url: '/test/index.php',
			type: 'POST',
			data: {
				editEmail: $(self).attr('value'),
				newName: newName
			},
			success: function(data) {
				var jsData = $.parseHTML(data);
				var formCont = $('.table-hover').closest('form');
				$(jsData).find('.alert').insertBefore(formCont);
				name.text(newName);
			}
		});
	}
	return false;
}

function delItem(self) {
	if( confirm('Подтверждаете удаление?') ) {
		$.ajax({
			url: '/test/index.php',
			type: 'POST',
			data: { delEmail: $(self).attr('value') },
			success: function() {
				$(self).closest('tr').fadeOut('fast');
			}
		});
	}
	return false;
}

$(function() {
	$('.alert')
	.prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
});