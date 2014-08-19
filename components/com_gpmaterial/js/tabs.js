var Tabs = new Class({

	Implements: [Options, Events],
	
	options: {
		gloss_duration: 170,
		transition: 'quad:out',
		fps: 50,
		colors: {
			light: '#636363',
			dark: '#2687ae',
			gloss: '#6a767b'
		}
	},
	
	initialize: function(tab_company, tab_parameter, options) {
	
		this.setOptions(options);
		this.tab_company = document.id(tab_company);
		this.tab_parameter = document.id(tab_parameter);
		
		this.state = 'company'; // company or parameter
		this.build_events();
	
	},
	
	build_events: function() {
	
	$$('.tab').set('tween', {
		duration: this.options.gloss_duration,
		fps: this.options.fps,
		property: 'background-color',
		transition: this.options.transition,
		link: 'cancel'
	});
	
	this.tab_company.addEvents({
	
	/*	'mouseenter': function() {
		
			if(!this.tab_company.hasClass('active')) {
			
				this.tab_company.tween(this.options.colors.gloss);
			
			}
		
		}.bind(this),
	*/
		'mouseleave': function() {

			if(!this.tab_company.hasClass('active')) {
			
				this.tab_company.tween(this.options.colors.light);
			
			}
		
		}.bind(this),

		'mousedown': function() {
			
			if(!this.tab_company.hasClass('active')) {
			
				this.tab_company.get('tween').cancel();
				
				this.tab_company.setStyles({
				//	'background-color': this.options.colors.dark,
					'background-image': 'url("/components/com_gpmaterial/img/tab-blue-bg.gif")'
				}).toggleClass('active');
				
				this.tab_parameter.setStyles({
				//	'background-color': this.options.colors.light,
					'background-image': 'url("/components/com_gpmaterial/img/tab-gray-bg.gif")'
				}).toggleClass('active');
				
			//	this.state = 'company';
				
				this.fireEvent('newtab', 'company');
			
			}
		
		}.bind(this),
		
		'click': function(event) {
		
			event.stop();
		
		}.bind(this)
	
	});
	
	this.tab_parameter.addEvents({

	/*	'mouseenter': function() {
		
			if(!this.tab_parameter.hasClass('active')) {
			
				this.tab_parameter.tween(this.options.colors.gloss);
			
			}
		
		}.bind(this),
	*/
		'mouseleave': function() {

			if(!this.tab_parameter.hasClass('active')) {
			
				this.tab_parameter.tween(this.options.colors.light);
			
			}
		
		}.bind(this),

		'mousedown': function() {

			if(!this.tab_parameter.hasClass('active')) {
			
				this.tab_parameter.get('tween').cancel();
				
				this.tab_parameter.setStyles({
				//	'background-color': this.options.colors.dark,
					'background-image': 'url("/components/com_gpmaterial/img/tab-blue-bg.gif")'
				}).toggleClass('active');
				
				this.tab_company.setStyles({
				//	'background-color': this.options.colors.light,
					'background-image': 'url("/components/com_gpmaterial/img/tab-gray-bg.gif")'
				}).toggleClass('active');
				
			//	this.state = 'parameter';
				
				this.fireEvent('newtab', 'parameter');
			
			}
		
		}.bind(this),
		
		'click': function(event) {
		
			event.stop();
		
		}.bind(this)
	
	});
	
	}

});
