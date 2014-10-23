function search_redirect(id, value, form) {
	if (id == 1) {
		form.action = 'index.php?option=com_content&view=article&id=69&Itemid=61&search=' + escape(value);
	}
	if (id == 2) {
		form.action = unescape('index.php?option=com_content&view=article&id=71&Itemid=63');
	}
	if (id == 3) {
		form.action = unescape('index.php?option=com_content&view=article&id=70&Itemid=62');
	}
	if (id == 4) {
		value = value.replace(/\s\(gp.*\)$/i, '');
		window.location.href = 'index.php?option=com_content&view=article&id=126&Itemid=71&q=' + encodeURIComponent(value);
		return;
	}
	if (id == 5) {
		value = value.replace(/\s\(gp.*\)$/i, '');
		window.location = 'index.php?option=com_content&view=article&id=127&Itemid=72&q=' + encodeURIComponent(value);
		return;
	}
/*
	var res = /\(gp\s(name|material)\)/i.exec(value);
	if(res){
		value = value.replace(/\s\(.*\)$/, '');

		if(res[1].toLowerCase() == 'name'){
			var gppage = 'gplens';
		}else{
			var gppage = 'gpmaterial';
		}

		var regex = new RegExp(gppage,'i');

		if(typeof search_box != 'undefined' && window.location.pathname.match(regex)){
			search_box.quick_search(value);
		}else{
			window.location.href = gppage + '.html#q=' + encodeURIComponent(value);
		}

		return;
	}
*/
	form.submit();
}