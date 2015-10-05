
function eyedock_slider_change(obj, id){

	var pctg = (obj.step / 100);

	if(id == 'power-slider'){
		var text = eyedock_slider_change_power(pctg);
	}else if(id == 'dk-slider'){
		var text = eyedock_slider_change_dk(pctg);
	}else if(id == 'water-slider'){
		var text = eyedock_slider_change_water(pctg);
	}else if(id == 'replacement1-slider' || id == 'replacement2-slider'){
		var text = eyedock_slider_change_replacement(pctg);
	}else if(id == 'toric-slider'){
		var text = eyedock_slider_change_toric(pctg);
	}else if(id == 'bifocal-slider'){
		var text = eyedock_slider_change_bifocal(pctg);
	}else{
		return;
	}

	var textid = id + '-value';

	if(document.getElementById(textid)){
		document.getElementById(textid).innerHTML = text;
	}

}

function eyedock_adv_slider_change(obj, id){

	var pctg = (obj.step / 100);

	if(id == 'adv-power-slider'){
		var text = eyedock_slider_change_power(pctg);
	}else if(id == 'adv-diameter-slider'){
		var text = eyedock_slider_change_diameter(pctg);
	}else if(id == 'adv-dk-slider'){
		var text = eyedock_slider_change_dk(pctg);
	}else if(id == 'adv-water-slider'){
		var text = eyedock_slider_change_water(pctg);
	}else if(id == 'adv-ct-slider'){
		var text = eyedock_slider_change_ct(pctg);
	}else if(id == 'adv-oz-slider'){
		var text = eyedock_slider_change_oz(pctg);
	}else if(id == 'adv-replacement1-slider' || id == 'adv-replacement2-slider'){
		var text = eyedock_slider_change_replacement(pctg);
	}else if(id == 'adv-toric-slider'){
		var text = eyedock_slider_change_toric(pctg);
	}else if(id == 'adv-bifocal-slider'){
		var text = eyedock_slider_change_bifocal(pctg);
	}else{
		return;
	}

	var textid = id + '-value';

	if(document.getElementById(textid)){
		document.getElementById(textid).innerHTML = text;
	}

}

function eyedock_slider_change_power(pctg){
	var psmin = 20;
	var psmax = -20;
	var psdiff = psmax - psmin;

	var psval = (psmin + (psdiff * pctg)).toInt();

	var text = (psval > 0?'+':'') + psval + '.00D';

	return text;
}

function eyedock_slider_change_dk(pctg){
	var dkmin = -150;
	var dkmax = 150;
	var dkdiff = dkmax - dkmin;

	var dkval = (dkmin + (dkdiff * pctg)).toInt();

	var text = (Math.abs(dkval) < 15?'0':Math.abs(dkval)) + (dkval >= 0?' or more':' or less');

	return text;
}

function eyedock_slider_change_water(pctg){
	var wsmin = -100;
	var wsmax = 100;
	var wsdiff = wsmax - wsmin;

	var wsval = (wsmin + (wsdiff * pctg)).toInt();

	var text = Math.abs(wsval) + '.0%' + (wsval >= 0?' or more':' or less');

	return text;
}

function eyedock_slider_change_replacement(pctg){

	var increment = 100 / 6;

	if(pctg * 100 < increment){
		var text = 'Daily';
	}else if( (pctg * 100) < (increment * 2) ){
		var text = '2 weeks';
	}else if( (pctg * 100) < (increment * 3) ){
		var text = 'Monthly';
	}else if( (pctg * 100) < (increment * 4) ){
		var text = '3 Months';
	}else if( (pctg * 100) < (increment * 5) ){
		var text = '6 Months';
	}else{
		var text = 'Yearly+';
	}

	return text;
}

function eyedock_slider_change_toric(pctg){
	var tsmin = -0.75;
	var tsmax = -6;
	var tsdiff = tsmax - tsmin;

	var tsval = (tsmin + (tsdiff * pctg)).round(2);

	var dec = tsval - Math.floor(tsval);

	if(dec > 0){
		if(dec >= 0.75){
			tsval = Math.floor(tsval) + 0.75;
		}else if(dec >= 0.5){
			tsval = Math.floor(tsval) + 0.5;
		}else if(dec >= 0.25){
			tsval = Math.floor(tsval) + 0.25;
		}else{
			tsval = Math.floor(tsval);
		}

		tsval = tsval.round(2);
	}

	var text = 'at least ' + tsval + 'D';

	return text;
}

function eyedock_slider_change_bifocal(pctg){
	var bsmin = 1;
	var bsmax = 3;
	var bsdiff = bsmax - bsmin;

	var bsval = bsmin + (bsdiff * pctg);

	if(Math.floor(bsval) == Math.floor(bsval + 0.5)){
		bsval = (Math.floor(bsval)).round(1);
	}else{
		bsval = (Math.floor(bsval) + 0.5).round(1);
	}

	var text = 'at least +' + bsval + 'D';

	return text;
}

function eyedock_slider_change_diameter(pctg){

	if(pctg == 0.5){
		var text = 'any';
	}else{
		var dsmin = -5;
		var dsmax = 5;
		var dsdiff = dsmax - dsmin;

		var dsval = (dsmin + (dsdiff * pctg)).round(1);

		var text = (Math.abs(dsval) + 13) + 'mm' + (dsval >= 0?' or more':' or less');
	}

	return text;
}

function eyedock_slider_change_ct(pctg){

	if(pctg == 0.5){
		var text = 'any';
	}else{
		var csmin = -0.1;
		var csmax = 0.1;
		var csdiff = csmax - csmin;

		var csval = (csmin + (csdiff * pctg)).round(2);

		var text = ( (Math.abs(csval) + 0.04).toString().replace(/(\.[\d]{2}).+$/,'$1') ) + 'mm' + (pctg >= 0.5?' or more':' or less');
	}

	return text;
}

function eyedock_slider_change_oz(pctg){

	if(pctg == 0.5){
		var text = 'any';
	}else{
		var osmin = -7;
		var osmax = 7;
		var osdiff = osmax - osmin;

		var osval = (osmin + (osdiff * pctg)).toInt();

		var text = (Math.abs(osval) + 5) + 'mm' + (osval >= 0?' or more':' or less');
	}

	return text;
}
