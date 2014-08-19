var RightBox = new Class({

	Implements: [Options, Events],
	
	options: {
		gray_bg: '#e7e7e7',
		gloss_bg: '#9fc9e4',
		select_bg: '#2687ae',
		text_color: '#363636',
		def_color: '#ffffff',
		load_cover_opacity: 0.7,
		loader_size: 48,
		tooltip_offset_x: 30,
		tooltip_offset_y: 0
	},
	
	initialize: function(container, options) {
	
		this.setOptions(options);
		this.container = document.id(container);
		this.content = this.container.getElementById('gp_right_box_content');
		this.tooltip_exists = false;
		
		this.request_lenses_by_company_id = new Request.JSON({
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
			onSuccess: function(lenses) {
				this.end_loader();
				this.populate_lens_list_by_company_id(lenses);
			//	console.log(lenses);
			}.bind(this)
		});
		
		this.request_lenses_by_searchstring = new Request.JSON({
			url: 'index.php',
			method: 'get',
			link: 'cancel',
			onRequest: function() {
				this.start_loader();
				this.fireEvent('requestsearchstring');
			}.bind(this),
			onFailure: function() {
				// handle?
			}.bind(this),
			onCancel: function() {
				this.end_loader();
				this.fireEvent('endrequestsearchstring');
			}.bind(this),
			onSuccess: function(lenses) {
				this.end_loader();
				this.populate_lens_list_by_searchstring(lenses);
				this.fireEvent('endrequestsearchstring');
			//	console.log(lenses);
			}.bind(this)
		});
		
		this.request_lenses_by_parameters = new Request.JSON({
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
			onSuccess: function(lenses) {
				this.end_loader();
				this.populate_lens_list_by_company_id(lenses);
			//	console.log(lenses);
			}.bind(this)
		});
	
	},
	
	load_lenses_by_company_ids: function(ids) {
	
		this.request_lenses_by_company_id.send({
			data: {
				'option': 'com_gplens',
				'task': 'getLensesByCompanyId',
				'ids': JSON.encode(ids)
			}
		});
	
	},
	
	load_lenses_by_searchstring: function(string) {
	
		if(string != '') {
	
			this.searchstring = string;
		
			this.request_lenses_by_searchstring.send({
					data: {
						'option': 'com_gplens',
						'task': 'getLensesBySearchstring',
						'string': string
					}
				});		
		
		}
		
		else {
		
			this.clear_results();
			this.request_lenses_by_searchstring.cancel();
		
		}
		
	},
	
	load_lenses_by_parameters: function(params) { // takes 3-parameter object with 'category', 'subcategory', and 'material'
	
		this.request_lenses_by_parameters.send({
			data: {
				'option': 'com_gplens',
				'task': 'getLensesByParameters',
				'params': JSON.encode(params)
			}
		});		
	
	},
	
	populate_lens_list_by_company_id: function(lenses) {
	
		this.content.set('html', '');
		this.lenses = [];
	
		lenses.each(function(l) {
			
			var lens = new Element('a', {
				html: l.lens_name,
				href: '#',
				'class': 'gp_lens_listing'
			}).inject(this.content);
			
			lens.store('id', l.tid);
			lens.store('lens_name', l.lens_name);
			lens.store('company', l.company);
			lens.store('type', l.type);
			lens.store('subtype', l.subtype);
			
			this.lenses.include(lens);
						
		//	i++;
			
		}.bind(this));
		
		this.lenses.each(function(lens) {
		
			lens.addEvents({
			
				'mouseenter': function(e) {
				
					lens.setStyle('background-color', this.options.gloss_bg);
					this.tooltip_exists = true;
					this.create_tooltip(lens, e.page.x, e.page.y);
				
				}.bind(this),
			
				'mouseleave': function() {
				
					lens.setStyle('background-color', this.options.def_color);
					this.destroy_tooltip();
				
				}.bind(this),
				
				'click': function(event) {
				
					event.stop();
				
				}.bind(this),
				
				'mousedown': function() {
				
					this.selected_lens = lens.retrieve('id');
					this.fireEvent('lensclick');
				
				}.bind(this)
			
			});
		
		}.bind(this));
	
	},
	
	populate_lens_list_by_searchstring: function(lenses) {
	
	//	console.log(lenses);
	
		if(lenses == undefined) {
			this.clear_results();
			return;
		}
	
		this.content.set('html', '');
		this.lenses = [];
	
		lenses.each(function(l) {
		
			var bolder_opener_html = '<span class="gp_searchstring_bolder">';
			var bolder_closer_html = '</span>';
			var starter_position = l.lens_name.toLowerCase().indexOf(this.searchstring.toLowerCase());
			var ender_position = starter_position + this.searchstring.length;
			
			var starter = l.lens_name.substring(0, starter_position);
			var middle = l.lens_name.substring(starter_position, ender_position);
			var ender = l.lens_name.substring(ender_position, l.lens_name.length);		
			
			var bolded_name = starter + bolder_opener_html + middle + bolder_closer_html + ender;
			
			var lens = new Element('a', {
				html: bolded_name,
				href: '#',
				'class': 'gp_lens_listing'
			}).inject(this.content);
			
			lens.store('id', l.tid);
			lens.store('lens_name', l.lens_name);
			lens.store('company', l.company);
			lens.store('type', l.type);
			lens.store('subtype', l.subtype);
			
			this.lenses.include(lens);
						
		//	i++;
			
		}.bind(this));
		
		this.lenses.each(function(lens) {
		
			lens.addEvents({
			
				'mouseenter': function(e) {
				
					lens.setStyle('background-color', this.options.gloss_bg);
					this.tooltip_exists = true;
					this.create_tooltip(lens, e.page.x, e.page.y);
				
				}.bind(this),
			
				'mouseleave': function() {
				
					lens.setStyle('background-color', this.options.def_color);
					this.destroy_tooltip();
				
				}.bind(this),
				
				'click': function(event) {
				
					event.stop();
				
				}.bind(this),
				
				'mousedown': function() {
				
					this.selected_lens = lens.retrieve('id');
					this.fireEvent('lensclick');
				
				}.bind(this)
			
			});
		
		}.bind(this));
	
	},
	
	create_tooltip: function(lens, startx, starty) {
		
		this.tooltip = new Element('div', {
			html: '<div class="gp_tooltip_lens">' + lens.retrieve('lens_name') + '</div><div class="gp_tooltip_company"><div class="gp_tooltip_label">Company:</div> ' + lens.retrieve('company') + '</div><div class="gp_tooltip_type"><div class="gp_tooltip_label">Type:</div> ' + lens.retrieve('type') + '</div><div class="gp_tooltip_subtype"><div class="gp_tooltip_label">Subtype:</div> ' + lens.retrieve('subtype') + '</div>',
			'class': 'gp_tooltip_eyedock',
			styles: {
				top: starty + this.options.tooltip_offset_y,
				left: startx + this.options.tooltip_offset_x
			}
		}).inject(document.body);

			
		window.addEvent('mousemove', function(e) {
			
			if(this.tooltip_exists == true) {
			
				this.tooltip.setStyles({
					top: e.page.y + this.options.tooltip_offset_y,
					left: e.page.x + this.options.tooltip_offset_x
				});
				
			}
				
		}.bind(this));
	
	},
	
	destroy_tooltip: function() {
	
		this.tooltip.destroy();
		window.removeEvents('mousemove');
	
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
			src: '/components/com_gplens/img/loader.gif',
			styles: {
				'position': 'relative',
				'padding-top': ((content_size.y-this.options.loader_size)/2)-20
			}
		}).inject(this.cover);
	
	},
	
	end_loader: function() {
	
		$$('.load_cover').each(function(c) { c.destroy(); });
	
	},
	
	clear_results: function() {
		
		this.content.set('html', '');
		
	}
	
});
