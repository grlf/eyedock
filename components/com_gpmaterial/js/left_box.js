var LeftBox = new Class({

	Implements: [Options, Events],
	
	options: {
		gray_bg: '#e7e7e7',
		gloss_bg: '#9fc9e4',
		select_bg: '#2687ae',
		text_color: '#363636',
		def_color: '#ffffff',
		load_cover_opacity: 0.7,
		loader_size: 48
	},
	
	initialize: function(container, options) {
	
		this.setOptions(options);
		this.container = document.id(container);
		this.content = this.container.getElementById('gp_left_box_content');
		
		this.state = ''; // company or parameter
		this.selected_companies = [];
		this.selected_companies_dom = [];
		
		this.dk_value = 0;
		this.wetting_value = 0;
		this.refractive_value = 1.4;
		this.specific_value = 1;
		
		this.moving_slider = false;
		
		this.request_companies = new Request.JSON({
			url: 'index.php',
			method: 'get',
			link: 'cancel',
			onRequest: function() {
				this.start_loader();
			}.bind(this),
			onFailure: function() {
				// handle?
			}.bind(this),
			onCancel: function() {
				this.end_loader();
			}.bind(this),
			onSuccess: function(companies) {
				this.end_loader();
				this.populate_companies_list(companies);
			}.bind(this)
		});
	
	},
	
	load_company_search: function() {
	
		this.state = 'company';

		this.request_companies.send({
			data: {
				'option': 'com_gpmaterial',
				'task': 'getCompanyList'
			}
		});
	
	},
	
	populate_companies_list: function(companies) {
		
		this.content.set('html', '');
		this.companies = [];
	
		companies.each(function(c) {
			
			var company = new Element('a', {
				html: c.name,
				href: '#',
				'class': 'gp_company_listing'
			}).inject(this.content);
			
			company.store('id', c.tid);
			company.store('selection_status', false);
			
			this.companies.include(company);
			
		}.bind(this));
	
		this.companies.each(function(company) {
			
			company.addEvents({
			
				'mouseenter': function() {
					
					if(company.retrieve('selection_status') == false) {
						company.setStyle('background-color', this.options.gloss_bg);
					}
						
				}.bind(this),
					
				'mouseleave': function() {
					
					if(company.retrieve('selection_status') == false) {
						company.setStyle('background-color', this.options.def_color);
					}
						
				}.bind(this),
					
				'mousedown': function() {
					
					if(company.retrieve('selection_status') == false) {	
					
						this.deselect_all_companies();
					
						company.setStyles({
							'background-color': this.options.select_bg,
							'color': '#ffffff'
						});
							
						company.store('selection_status', true);
						this.selected_companies.include(company.retrieve('id'));
						this.selected_companies_dom.include(company);
					//	console.log(this.selected_companies);
						this.fireEvent('materialselection');
												
					}

					else if(company.retrieve('selection_status') == true) {
						
						company.setStyles({
							'background-color': this.options.def_color,
							'color': this.options.text_color
						});
							
						company.store('selection_status', false);
						this.selected_companies.erase(company.retrieve('id'));
						this.selected_companies_dom.erase(company);
					//	console.log(this.selected_companies);
						this.fireEvent('materialselection');					
						
					}				
						
				}.bind(this),
					
				'click': function(event) {
					
					event.stop();
					
				}.bind(this)
			
			});
		
		}.bind(this));
	
	},
	
	deselect_all_companies: function() {
	
		this.selected_companies_dom.each(function(company) {
		
			company.setStyles({
				'background-color': this.options.def_color,
				'color': this.options.text_color
			});
							
			company.store('selection_status', false);
			this.selected_companies.erase(company.retrieve('id'));
			this.fireEvent('materialselection');		
						
		}.bind(this));
		
		this.selected_companies = [];
		this.selected_companies_dom = [];
	
	},
	
	load_parameter_search: function() {
	
		this.state = 'parameter';
	
		this.populate_parameters();
	
	},
	
	populate_parameters: function(params) {
	
		this.content.set('html', '<table id="slider_table" border="0" cellspacing="5" cellpadding="0">\
										<tbody>\
											<tr id="dk_row">\
												<td>Dk\
												<div id="dk_greater_less_than" class="greater_less_than"><a class="greater_than" href="#">Greater than</a> | <a href="#" class="less_than" >Less than</a></div"</td>\
												<td valign="top"></td>\
											</tr>\
											<tr id="wettingangle_row">\
												<td>Wetting Angle\
												<div id="wettingangle_greater_less_than" class="greater_less_than"><a class="greater_than" href="#">Greater than</a> | <a href="#" class="less_than" >Less than</a></div"</td>\
												<td valign="top"></td>\
											</tr>\
											<tr id="refractiveindex_row">\
												<td>Refractive Index\
												<div id="refractiveindex_greater_less_than" class="greater_less_than"><a class="greater_than" href="#">Greater than</a> | <a href="#" class="less_than" >Less than</a></div"</td>\
												<td valign="top"></td>\
											</tr>\
											<tr id="specificgravity_row">\
												<td>Specific Gravity\
												<div id="specificgravity_greater_less_than" class="greater_less_than"><a class="greater_than" href="#">Greater than</a> | <a href="#" class="less_than" >Less than</a></div"</td>\
												<td valign="top"></td>\
											</tr>\
										</tbody>\
									</table>');
		
		// dk slider
		var dk_slider = new Element('div', {
			html: '',
			'class': 'slider_track',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_track.gif")',
				display: 'block',
				width: 150,
				height: 4
			}
		}).inject(document.id('dk_row').getLast('td')).addEvent('mousedown', function() { this.fireEvent('sliderchange'); }.bind(this));
			
		var dk_knob = new Element('div', {
			html: '',
			'class': 'slider_knob',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_knob.gif")',
				display: 'block',
				width: 12,
				height: 12
			}
		}).inject(document.id('dk_row').getLast('td')).addEvent('mousedown', function() { this.moving_slider = true; }.bind(this));
		
		var dk_value = new Element('div', {
			html: '0',
			styles: {
				position: 'absolute',
				'font-size': 9,
				left: 3,
				top: -20,
				'font-weight': 'normal'
			}
		}).inject(dk_knob);

		this.dk_slider = new Slider(dk_slider, dk_knob, {
			range: [0,100],
			wheel: true,
			snap: true,
			onChange: function(step) {
				dk_value.set('html', step);
				this.dk_value = step;
			//	this.fireEvent('sliderchange');
			}.bind(this)
		});
		
		
		// wetting angle slider
		var wetting_slider = new Element('div', {
			html: '',
			'class': 'slider_track',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_track.gif")',
				display: 'block',
				width: 150,
				height: 4
			}
		}).inject(document.id('wettingangle_row').getLast('td')).addEvent('mousedown', function() { this.fireEvent('sliderchange'); }.bind(this));
			
		var wetting_knob = new Element('div', {
			html: '',
			'class': 'slider_knob',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_knob.gif")',
				display: 'block',
				width: 12,
				height: 12
			}
		}).inject(document.id('wettingangle_row').getLast('td')).addEvent('mousedown', function() {  this.moving_slider = true; }.bind(this));
		
		var wetting_value = new Element('div', {
			html: '0',
			styles: {
				position: 'absolute',
				'font-size': 9,
				left: 3,
				top: -20,
				'font-weight': 'normal'
			}
		}).inject(wetting_knob);

		this.wetting_slider = new Slider(wetting_slider, wetting_knob, {
			range: [0,50],
			wheel: true,
			snap: true,
			onChange: function(step) {
				wetting_value.set('html', step);
				this.wetting_value = step;
			//	this.fireEvent('sliderchange');
			}.bind(this)
		});
		
		
		// refractive index slider
		var refractive_slider = new Element('div', {
			html: '',
			'class': 'slider_track',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_track.gif")',
				display: 'block',
				width: 150,
				height: 4
			}
		}).inject(document.id('refractiveindex_row').getLast('td')).addEvent('mousedown', function() { this.fireEvent('sliderchange'); }.bind(this));
			
		var refractive_knob = new Element('div', {
			html: '',
			'class': 'slider_knob',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_knob.gif")',
				display: 'block',
				width: 12,
				height: 12
			}
		}).inject(document.id('refractiveindex_row').getLast('td')).addEvent('mousedown', function() {  this.moving_slider = true; }.bind(this));
		
		var refractive_value = new Element('div', {
			html: '1.40',
			styles: {
				position: 'absolute',
				'font-size': 9,
				left: 3,
				top: -20,
				'font-weight': 'normal'
			}
		}).inject(refractive_knob);

		this.refractive_slider = new Slider(refractive_slider, refractive_knob, {
			range: [140,156],
			wheel: true,
			snap: true,
			onChange: function(step) {
				refractive_value.set('html', step/100);
				this.refractive_value = step/100;
			//	this.fireEvent('sliderchange');
			}.bind(this)
		});
		
		
		// specific gravity slider
		var specific_slider = new Element('div', {
			html: '',
			'class': 'slider_track',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_track.gif")',
				display: 'block',
				width: 150,
				height: 4
			}
		}).inject(document.id('specificgravity_row').getLast('td')).addEvent('mousedown', function() { this.fireEvent('sliderchange'); }.bind(this));
			
		var specific_knob = new Element('div', {
			html: '',
			'class': 'slider_knob',
			styles: {
				'background-image': 'url("/components/com_gpmaterial/img/slider_knob.gif")',
				display: 'block',
				width: 12,
				height: 12
			}
		}).inject(document.id('specificgravity_row').getLast('td')).addEvent('mousedown', function() {  this.moving_slider = true; }.bind(this));
		
		var specific_value = new Element('div', {
			html: '1.00',
			styles: {
				position: 'absolute',
				'font-size': 9,
				left: 3,
				top: -20,
				'font-weight': 'normal'
			}
		}).inject(specific_knob);

		this.specific_slider = new Slider(specific_slider, specific_knob, {
			range: [100,121],
			wheel: true,
			snap: true,
			onChange: function(step) {
				specific_value.set('html', step/100);
				this.specific_value = step/100;
			//	this.fireEvent('sliderchange');
			}.bind(this)
		});
		
		this.dk_greater_less = new GreaterLess('dk_greater_less_than', 'gp_left_box_content', { onGlchange: function() { this.fireEvent('sliderchange'); }.bind(this) });
		this.wettingangle_greater_less = new GreaterLess('wettingangle_greater_less_than', 'gp_left_box_content', { onGlchange: function() { this.fireEvent('sliderchange'); }.bind(this) });
		this.refractiveindex_greater_less = new GreaterLess('refractiveindex_greater_less_than', 'gp_left_box_content', { onGlchange: function() { this.fireEvent('sliderchange'); }.bind(this) });
		this.specificgravity_greater_less = new GreaterLess('specificgravity_greater_less_than', 'gp_left_box_content', { onGlchange: function() { this.fireEvent('sliderchange'); }.bind(this) });
		
		this.fireEvent('sliderchange'); // starting query
		
		document.addEvents({
		
			'mouseup': function() {
			
				if(this.moving_slider) {
					this.fireEvent('sliderchange');
				}
				
				this.moving_slider = false;
			
			}.bind(this)
		
		});
		
	},
	
	start_loader: function() {
	
		var content_position = this.content.getCoordinates();
		var content_size = this.content.getSize();
		
		this.cover = new Element('div', {
			html: '',
			'class': 'load_cover',
			styles: {
				'position': 'absolute',
				'top': content_position.top,
				'left': content_position.left,
				'height': content_size.y,
				'width': content_size.x,
				'background-color': '#ffffff',
				'opacity': this.options.load_cover_opacity,
				'text-align': 'center'
			}
		}).inject(document.body);
		
		var loader = new Element('img', {
			src: '/components/com_gpmaterial/img/loader.gif',
			styles: {
				'position': 'relative',
				'padding-top': ((content_size.y-this.options.loader_size)/2)-20
			}
		}).inject(this.cover);
	
	},
	
	end_loader: function() {
	
		$$('.load_cover').each(function(c) { c.destroy(); });
	
	}
	
});

var GreaterLess = new Class({

	Implements: [Options, Events],
	
	options: {
		slider_height: 2,
		duration: 300,
		fps: 50,
		transition: 'quad:out'
	},

	initialize: function(container, shell, options) {
	
		this.setOptions(options);
		this.container = document.id(container);
		this.shell = document.id(shell);
		
		this.status = 'greater'; // greater or less
		
		this.greater = this.container.getElement('a[class=greater_than]');
		this.less = this.container.getElement('a[class=less_than]');
		
		this.greater_pos = this.greater.getCoordinates(document.id('wrapper'));
		this.less_pos = this.less.getCoordinates(document.id('wrapper'));
		
		this.greater_width = this.greater_pos.right - this.greater_pos.left;
		this.less_width = this.less_pos.right - this.less_pos.left;

	/*	this.greater_size = this.greater.getSize();
		this.less_size = this.less.getSize();
	*/	
		if(!Browser.Engine.trident) {
		
			this.slider = new Element('div', {
				html: '',
				'class': 'greater_less_slider',
				styles: {
					'width': this.greater_width - 5,
					'height': this.options.slider_height,
					'background-color': '#2699B6',
					'position': 'absolute',
					'top': this.greater_pos.bottom + 2,
					'left': this.greater_pos.left + (((this.greater_pos.right - this.greater_pos.left) - (this.greater_width - 5)) / 2),
					'display': 'block'
				}
			}).inject(this.shell);
		
		}
		
		this.slide = new Fx.Morph(this.slider, {
			duration: this.options.duration,
			transition: this.options.transition,
			fps: this.options.fps,
			link: 'cancel'
		});
		
		if(Browser.Engine.trident) {
			this.greater.setStyle('border-bottom', '2px solid #2687AE');
		}
		
		this.add_events();
	
	},
	
	add_events: function() {
	
		this.greater.addEvents({
		
			'mousedown': function() {
			
				if(this.status == 'less') {
				
					var pos = this.greater_pos.left + (((this.greater_pos.right - this.greater_pos.left) - (this.greater_width - 5)) / 2);
					if(!Browser.Engine.trident) { this.slide.start({ 'left': pos, 'width': this.greater_width-5 }); }
					else { this.greater.setStyle('border-bottom', '2px solid #2687AE'); this.less.setStyle('border-bottom', 'none'); }
					this.status = 'greater';
					
					this.fireEvent('glchange');
				
				}
			
			}.bind(this),
			
			'click': function(e) {
				
				e.stop();
				
			}
		
		});
		
		this.less.addEvents({
		
			'mousedown': function() {
			
				if(this.status == 'greater') {
				
					var pos = this.less_pos.left + (((this.less_pos.right - this.less_pos.left) - (this.less_width - 5)) / 2);
					if(!Browser.Engine.trident) { this.slide.start({ 'left': pos, 'width': this.less_width-5 }); }
					else { this.less.setStyle('border-bottom', '2px solid #2687AE'); this.greater.setStyle('border-bottom', 'none'); }
					this.status = 'less';
					
					this.fireEvent('glchange');
				
				}
			
			}.bind(this),
			
			'click': function(e) {
				
				e.stop();
				
			}
		
		});
	
	}
	
});
