
function eyedock_advanced_search_prepare(){

	var a = document.getElementById(eyedock_search_config.advsearchid);

	if(!a){
		return;
	}

	var divs = a.getElementsByTagName('div');
	for(var x in divs){
		if(divs[x].className && divs[x].className.match(/edas-adv-close/i)){
			eyedock_add_event(divs[x],'click',eyedock_advanced_search_toggle,false);
		}
	}

	var asubmit = document.getElementById('adv-search-submit');
	if(asubmit){
		eyedock_add_event(asubmit,'click',eyedock_advanced_search,false);
	}

	var areset = document.getElementById('adv-search-reset');
	if(areset){
		eyedock_add_event(areset,'click',eyedock_advanced_search_reset,false);
	}

	var advhidden = (a.style.display == 'none');

	if(advhidden){
		a.style.opacity = '0';
		a.style.filter = 'alpha(opacity=0)';
		a.style.display = 'block';
	}

	eyedock_search_config['advanced_sliders'] = {};

	$$('div.edas-adv-params-slider-line div.edas-slider, div.edas-slider-param-short .edas-slider-short').each(function(el, i){

		if(el.getAttribute){
			var id = el.getAttribute('id');
		}else{
			var id = '';
		}

		var slider = new Slider(el, el.getElement('.edas-slider-knob'), {
			steps: 100,
			wheel: true,
			onChange: function(){eyedock_adv_slider_change(this, el.getAttribute('id'));}
		});

		if(id == 'adv-replacement1-slider'){
			slider.set(0);
		}else if(id == 'adv-replacement2-slider'){
			slider.set(100);
		}else if(id.match(/(toric|bifocal)/)){
			slider.set(0);
		}else{
			slider.set(50);
		}

		if(id != ''){
			eyedock_search_config['advanced_sliders'][id] = slider;
		}

	});

	if(advhidden){
		if(window.opera){
			a.style.opacity = '1';
		}else{
			a.style.opacity = null;
		}
		a.style.filter = null;
		a.style.display = 'none';
	}

}

function eyedock_advanced_search(){

	var a = document.getElementById(eyedock_search_config.advsearchid);

	if(!a){
		return;
	}

	var rid = eyedock_search_config.advresultsid;
	eyedock_reset_content(rid);

	var ajax_query = 'mode=advanced_search';

	var tags = ['div','span'];
	for(var i in tags){
		var els = a.getElementsByTagName(tags[i]);
		if(els.length > 0){
			for(var x in els){
				if(els[x] && els[x].className && els[x].className.match(/edas-ajax-input/i) && els[x].getAttribute('id')){
					var name = els[x].getAttribute('id').replace(/^adv-(.*)-slider-value$/,'$1').trim();
					var value = els[x].firstChild.nodeValue.trim();
					ajax_query += (ajax_query != ''?'&':'') + encodeURIComponent(name) + '=' + encodeURIComponent(value);
				}
			}
		}
	}

	var inputs = a.getElementsByTagName('input');
	for(var x in inputs){
		if(inputs[x].getAttribute && inputs[x].getAttribute('type').toLowerCase() == 'checkbox' && inputs[x].getAttribute('id') && inputs[x].checked){
			if(inputs[x].getAttribute('id').match(/^basecurve-/)){
				var bcurvetype = inputs[x].getAttribute('id').replace(/^basecurve-/,'');
				var name = 'basecurve_checked[' + bcurvetype + ']';
			}else{
				var name = inputs[x].getAttribute('id').replace(/-adv-checkbox/,'').trim() + '_checked';
			}
			var value = 'Y';
			ajax_query += (ajax_query != ''?'&':'') + encodeURIComponent(name) + '=' + encodeURIComponent(value);
		}
	}

	/***
	 * Greenleaf - MJS - 10/28/14
	 * 
	 * Update Ajax syntax
	 */
	//var res = new Ajax(eyedock_search_config.base_url + 'index.php', {method: 'post', data: ajax_query, update: eyedock_search_config.advresultsid, onComplete: function(){ eyedock_load_results(rid); }}).request();
	var res = new Request.HTML({
		url: eyedock_search_config.base_url + 'index.php',
		method: 'post',
		data: ajax_query,
		update: eyedock_search_config.advresultsid,
		onComplete: function(){ eyedock_load_results(rid); }
	});

}

function eyedock_advanced_search_reset(){

	var a = document.getElementById(eyedock_search_config.advsearchid);

	if(!a){
		return;
	}

	if(eyedock_search_config.advanced_sliders){
		for(var id in eyedock_search_config.advanced_sliders){
			var slider = eyedock_search_config.advanced_sliders[id];
			if(id == 'adv-replacement1-slider'){
				slider.set(0);
			}else if(id == 'adv-replacement2-slider'){
				slider.set(100);
			}else if(id.match(/(toric|bifocal)/)){
				slider.set(0);
			}else{
				slider.set(50);
			}
		}
	}

	var inputs = a.getElementsByTagName('input');
	for(var x in inputs){
		if(inputs[x].getAttribute && inputs[x].getAttribute('type').toLowerCase() == 'checkbox' && inputs[x].checked){
			inputs[x].checked = null;
		}
	}

	var ar = document.getElementById(eyedock_search_config.advresultsid);
	if(ar){
		ar.innerHTML = '';
	}
}

function eyedock_advanced_search_toggle(){

	var obj = document.getElementById(eyedock_search_config.advsearchid);

	if(!obj){
		return;
	}

	if(obj.style.display == 'none'){
		eyedock_advanced_search_open(obj);
	}else{
		eyedock_advanced_search_close(obj);
	}

}

function eyedock_advanced_search_open(obj){

	if(obj != null){
		obj.style.display = 'block';
	}

}

function eyedock_advanced_search_close(obj){

	if(obj != null){
		obj.style.display = 'none';
	}

}


function eyedock_adv_search_init(){

	var uri = eyedock_search_config.base_url + 'index.php?mode=get_advanced_search';

	/***
	 * Greenleaf - MJS - 10/27/14
	 * 
	 * Updated Mootools Ajax syntax
	 */
	//var res = new Ajax(uri, {method: 'get', update: eyedock_search_config.advsearchid, onComplete: eyedock_advanced_search_prepare}).request();
	var res = new Request.HTML({
		url: uri,
		method: 'get',
		update: eyedock_search_config.advsearchid,
		onComplete: eyedock_advanced_search_prepare
	}).send();

}
