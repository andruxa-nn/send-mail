function autoDell() {
	$('td:contains("noreply")').each(function() {
		idElem = $(this).prev().text();
		$.ajax({
			url: 'http://127.0.0.1/test/index.php?delEmail=' + idElem,
			type: 'POST',
			cashe: false
		});
	})
}
autoDell();