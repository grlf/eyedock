
var eyedock_search_config = {
	'base_url' : 'eyedock_search/',
	'wrapperid' : 'eyedock-search-content',
	'defsearch' : 'Search everything...',
	'clensesid' : 'eyedock-company-lenses',
	'paramsid' : 'eyedock-parameters',
	'paramssearchid' : 'edas-parameters-results',
	'advsearchid' : 'eyedock-advanced-search',
	'advresultsid' : 'eyedock-advanced-results',
	'compareid' : 'eyedock-compare-lenses',
	'compareresultsid' : 'edas-compare-lens-list',
	'autofill_script' : 'upgrd/search.php'
};

function eyedock_main_init(){

	var t1 = document.getElementById('edas-tab-1');
	var t2 = document.getElementById('edas-tab-2');

	if(t1){
		eyedock_add_event(t1,'click',eyedock_tab_toggle,false,true);
	}

	if(t2){
		eyedock_add_event(t2,'click',eyedock_tab_toggle,false,true);
	}

	var sb = document.getElementById('edas-top-search-box');

	if(sb){
		eyedock_add_event(sb,'focus',eyedock_top_search_focus,false,true);
		eyedock_add_event(sb,'blur',eyedock_top_search_blur,false,true);
		eyedock_add_event(sb,'keyup',function(event){eyedock_top_search_autofill(sb,event);},true,false);
	}

	var paramtab = document.getElementById('edas-tab-content-2');

	var inputs = paramtab.getElementsByTagName('input');
	for(var x in inputs){
		if(inputs[x].getAttribute && inputs[x].getAttribute('type').toLowerCase() == 'checkbox' && inputs[x].getAttribute('id')){
			eyedock_add_event(inputs[x],'click',eyedock_parameter_search,false);
		}
	}

	eyedock_add_event(document.getElementById('search-compare-lenses'),'click',eyedock_compare_lenses,false);

	eyedock_add_event(document.getElementById('edas-more-options'),'click',eyedock_advanced_search_toggle,false);

	var paramhidden = (paramtab && paramtab.style.display == 'none');

	if(paramhidden){
		paramtab.style.opacity = '0';
		paramtab.style.filter = 'alpha(opacity=0)';
		paramtab.style.position = 'absolute';
		paramtab.style.display = 'block';
	}

	$$('div.edas-parameters-line-slider div.edas-slider').each(function(el, i){

		if(el.getAttribute){
			var id = el.getAttribute('id');
		}else{
			var id = '';
		}

		var slider = new Slider(el, el.getElement('.edas-slider-knob'), {
			steps: 100,
			wheel: true,
			onChange: function(){eyedock_slider_change(this, el.getAttribute('id'));},
			onComplete: eyedock_parameter_search
		});

		eyedock_search_config['parameter_search'] = false;

		if(id == 'replacement1-slider'){
			slider.set(0);
		}else if(id == 'replacement2-slider'){
			slider.set(100);
		}else if(id.match(/(toric|bifocal)/)){
			slider.set(0);
		}else{
			slider.set(50);
		}

		eyedock_search_config['parameter_search'] = true;

	});

	if(paramhidden){

		if(window.opera){
			paramtab.style.opacity = '1';
		}else{
			paramtab.style.opacity = null;
		}

		paramtab.style.filter = null;

		paramtab.style.position = 'static';

		paramtab.style.display = 'none';

	}

	$$('div.eyedock-detailed-popup').each(function(el, i){
		document.body.appendChild(el);
	});

	eyedock_adv_search_init();

	eyedock_compare_lenses_init();

}

function eyedock_load_results(id){

	var c = document.getElementById(id);
	var l = document.getElementById(id + '-loader');

	if(!c){
		return;
	}

	c.style.opacity = '0';
	c.style.filter = 'alpha(opacity=0)';
	c.style.display = 'block';

	var fx = new Fx.Morph($(id), {
		duration: 350,
		wait: false,
		transition: Fx.Transitions.Sine.easeIn
	});

	fx.start({'opacity': 1});

	if(l){
		setTimeout(function(){l.style.display = 'none';}, 250);
	}

	eyedock_setup_popups(id);

}

function eyedock_setup_popups(id){
	var els = document.getElementById(id).getElementsByTagName('div');
	for(var x in els){
		if(els[x] && els[x].className && els[x].className.match(/edas-tab-search-lens/) && els[x].getAttribute('id') && els[x].getAttribute('id').match(/lens-[\d]*/)){
			var elid = els[x].getAttribute('id');
			$(elid).addEvents({
				mouseenter: function(){eyedock_lens_open_popup(this);},
				mouseleave: function(e){eyedock_lens_close_popup(e);}
			});
		}else if(els[x] && els[x].className && els[x].className.match(/edas-tab-search-lens-popup/)){
			els[x].setAttribute('id', 'tmpid');
			$('tmpid').addEvents({
				mouseleave: eyedock_lens_close_popups
			});
			els[x].removeAttribute('id');
		}
	}
}

function eyedock_lens_open_popup(obj){

	var id = obj.getAttribute('id');

	var el = document.getElementById(id);

	eyedock_lens_close_popups();

	var res = /lens-([\d]*)$/.exec(id);
	if(res){
		var lid = res[1];
		var popup = document.getElementById('popup-' + lid);
		if(!popup){
			var divs = el.getElementsByTagName('div');
			for(var x in divs){
				if(divs[x] && divs[x].className && divs[x].className == 'edas-tab-search-lens-popup'){

					divs[x].style.visibility = 'hidden';
					divs[x].style.display = 'block';
					var origwidth = divs[x].offsetWidth;

					if( (obj.offsetWidth - 50) < origwidth){
						origwidth = obj.offsetWidth - 50;
					}

					try{
						divs[x].style.display = null;
					}catch(e){
						divs[x].style.display = 'none';
					}
					try{
						divs[x].style.visibility = null;
					}catch(e){
						divs[x].style.visibility = 'visible';
					}

					divs[x].setAttribute('id', 'popup-' + lid);
					document.body.appendChild(divs[x]);
					popup = document.getElementById('popup-' + lid);
					popup.origwidth = origwidth;
					break;
				}
			}
		}
		var pos = eyedock_get_position(el);
		var extratop = el.parentNode.scrollTop;
		var extraleft = el.offsetWidth - popup.origwidth;
		popup.style.top = (pos.top - extratop) + 'px';
		popup.style.left = (pos.left + extraleft) + 'px';
		popup.style.width = popup.origwidth + 'px';
		popup.style.display = 'block';
	}

}

function eyedock_lens_close_popup(e){
	var divs = document.body.getElementsByTagName('div');
	for(var x in divs){
		if(divs[x] && divs[x].className && divs[x].className == 'edas-tab-search-lens-popup' && divs[x].style.display == 'block'){

			ev = e || window.event;

			ev = ev.event;

			var mx = ev.pageX;
			var my = ev.pageY;

			var elx = divs[x].offsetLeft;
			var ely = divs[x].offsetTop;
			var elw = divs[x].offsetWidth;
			var elh = divs[x].offsetHeight;

			if( (mx >= elx) && (mx <= elx + elw) && (my >= ely) && (my <= ely + elh) ){
				return;
			}

			break;

		}
	}

	eyedock_lens_close_popups();
}

function eyedock_lens_close_popups(){
	var divs = document.body.getElementsByTagName('div');
	for(var x in divs){
		if(divs[x] && divs[x].className && divs[x].className == 'edas-tab-search-lens-popup'){
			divs[x].style.display = 'none';
		}
	}
}

function eyedock_get_position(el){

	var top = 0;
	var left = 0;
	while(el.tagName.toLowerCase() != 'body' && el.offsetParent){
		top += el.offsetTop;
		left += el.offsetLeft;
		el = el.offsetParent;
	}

	return {'top': top, 'left': left};

}

function eyedock_reset_content(id){

	var c = document.getElementById(id);
	var l = document.getElementById(id + '-loader');

	if(!c){
		return;
	}

	c.innerHTML = '';
	c.style.display = 'none';

	if(l){
		l.style.display = 'block';
	}

}

function eyedock_tab_toggle(){

	if(!this || !this.getAttribute){
		return;
	}

	var id = this.getAttribute('id');
	var tab = id.replace(/edas-tab-/i,'');

	var divs = eyedock_search_config['wrapperdom'].getElementsByTagName('div');

	for(var x = 0; x < divs.length; x++){
		if(divs[x].className && divs[x].className == 'edas-tab-content'){
			if(divs[x].getAttribute('id') == 'edas-tab-content-' + tab){
				this.className = 'edas-tab';
				divs[x].style.display = 'block';
			}else{
				var tabid = divs[x].getAttribute('id').replace(/edas-tab-content-/,'edas-tab-');
				if(document.getElementById(tabid)){
					document.getElementById(tabid).className = 'edas-tab-inactive';
				}
				divs[x].style.display = 'none';
			}
		}
	}

}

function eyedock_main_search(id){

	var s = document.getElementById(id);

	if(!s){
		return;
	}

	eyedock_trigger(s, 'blur');

	var rid = eyedock_search_config.compareresultsid;
	eyedock_reset_content(rid);

	var text = s.value;

	var cs = document.getElementById('edas-compare-search-box');
	if(cs){
		cs.value = text;
	}

	var ajax_query = 'mode=search_lenses&str=' + encodeURIComponent(text);

	eyedock_compare_lenses_open(document.getElementById(eyedock_search_config.compareid));

	/****
	 * Greenleaf - MJS - 10/28/14
	 * 
	 * Updated Ajax syntax
	 */
	//var res = new Ajax(eyedock_search_config.base_url + 'index.php', {method: 'post', data: ajax_query, update: eyedock_search_config.compareresultsid, onComplete: function(){ eyedock_load_results(rid); } }).request();
	var res = new Request.HTML({
		url: eyedock_search_config.base_url + 'index.php',
		method: 'post',
		data: ajax_query,
		update: eyedock_search_config.compareresultsid,
		onComplete: function(){ eyedock_load_results(rid); }
	}).send();

	return;
}

function eyedock_main_enter_search(id, e){

	var code = e.keyCode? e.keyCode : e.charCode;

	try{
		var d = document.getElementById(id + '-display');
	}catch(e){ }

	if(code == 13){

		if(d){
			var list = d.getElementsByTagName('a');
			if(list.length > 0){
				for(var x = 0; x <  list.length; x++){
					if(list[x].className && list[x].className == 'edas-search-dropdown-hover'){
						if(is_ie){
							eyedock_autofill_manual_click(list[x]);
						}else{
							eyedock_trigger(list[x], 'click');
						}
						return;
					}
				}
			}
		}

		eyedock_main_search(id);
		return;
	}

	if(!d){
		return;
	}

	if(code == 38 || code == 40){
		eyedock_autofill_move(id, (code == 38?-1:1));
	}

}

function eyedock_top_search_focus(){

	if(!this || !this.getAttribute){
		return;
	}

	var text = this.value;

	if(text == eyedock_search_config['defsearch']){
		this.setAttribute('value','');
	}

}

function eyedock_top_search_blur(){

	if(!this || !this.getAttribute){
		return;
	}

	var text = this.value;

	if(text == ''){
		this.setAttribute('value',eyedock_search_config['defsearch']);
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

function eyedock_top_search_autofill(el, e){

	var code = e.keyCode? e.keyCode : e.charCode;
	if(code == 13 || code == 38 || code == 40){
		return;
	}

	var text = el.value;
	var time = Math.floor(new Date().getTime() / 1000);

	/**
	 * Greenleaf - MJS - 10/28/14
	 * 
	 * Updated Ajax Request syntax
	 */
	var res = new Request({
		method: 'get', 
		url: eyedock_search_config.autofill_script + '?q=' + encodeURIComponent(text) + '&limit=10&timestamp=' + time,
		onComplete: function(res){
			var words = [];
			if(res != ''){
				var res = res.split("\n");
				if(res.length > 0){
					for(var x = 0; x < res.length; x++){
						var str = res[x].split('|')[0];
						if(str.match(/\(cls\)/i)){
							word = str.replace(/^(.*)[\s]*\(cls\)$/i,"$1");
							while(word.match(/\s$/)){
								word = word.replace(/\s$/,'');
							}
							words[words.length] = word;
						}
					}
				}
			}
			eyedock_top_search_autofill_display(el, words);
		}
	}).send();

}

function eyedock_top_search_autofill_display(el, words){

	try{
		var d = document.getElementById(el.id + '-display');
		if(!d){
			return;
		}
	}catch(e){
		return;
	}

	d.style.display = 'none';
	d.innerHTML = '';

	if(words.length == 0 || words.length > 15){
		return;
	}

	for(var x = 0; x < words.length; x++){
		var a = document.createElement('a');
		a.setAttribute('href', '#');
		a.setAttribute('onclick', 'return false;');
		a.appendChild(document.createTextNode(words[x]));
		eyedock_add_event(a,'click',eyedock_autofill_click,false,true);
		$(a).addEvents({
			mouseenter: eyedock_autofill_hover,
			mouseleave: function(){this.className = '';}
		});
		d.appendChild(a);
	}

	d.firstChild.className = 'edas-search-dropdown-hover';
	d.style.display = 'block';

}

function eyedock_autofill_hover(){

	var list = this.parentNode.getElementsByTagName('a');

	if(list.length > 0){
		for(var x = 0; x < list.length; x++){
			if(list[x].className && list[x].className == 'edas-search-dropdown-hover'){
				list[x].className = '';
			}
		}
	}

	this.className = 'edas-search-dropdown-hover';
}

function eyedock_autofill_click(el){

	if(el === null){
		return eyedock_autofill_manual_click(el);
	}

	try{
		var c = this.parentNode;
		var i = document.getElementById(c.id.replace(/-display/, ''));
		if(!i){
			return;
		}
	}catch(e){
		return;
	}

	var text = this.firstChild.nodeValue;
	i.value = text;

	c.style.display = 'none';
	c.innerHTML = '';

	eyedock_main_search(i.id);

	return false;
}

function eyedock_autofill_manual_click(el){

	try{
		var c = el.parentNode;
		var i = document.getElementById(c.id.replace(/-display/, ''));
		if(!i){
			return;
		}
	}catch(e){
		return;
	}

	var text = el.firstChild.nodeValue;
	i.value = text;

	c.style.display = 'none';
	c.innerHTML = '';

	eyedock_main_search(i.id);

	return false;
}

function eyedock_autofill_move(id, dir){

	try{
		var d = document.getElementById(id + '-display');
		if(!d){
			return;
		}
	}catch(e){
		return;
	}

	var list = d.getElementsByTagName('a');

	if(list.length == 0){
		return;
	}

	selectedindex = false;
	for(var x = 0; x < list.length; x++){
		if(list[x].className && list[x].className == 'edas-search-dropdown-hover'){
			selectedindex = x;
		}
	}

	if(selectedindex === false){
		var newindex = (dir > 0?0:list.length - 1);
	}else{
		var newindex = Math.min(Math.max(selectedindex + dir, 0), list.length - 1);
	}

	if(selectedindex !== newindex){
		if(selectedindex !== false){
			list[selectedindex].className = '';
		}
		list[newindex].className = 'edas-search-dropdown-hover';
	}

}

function eyedock_get_company_lenses(id){

	var rid = eyedock_search_config.clensesid;
	var c = document.getElementById(rid);

	if(!c){
		return;
	}

	eyedock_reset_content(rid);

	var uri = eyedock_search_config['base_url'] + 'index.php?mode=get_company_lenses&id=' + encodeURIComponent(id);

	/*****
	 * Greenleaf - MJS - 10/28/14
	 * 
	 * Updated Ajax syntax
	 */
	//var res = new Ajax(uri, {method: 'get', update: eyedock_search_config.clensesid, onComplete: function(){ eyedock_load_results(rid); } }).request();
	var res = new Request.HTML({
		method: 'get',
		url : uri,
		update: eyedock_search_config.clensesid,
		onComplete: function(){ eyedock_load_results(rid); }
	}).send();

}

function eyedock_parameter_search(){

	if(eyedock_search_config['parameter_search'] == false){
		return;
	}

	var params = document.getElementById(eyedock_search_config.paramsid);

	if(!params){
		return;
	}

	var rid = eyedock_search_config.paramssearchid;
	eyedock_reset_content(rid);

	var ajax_query = 'mode=parameters_search';

	var divs = params.getElementsByTagName('div');
	for(var x in divs){
		if(divs[x].className && divs[x].className.match(/edas-ajax-input/i) && divs[x].getAttribute('id')){
			var name = divs[x].getAttribute('id').replace(/-slider-value/,'').trim();
			var value = divs[x].firstChild.nodeValue.trim();
			ajax_query += (ajax_query != ''?'&':'') + encodeURIComponent(name) + '=' + encodeURIComponent(value);
		}
	}

	var inputs = params.getElementsByTagName('input');
	for(var x in inputs){
		if(inputs[x].getAttribute && inputs[x].getAttribute('type').toLowerCase() == 'checkbox' && inputs[x].getAttribute('id') && inputs[x].checked){
			var name = inputs[x].getAttribute('id').replace(/-checkbox/,'').trim() + '_checked';
			var value = 'Y';
			ajax_query += (ajax_query != ''?'&':'') + encodeURIComponent(name) + '=' + encodeURIComponent(value);
		}
	}

	/*****
	 * Greenleaf - MJS - 10/27/14
	 * 
	 * Updated Mootools ajax syntax
	 */
	//var res = new Ajax(eyedock_search_config.base_url + 'index.php', {method: 'post', data: ajax_query, update: eyedock_search_config.paramssearchid, onComplete: function(){ eyedock_load_results(rid); } }).request();
	var res = new Request.HTML({
		method: 'post',
		url:	eyedock_search_config.base_url + 'index.php',
		data:	ajax_query,
		update:	eyedock_search_config.paramssearchid,
		onComplete:	function() {
			eyedock_load_results(rid);
		}
	}).send();

}

function eyedock_auto_search(str){

	var el = document.getElementById('edas-top-search-box');

	if(!el){
		return;
	}

	el.setAttribute('value', str);

	var el2 = document.getElementById('edas-compare-search-box');
	if(el2){
		el2.setAttribute('value', str);
	}

	eyedock_main_search('edas-top-search-box');
}

function eyedock_search_init(){

	eyedock_search_config['wrapperdom'] = document.getElementById(eyedock_search_config['wrapperid']);

	/***
	 * Greenleaf - MJS - 10/27/14
	 * 
	 * Upgraded Mootools Ajax syntax
	 */
	//var res = new Ajax(eyedock_search_config.base_url + 'index.php', {method: 'get', update: eyedock_search_config.wrapperid, onComplete: eyedock_main_init}).request();
	var res = new Request.HTML({
		method: 'get',
		url: eyedock_search_config.base_url + 'index.php',
		update: eyedock_search_config.wrapperid,
		onComplete: eyedock_main_init
	}).send();

}

function eyedock_add_event(obj,type,func,flag,requirethis){

	if(obj == null){
		return;
	}

	try{
		obj.addEventListener(type, func, flag);
	}catch(e){
		if(requirethis == true){
			obj['on' + type] = func;
		}else{
			obj.attachEvent('on' + type, func);
		}
	}
}

function eyedock_trigger(el, event){
	if(document.createEvent){
		var evt = document.createEvent("HTMLEvents");
		evt.initEvent(event, true, true);
		return !el.dispatchEvent(evt);
	}else{
		var evt = document.createEventObject();
		return el.fireEvent('on'+event,evt);
	}
}

eyedock_add_event(window,'load',eyedock_search_init,false);

var is_ie6 = (navigator.userAgent.toLowerCase().substr(25,6)=="msie 6") ? true : false;
var is_ie7 = (navigator.userAgent.toLowerCase().substr(25,6)=="msie 7") ? true : false;
var is_ie = (navigator.userAgent.match(/msie/i)) ? true : false;

document.writeln('<link type="text/css" rel="stylesheet" href="' + eyedock_search_config.base_url + 'lib/css/main.css" />');
if(is_ie6){
	document.writeln('<link type="text/css" rel="stylesheet" href="' + eyedock_search_config.base_url + 'lib/css/main.ie6.css" />');
}
if(is_ie7){
	document.writeln('<link type="text/css" rel="stylesheet" href="' + eyedock_search_config.base_url + 'lib/css/main.ie7.css" />');
}
document.writeln('<link type="text/css" rel="stylesheet" href="' + eyedock_search_config.base_url + 'lib/css/advanced_search.css" />');
document.writeln('<link type="text/css" rel="stylesheet" href="' + eyedock_search_config.base_url + 'lib/css/compare_lenses.css" />');
document.writeln('<link type="text/css" rel="stylesheet" href="' + eyedock_search_config.base_url + 'lib/css/lens_detail.css" />');
document.writeln('<script type="text/javascript" src="' + eyedock_search_config.base_url + 'lib/js/slider_functions.js"></script>');
document.writeln('<script type="text/javascript" src="' + eyedock_search_config.base_url + 'lib/js/advanced_search.js"></script>');
document.writeln('<script type="text/javascript" src="' + eyedock_search_config.base_url + 'lib/js/compare_lenses.js"></script>');
document.writeln('<script type="text/javascript" src="' + eyedock_search_config.base_url + 'lib/js/lens_detail.js"></script>');
document.writeln('<div id="eyedock-search-content"></div>');

var res = window.location.href.split('&');

for(var x = 0; x < res.length; x++){
	var res2 = /^search=(.*)/.exec(res[x]);
	if(res2 != null){
		var initsearch = decodeURIComponent(res2[1]);
	}
}
