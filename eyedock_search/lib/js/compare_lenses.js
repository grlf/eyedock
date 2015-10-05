
function eyedock_compare_lenses_prepare(){

	var sb = document.getElementById('edas-compare-search-box');

	if(sb){
		eyedock_add_event(sb,'blur',eyedock_top_search_blur,false,true);
		eyedock_add_event(sb,'keyup',function(event){eyedock_top_search_autofill(sb,event);},true,false);
	}

	if(initsearch){
		eyedock_auto_search(initsearch);
	}
}

function eyedock_compare_lenses_init(){

	var uri = eyedock_search_config.base_url + 'index.php?mode=get_compare_lenses';

	/***
	 * Greenleaf - MJS - 10/27/14
	 * 
	 * Update Mootools Ajax syntax
	 */
	//var res = new Ajax(uri, {method: 'get', update: eyedock_search_config.compareid, onComplete: eyedock_compare_lenses_prepare}).request();
	var res = new Request.HTML({
		url: uri,
		method: 'get',
		update: eyedock_search_config.compareid,
		onComplete: eyedock_compare_lenses_prepare
	}).send();

}

function eyedock_compare_search_blur(){

	if(!this || !this.getAttribute){
		return;
	}

	try{
		var d = document.getElementById(this.id + '-display');
		if(d && d.style.display == 'block'){
			setTimeout(function(){
				d.style.display = 'none';
				d.innerHTML = '';
			}, 250);
		}
	}catch(e){}

}

function eyedock_compare_lenses(){

	var obj = document.getElementById(eyedock_search_config.compareid);

	if(!obj){
		return;
	}

	var productids = [];
	for(var x = 1; x <= 2; x++){
		var t = document.getElementById('edas-tab-content-' + x);
		if(t && t.style.display != 'none'){
			productids = eyedock_get_list_productids(t);
			break;
		}
	}

	if(productids.length == 0){
		return;
	}

	var rid = eyedock_search_config.compareresultsid;
	eyedock_reset_content(rid);

	var ajax_query = 'mode=get_lenses_data';

	var prefix = 'productids[]';
	for(var x = 0; x < productids.length; x++){
		ajax_query += '&' + encodeURIComponent(prefix) + '=' + encodeURIComponent(productids[x]);
	}

	eyedock_compare_lenses_open(obj);

	/****
	 * Greenleaf - MJS - 10/28/14
	 * 
	 * Updated Ajax syntax
	 */
	//var res = new Ajax(eyedock_search_config.base_url + 'index.php', {method: 'post', data: ajax_query, update: eyedock_search_config.compareresultsid, onComplete: function(){ eyedock_load_results(rid); } }).request();
	var res = new Request.HTML({
		method: 'post',
		url:	eyedock_search_config.base_url + 'index.php',
		data:	ajax_query,
		update:	eyedock_search_config.compareresultsid,
		onComplete: function(){ eyedock_load_results(rid); }
	}).send();

}

function eyedock_get_list_productids(obj){

	if(obj == null){
		return;
	}

	var productids = [];
	var divs = obj.getElementsByTagName('div');
	if(divs.length > 0){
		for(var x in divs){
			if(divs[x] && divs[x].getAttribute && divs[x].getAttribute('id') && divs[x].getAttribute('id').match(/lens-[\d]*$/)){
				var m = /lens-([\d]*)$/.exec(divs[x].getAttribute('id'));
				productids[productids.length] = m[1];
			}
		}
	}

	return productids;
}

function eyedock_compare_lenses_toggle(){

	var obj = document.getElementById(eyedock_search_config.compareid);

	if(!obj){
		return;
	}

	if(obj.style.display == 'none'){
		eyedock_compare_lenses_open(obj);
	}else{
		eyedock_compare_lenses_close(obj);
	}

}

function eyedock_compare_lenses_open(obj){

	if(obj != null){
		obj.style.display = 'block';
	}

}

function eyedock_compare_lenses_close(obj){

	if(obj == null){
		obj = document.getElementById(eyedock_search_config.compareid);
	}

	if(obj != null){
		obj.style.display = 'none';
	}

}
