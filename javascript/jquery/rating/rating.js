// JavaScript Document
/*************************************************
Star Rating System
First Version: 21 November, 2006
Author: Ritesh Agrawal
Inspriation: Will Stuckey's star rating system (http://sandbox.wilstuckey.com/jquery-ratings/)
Demonstration: http://php.scripts.psu.edu/rja171/widgets/rating.php
Usage: $('#rating').rating('www.url.to.post.com', {maxvalue:5, curvalue:0});

arguments
url : required -- post changes to 
options
	maxvalue: number of stars
	curvalue: number of selected stars
	//added by tmz
	table: the database table that needs to be updated
	//itemID: the item ID - will get from the element id instead (element ID must be named #star1234)
	userID: the user ID
	

//has been hacked by tmz - will only work with 1 star (maxvalue = 1)

************************************************/

jQuery.fn.rating = function(url, options) {
	
	if(url == null) return;
	
	var settings = {
        url       : url, // post changes to 
        maxvalue  : 5,   // max number of stars
        curvalue  : 0,    // number of selected stars
        table	  : null,
        userID	  : null
    };
	
    if(options) {
       jQuery.extend(settings, options);
    };
   jQuery.extend(settings, {cancel: (settings.maxvalue > 1) ? true : false});
   
   
   var container = jQuery(this);
	
	jQuery.extend(container, {
            averageRating: settings.curvalue,
            url: settings.url
        });

	for(var i= 0; i <= settings.maxvalue ; i++){
		var size = i
        if (i == 0) {
			if(settings.cancel == true){
	             var div = '<div class="cancel"><a href="#0" title="Cancel Rating">Cancel Rating</a></div>';
				 container.append(div);
			}
        } 
		else {
             var div = '<div class="star"><a href="#'+i+'" title="favorite lens">'+i+'</a></div>';
			 container.append(div);

        }
 
		

	}
	
	var stars = jQuery(container).children('.star');
    var cancel = jQuery(container).children('.cancel');
	
    stars
	        .mouseover(function(){
                event.drain();
                event.fill(this);
            })
            .mouseout(function(){
                event.drain();
                event.reset();
            })
            .focus(function(){
                event.drain();
                event.fill(this)
            })
            .blur(function(){
                event.drain();
                event.reset();
            });

    stars.click(function(){
    
    	//alert (jQuery(this).attr("id"));
    	//extract the itemID # from the Div ID
    	var itemEl = (jQuery(this).closest(".rating").attr("id") );
		var itemID = itemEl.replace(/star/, '');
		
		//reset the div element with the class "star" - wasn't' working with dynamic content
		var starEL = jQuery('.star');

		if(settings.cancel == true){
            settings.curvalue = stars.index(jQuery('.star')) + 1;
            jQuery.post(container.url, {
                //"rating": jQuery(this).children('a')[0].href.split('#')[1],
                "rating": settings.curvalue,
                "table": settings.table,
                "itemID": itemID,
                "userID": settings.userID 
            });
			return false;
		}
		else if(settings.maxvalue == 1){
			settings.curvalue = (settings.curvalue == 0) ? 1 : 0;
			//alert (settings.curvalue);
			jQuery(jQuery('.star')).toggleClass('on');
			jQuery.post(container.url, {
                //"rating": jQuery(this).children('a')[0].href.split('#')[1],
                "rating": settings.curvalue,
                "table": settings.table,
                "itemID": itemID,
                "userID": settings.userID 
            });
            //event.reset();
			return false;
		}
		return true;
			
    });

        // cancel button events
	if(cancel){
        cancel
            .mouseover(function(){
                event.drain();
                jQuery(this).addClass('on')
            })
            .mouseout(function(){
                event.reset();
                jQuery(this).removeClass('on')
            })
            .focus(function(){
                event.drain();
                jQuery(this).addClass('on')
            })
            .blur(function(){
                event.reset();
                jQuery(this).removeClass('on')
            });
        
        // click events.
        cancel.click(function(){
            event.drain();
			settings.curvalue = 0;
            jQuery.post(container.url, {
                "rating": jQuery(this).children('a')[0].href.split('#')[1] 
            });
            return false;
        });
	}
        
	var event = {
		fill: function(el){ // fill to the current mouse position.
			var index = stars.index(el) + 1;
			stars
				.children('a').css('width', '100%').end()
				.slice(0,index).addClass('hover').end();
		},
		drain: function() { // drain all the stars.
			stars
				.filter('.on').removeClass('on').end()
				.filter('.hover').removeClass('hover').end();
		},
		reset: function(){ // Reset the stars to the default index.
			if (settings.curvalue==1) jQuery('.star').addClass('on');
			//stars.slice(0,settings.curvalue).addClass('on').end();
		}
	}        
	event.reset();
	
	return(this);	

}