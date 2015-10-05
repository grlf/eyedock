
function eyedock_lens_detail_display(id){

	var detailid = 'lens-detail-' + id;

	var ol = document.getElementById('eyedock-lens-detail-load');

	if(!document.getElementById(detailid)){

		if(ol){
			ol.style.display = 'block';
		}

		var w = document.getElementById(eyedock_search_config.wrapperid);
		var d = document.createElement('div');
		d.setAttribute('id', detailid);
		d.className = 'eyedock-lens-detail';
		d.style.display = 'none';
		w.appendChild(d);

		var ajax_query = 'mode=lens_detail&id=' + id;

		/****
		 * Greenleaf - MJS - 10/28/14
		 * 
		 * Updated Ajax Syntax
		 */
		//var res = new Ajax(eyedock_search_config.base_url + 'index.php', {method: 'get', data: ajax_query, update: detailid, onComplete: function(){ eyedock_lens_detail_display(id); eyedock_lens_detail_done(id) } }).request();
		var res = new Request.HTML({
			method: 'get',
			url: eyedock_search_config.base_url + 'index.php',
			data: ajax_query,
			update: detailid,
			onComplete: function(){ eyedock_lens_detail_display(id); eyedock_lens_detail_done(id); }
		}).send();

	}else{

		if(ol){
			ol.style.display = 'none';
		}

		document.getElementById(detailid).style.position = 'static';
		document.getElementById(detailid).style.display = 'block';
		var es = document.getElementById('eyedock-search');
		if(es){
			es.style.display = 'none';
		}
		var as = document.getElementById(eyedock_search_config.advsearchid);
		if(as){
			as.style.zIndex = '-1';
		}
		var c = document.getElementById(eyedock_search_config.compareid);
		if(c){
			c.style.zIndex = '-1';
		}
	}

}

function eyedock_lens_detail_done(id) {
	//update the "displayed" count
	jQuery.post("/ratings/viewCount.php", { table: "lenses", itemID: id } );
}

function eyedock_lens_detail_close(id){

	var detailid = 'lens-detail-' + id;

	if(document.getElementById(detailid)){
		document.getElementById(detailid).style.display = 'none';
		document.getElementById(detailid).style.position= 'absolute';
	}

	var es = document.getElementById('eyedock-search');
	if(es){
		es.style.display = 'block';
	}

	var as = document.getElementById(eyedock_search_config.advsearchid);
	if(as){
		try{
			as.style.zIndex = null;
		}catch(e){
			as.style.zIndex = '49';
		}
	}

	var c = document.getElementById(eyedock_search_config.compareid);
	if(c){
		try{
			c.style.zIndex = null;
		}catch(e){
			c.style.zIndex = '50';
		}
	}

	$$('div.eyedock-detailed-popup').each(function(el, i){
		el.style.display = 'none';
	});
	
	var company_details_status = false;
	
}

function eld_popup_open(w, id){

	$$('div.eyedock-detailed-popup').each(function(el, i){
		el.style.display = 'none';
	});

	var el = document.getElementById('edp-' + w);

	if(!el){
		return;
	}

	if(el.parentNode.tagName.toLowerCase() != 'body'){
		document.body.appendChild(el);
		el = document.getElementById('edp-' + w);
	}

	w = 250;
	h = 250;

	eld_popup_open_init(el, id, w, h);

}

function eld_popup_open_init(el, id, w, h){
	el.style.width = '0px';
	el.style.height = '0px';
	el.style.display = 'block';

	var box = document.getElementById('lens-detail-' + id);
	if(box){
		var pos = eyedock_get_position(box);
		el.style.top = (pos.top + 50) + 'px';
		el.style.left = (pos.left + (box.clientWidth / 2)) + 'px';
	}

	if(w == null){
		w = 250;
	}

	if(h == null){
		h = 250;
	}

	eld_popup = {
		'el': el,
		'width': w,
		'height': h,
		'inc': 15,
		'int': 10
	};

	var divs = el.getElementsByTagName('div');
	if(divs.length > 0){
		for(var x = 0; x <  divs.length; x++){
			if(divs[x] && divs[x].className && divs[x].className == 'eyedock-detailed-popup-text'){
				divs[x].style.display = 'none';
				eld_popup.text = divs[x];
			}else if(divs[x] && divs[x].className && divs[x].className == 'eyedock-detailed-popup-close'){
				divs[x].style.display = 'none';
				eld_popup.close = divs[x];
			}
		}
	}

	eld_popup['func'] = setInterval(eld_popup_open_anim, eld_popup.int);
}

function eld_popup_open_anim(){

	if(!eld_popup){
		return;
	}

	var w = eld_popup.el.clientWidth + 2;//(is_ie6?2:0);
	var h = eld_popup.el.clientHeight + 2;//(is_ie6?2:0);

	if(w < eld_popup.width && h < eld_popup.height){
		var neww = Math.min((w + eld_popup.inc), eld_popup.width);
		var newh = Math.min((h + eld_popup.inc), eld_popup.height);
		eld_popup.el.style.width = neww + 'px';
		eld_popup.el.style.height = newh + 'px';
		eld_popup.el.style.marginLeft = '-' + (neww / 2) + 'px';
	}else{
		eld_popup.text.style.display = 'block';
		eld_popup.close.style.display = 'block';
		clearInterval(eld_popup.func);
		eld_popup = null;
	}

}

function eld_popup_close(w){
	var el = document.getElementById('edp-' + w);

	if(!el){
		return;
	}

	el.style.display = 'none';
}

function eyedock_lens_detail_images_toggle(id){

	var d = document.getElementById(id + '-images');

	if(!d){
		return false;
	}

	if(d.style.display == 'none'){
		d.style.display = 'block';
	}else{
		d.style.display = 'none';
	}

	return false;
}

function eyedock_lens_detail_wholesale_toggle(id){

	var d = document.getElementById(id + '-wholesale');

	if(!d){
		return false;
	}

	if(d.style.display == 'none'){
		d.style.display = 'block';
	}else{
		d.style.display = 'none';
	}

	return false;
}

var company_details_status = false;

function eld_companypopup_open(id) {

	switch(company_details_status) {
	
		case false:
		
			$('company_details_popup' + id).setStyle('display', 'inline');
			company_details_status = true;
		
		break;
		
		case true:
	
			$('company_details_popup' + id).setStyle('display', 'none');
			company_details_status = false;			
	
		break;
	
	}

}