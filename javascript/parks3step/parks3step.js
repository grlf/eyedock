  
  var primary = "";
  var gaze = "";
  var tilt = "";
  
  jQuery(document).ready(function(){

    //now, fadeOut the stuff we worked so hard to create!
    jQuery("[id$='_line']").hide();
    jQuery("[id$='_circle']").hide();
    
    
  }) //end doc ready
    
    
    
function buttonClicked (e) {  
  var element = (e.target) ? e.target : e.srcElement;
  
  var parts = element.id.split("_"); 
  
  //alert (parts);
  
  window[parts[0]] = parts[1];
   
  var otherButton = parts[1] == "r"?"l":"r";
    //alert ("#" + element.id);
    jQuery("#" + element.id).removeClass("parks_off");
    jQuery("#" + element.id).addClass("parks_on");
    
    jQuery("#" + parts[0] + "_" + otherButton).removeClass("parks_on");
    jQuery("#" + parts[0] + "_" + otherButton).addClass("parks_off");
    
    //show the appropriate discussion div
    jQuery ("#d_" + parts[0]).fadeIn(300);
    jQuery ("#d_" + parts[0] + "_" + parts[1]).fadeIn(300);
    jQuery ("#d_" + parts[0] + "_" + otherButton).fadeOut(300);
      
    eliminateMuscles();
    
     //since a button was pushed we can show this discussion
  	jQuery("#parks_discussion").fadeIn(300);
}



function eliminateMuscles () {

  var eliminated = new Array();  
  
  if (primary == "r") {
    eliminated.push("rsr");
    eliminated.push("rio");
    eliminated.push("lso");
    eliminated.push("lir");
    
  } 
  
  if (primary == "l") {
    eliminated.push("rso");
    eliminated.push("rir");
    eliminated.push("lsr");
    eliminated.push("lio");
  } 
  
  if (gaze == "r") {
    eliminated.push("rso");
    eliminated.push("rio");
    eliminated.push("lsr");
    eliminated.push("lir");
  } 
  
  if (gaze == "l") {
    eliminated.push("rsr");
    eliminated.push("rir");
    eliminated.push("lso");
    eliminated.push("lio");
  } 
  
  if (tilt == "r") {
    eliminated.push("rir");
    eliminated.push("rio");
    eliminated.push("lso");
    eliminated.push("lsr");
  } 
  
  if (tilt == "l") {
    eliminated.push("rso");
    eliminated.push("rsr");
    eliminated.push("lio");
    eliminated.push("lir");
  } 
  
  crossoutMuscles(eliminated);
  
}

function crossoutMuscles (eliminated) {
  //alert (eliminated);
  
  jQuery.each(eliminated, function(i, el){
    if(jQuery.inArray(el, eliminated) === -1) eliminated.push(el);
});

  //an array of all muscles - we'll cross out the eliminated ones
  var muscles = ["rso", "rsr", "rir", "rio", "lso", "lsr", "lir", "lio"];
  var muscleNames = ["Right Superior Oblique", "Right Superior Rectus", "Right Inferior Rectus", "Right Inferior Oblique", "Left Superior Oblique", "Left Superior Rectus", "Left Inferior Rectus", "Left Inferior Oblique"];
  
  //cross out eliminated muscles
  jQuery.each(eliminated, function(index, value) {
    //alert("cross: " + value);
    //cross out the eliminated muscles
    jQuery("#" + value + "_line").fadeIn(300);
  });
  
  //iterate through the muscles array - if it's not eliminated, fadeIn it
  //count the non-eliminated muscles - if there's only one, circle it
  var remainingMuscles = arr_diff (eliminated, muscles);
  jQuery.each(remainingMuscles, function(index, value) {
      jQuery("#" + value + "_line").fadeOut(300);

  });
  
  jQuery("[id$='_circle']").fadeOut(300);
  
  if (remainingMuscles.length == 1) {
  	jQuery("#" + remainingMuscles[0] + "_circle").fadeIn(300);
  	//what's the index of the remaining muscle in the muscles Array
  	var muscleIndex = jQuery.inArray( remainingMuscles[0], muscles );
  	var longName = muscleNames[muscleIndex];
  	jQuery("#d_answer").html("<b>Result:</b> The only EOM to not be eliminated is the <b>" + longName + "</b>");
  	jQuery("#d_answer").show();
  	}
}


function arr_diff(a1, a2) {
  var a=[], diff=[];
  for(var i=0;i<a1.length;i++)
  a[a1[i]]=true;
  for(var i=0;i<a2.length;i++)
  if(a[a2[i]]) delete a[a2[i]];
  else a[a2[i]]=true;
  for(var k in a)
  diff.push(k);
  return diff;
}
