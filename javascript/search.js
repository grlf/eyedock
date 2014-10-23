jQuery.noConflict();
jQuery().ready(function() {
	jQuery("#search_box").dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		buttons: {}
	});

	jQuery("#search").autocomplete("/search.php", {delay: 100, width: 300});
	jQuery("#search_form").submit(function() {jQuery('#search_box').dialog('open'); return false;});

	jQuery('input#search').result(function(event, data, formatted) {
// executes on select result
//alert(formatted);
	x = data[1];
	f = document.forms["search_form"];
	f.q.value = data[0];

	if (x == '1') {
		f.action = '/index.php?module=Lenses&func=flash';
	}
	if (x == '2') {
		f.action = "/module-icd9-view.htm"
	}
	if (x == '3') {
		f.action = "index.php?module=Meds&func=search"
	}
	f.submit();
});

});
function select(id) {
	f = document.forms["search_form"];
	jQuery("#s1").attr('value', f.q.value);

	if (id == 1) {
		f.action = '/index.php?module=Lenses&func=flash';
	}
	if (id == 2) {
		f.action = "/module-icd9-view.htm"
	}
	if (id == 3) {
		f.action = "/module-Meds-search.htm"
	}
	f.submit();
}
