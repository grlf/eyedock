(function($) {
    $.fn.extend({
        //pass the options variable to the function         
        formatRxField: function() {
            //Set the default values, use comma to separate the settings,          
            var defaults = {
                round: 0.25,
                maxSph: 30,
                maxCyl: 15,
                strict: 0 //if 1 it will not format strings that could be something besides an Rx 
            };
            var options = $.extend(defaults, options);
            return $(this).each(function() {
                var errorEl = $(this).nextAll(".rxFieldError:first");

                // Save current value of element    
                $(this).data('oldVal', $(this).val());
                // Look for changes in the value    
                $(this).bind("propertychange keyup input paste", function(event) { // If value has changed...       
                    if ($(this).data('oldVal') != $(this).val()) { // Updated stored value        
                        $(this).data('oldVal', $(this).val());
                        $(errorEl).html(" ");

                    }
                });


                $(this).focusout(function() {
              		triggerRxCheck(this);

                });
                
                $(this).keypress(function(event){
                	var keycode = (event.keyCode ? event.keyCode : event.which);
					if(keycode == '13') triggerRxCheck(this);
			});

            //    });
                
                function triggerRxCheck (el) {
                	var val = $(el).val(); 
                  	//errorEl = $(this).nextAll(".rxFieldError:first");
                    var sph = "";
                    var cyl = "";
                    var axis = "";
                    
                    //if in strict mode, do not try to change values that may not be an Rx, such as "2clear" lenses - if not strict it will remove the letters and call it a +2.00 lens

					if (defaults.strict ==1 && (! /^\s?[+-]?\d+\.?/.test(val) || /[^\+\-\d\.sphereplano]/.test(val) || /[cle]/.test(val)   )  ) return;
					
					
                    val = val.replace(/\s/g, "");
                    val = val.replace(/[^0-9\.\+\*\-\x\X]/g, "");
                    var patt1 = new RegExp(/^([-\+]?\d*\.?\d{0,2})([-\+]+\d*\.?\d{0,2})[xX*]{1}(\d{1,3})?$/); //spherocylinder regex
                    var patt2 = new RegExp(/^([-\+]?\d *\.?\d{0,2})$/); //sphere regex
                    if (patt1.test(val)) {
                        var test = val.match(patt1);
                        sph = parseFloat(test[1]);
                        cyl = parseFloat(test[2]);
                        axis = parseInt(test[3], 10);
                        
                        if (Math.abs(sph) > options.maxSph || Math.abs(cyl) > options.maxCyl || axis>180 || axis <0) {
                            $(errorEl).html("A power or axis is not within accepted limits");
                            //alert ("too high!");
                        }else{
                         //pad with leading zeros
                         	axis = axis.toString();
							while (axis.length < 3) axis = "0" + axis; 
							var sphPlus = sph>0?"+":"";
							var cylPlus = cyl>0?"+":"";
							$(el).val(sphPlus + sph.toFixed(2) + " " + cylPlus + cyl.toFixed(2) + " x " + axis);
                        }

                        

                    } else if (patt2.test(val)) {
                        var test = val.match(patt2);
                        sph = parseFloat(val);
                        if (Math.abs(sph) > options.maxSph) {
                            $(errorEl).html("Sphere power is too high");
                           // alert ("too high!");
                        }else{
                         	var sphPlus = sph>0?"+":"";
                            $(el).val(sphPlus + sph.toFixed(2));
                        }
                        
                    } else {
                        if (val !="") $(errorEl).html("Please use the correct format");

                    }
                }
                
            });
        }

    });
})(jQuery);
