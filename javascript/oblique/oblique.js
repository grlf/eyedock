var currentAngle = 0;
var clAxis = 0;
var orAxis = 0;
var resultAxis = 0;
var totalSize = 166;
var lensSize = 130;
var gutter = 18;

jQuery(document).ready(function() {

	jQuery('#oblique-clear_btn').click(function(){
		//clear all button
		jQuery("#clRx").val("plano");
   		jQuery("#orRx").val("plano");
   		jQuery("#xcl_result").val("plano");
   		currentAngle = 0;
   		inputChange();
   		rotateLenses();
   		doXcylCalc();
	});

	jQuery('.rxField').formatRxField({
	    round: 0.25
	});

	
	jQuery("#rotate_left_btn").click(function () {
		currentAngle += 1;
	    rotateLenses();
	
	});
	
	jQuery("#rotate_right_btn").click(function () {
		currentAngle -= 1;
	    rotateLenses();
	});
	
	jQuery("#rotate_left5_btn").click(function () {
		currentAngle += 10;
	    rotateLenses();
	
	});
	
	jQuery("#rotate_right5_btn").click(function () {
		currentAngle -= 10;
	    rotateLenses();
	});
	
	jQuery("#cl_lens_container").click(function (e) {
	    var offset = jQuery(this).offset();
	    var relX = e.pageX - offset.left;
	    var relY = e.pageY - offset.top;
	    //alert (relY);
	    var r = totalSize / 2;
	    if (relY < r) return; // don't do anything if they're clicking the top half of the div
	    if (relY > jQuery('.lens_container').height()) return; // don't do anything if they're clicking below the div (this was an issue when the inner div was rotated - it stuck under like a diamond)
	
	    var angl = -Math.atan((relX - r) / (relY - r)) * 180 / Math.PI;
	    currentAngle = Math.floor(angl);	  
	    rotateLenses();
	    doXcylCalc();
	});
	
	
	    jQuery('.rxField').keyup(function (e) {
	        inputChange(e);
	    });

   
	jQuery('.rxField').on('paste', function () {
	  setTimeout(function () {
		inputChange(e);
	  }, 100);
	});
		
	    
	    
	  	jQuery('.rxField').on("paste", function() {
	        inputChange();
	    });  

	jQuery('#info_button').click(function(){
		jQuery('#info_div').toggle("slow");
		
	});
	
	//get some dimensions of the elements we'll be working with
	totalSize = jQuery("#cl_lens_container").width();
	lensSize = jQuery("#cl_lens").width();
	gutter = (totalSize - lensSize )/2;

});

function padNumber(n) {
    return (n < 10) ? ("0" + n) : n;
}

function doXcylCalc() {
    var clRx = rxStringBreaker(jQuery("#clRx").val(), 1, 0.25);
    var orRx = rxStringBreaker(jQuery("#orRx").val(), 1, 0.25);
    if (isNaN(orRx.sph)) return;
	orRx = orRx.diffVertex(0);
    clRx.axis -= currentAngle;
    var newRx = clRx.addRx(orRx);
    newRx.axis += currentAngle;
    resultAxis = newRx.axisM();
    var newRxString = newRx.prettyStringMinus();
    if (newRxString.indexOf('null') !== -1) newRxString = "";
    jQuery("#xcl_result").val(newRxString);
    labelImage(newRx, jQuery("#result_lens_container") );
}

function inputChange(e) {
    var clString = jQuery('#clRx').val();
    var clRx = rxStringBreaker(clString, 1, 0.25);
    clAxis = clRx.axisM();
    if (clRx) labelImage(clRx, jQuery("#cl_lens_container"));
    var orString = jQuery('#orRx').val();
    if (orString == "-") orString = "0";
    var orRx = rxStringBreaker(orString, 1, 0.25);
    orAxis = orRx.axisM();
    if (orRx) {
    	var m1 = orRx.sphP();
    	var m2 = orRx.sphP() + orRx.cylP();
    	if (Math.abs(m1)>=4 || Math.abs(m2)>=4 ){
    		orRx = orRx.diffVertex(0);
    		jQuery('#or_vertexed').html( "<span style='font-size:smaller'>(Vertexed to the corneal plane)</span>");
    	} else {
    		jQuery('#or_vertexed').html("");
    	}
      labelImage(orRx, jQuery("#or_lens_container") );
      var axis = orRx.axis;
      jQuery('#axis_arrow').rotate({animateTo: -axis });
      jQuery('#or_cross').rotate({animateTo: -axis });
    }
    if (clRx && orRx) doXcylCalc();
    rotateLenses();
}


function rotateLenses() {
    if (currentAngle >70) currentAngle = 70;
    if (currentAngle <-70) currentAngle = -70;
    setAxisLabel(currentAngle);
    jQuery('#cl_lens').rotate({
        animateTo: currentAngle
    });
    jQuery('#cl_cross').rotate({
        animateTo: currentAngle - 90-clAxis
    });
 
    jQuery('#result_lens').rotate({
        animateTo: currentAngle
    });
   	jQuery('#result_cross').rotate({
        animateTo: currentAngle - 90-resultAxis
    });
    doXcylCalc();
}

function setAxisLabel(angle) {
    //var dir = (currentAngle > 1) ? "&deg;" : "&deg;";
    jQuery('#dir_label').html(padNumber(Math.abs(currentAngle)) + "&deg;");
}



function labelImage(rx, container) {
	var horizPwr = numToDiopterString(rx.horizontalPower(),1,0.25);
	var vertPwr = numToDiopterString(rx.verticalPower(),1,0.25);

    if (horizPwr == "NaN") horizPwr = "";
    if (vertPwr == "NaN") vertPwr = "";
   
    var labelH = jQuery(container).children('.meridianH_label');
    var labelV = jQuery(container).children('.meridianV_label');
    var labelSph = jQuery(container).children('.sph_label');
  	var cross = jQuery(container).children('.cross');
    //clear all the labels
    jQuery(container).children('.label').html("");
    //if it's spherical, just use the middle label
    if (horizPwr == vertPwr) { //the lens is spherical
    	var rxString = rx.prettyString();
    	if (rxString.indexOf('null') !== -1) rxString = "";
    	jQuery(labelSph).html(rxString);
      	jQuery(cross).hide();
    } else { //the lens is toric
        jQuery(labelH).html(horizPwr);
        jQuery(labelV).html(vertPwr);
        jQuery(cross).show();
        //postionLabels(rx.axis, container);
    }
    positionAllLabels();
}

function positionAllLabels(){
	postionLabels (clAxis, jQuery("#cl_lens_container"));
	postionLabels (orAxis, jQuery("#or_lens_container"));
	postionLabels (resultAxis, jQuery("#result_lens_container"));
}


function postionLabels (axis, container){
	var labelH = jQuery(container).children('.meridianH_label');
    var labelV = jQuery(container).children('.meridianV_label');
    
    //if we're dealing with the CL or the result lens we need to compensate for rotation (not needed for OR)
    var rotation = 0;
    var idString =jQuery(container).attr("id")  ;
    if (idString.indexOf('or') == -1) rotation = currentAngle;
    var realAxis = fixAxis(axis - rotation);
    var vAngle = (axis>=45 && axis < 135)?realAxis:fixAxis(realAxis-90);
    var hAngle = fixAxis(vAngle-90);
    radH = hAngle * (Math.PI/180);
	radV = vAngle * (Math.PI/180);
	var hx = parseInt (  (Math.cos(radH ) * (lensSize/2 )) +  lensSize/2 );
	var hy = parseInt (  -(Math.sin(radH ) * (lensSize/2 )) + lensSize/2 + gutter );	
	var vx = parseInt ( (Math.cos(radV ) * (lensSize/2 )) +  lensSize/2 );
	var vy = parseInt ( -(Math.sin(radV ) * (lensSize/2 )) + lensSize/2 + gutter );
	jQuery(labelH).animate({'left' : hx, 'top' : hy},150);
	jQuery(labelV).animate({'left' : vx, 'top' : vy},150);
	
}

//var rxTest = rxStringBreaker("-3-2x135");

//labelImage (rxTest, jQuery("#cl_lens") );// JavaScript Document

