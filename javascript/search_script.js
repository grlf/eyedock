jQuery.noConflict();
jQuery().ready(function() {
	jQuery("#search_box").dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		buttons: {}
	});

	jQuery("#top_search").autocomplete("/search.php", {delay: 100, width: 300});
	jQuery("#top_search_form").submit(function() {jQuery('#search_box').dialog('open'); return false;});

	jQuery('input#search_top').result(function(event, data, formatted) {
// executes on select result
	x = data[1];
	f = document.forms["top_search_form"];
	f.q.value = data[0];

	if (x == '1') {
		f.action = 'index.php?option=com_content&view=article&id=69&Itemid=61';
	}
	if (x == '2') {
		f.action = "search_icd9.php"
	}
	if (x == '3') {
		f.action = "search_mod.php"
	}
	f.submit();
});

});
function select(id) {
	f = document.forms["top_search_form"];
	jQuery("#s1").attr('value', f.q.value);

	if (id == 1) {
		f.action = 'index.php?option=com_content&view=article&id=69&Itemid=61';
	}
	if (id == 2) {
		f.action = "search_icd9.php"
	}
	if (id == 3) {
		f.action = "search_mod.php"
	}
	f.submit();
}
