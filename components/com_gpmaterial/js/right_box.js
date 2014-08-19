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
		
		this.request_materials_by_company_id = new Request.JSON({
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
			onSuccess: function(materials) {
				this.end_loader();
				this.populate_material_list_by_company_id(materials);
			}.bind(this)
		});
		
		this.request_materials_by_searchstring = new Request.JSON({
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
				this.populate_materials_list_by_searchstring(lenses);
				this.fireEvent('endrequestsearchstring');
			//	console.log(lenses);
			}.bind(this)
		});
		
		this.request_materials_by_sliders = new Request.JSON({
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
			onSuccess: function(materials) {
				this.end_loader();
				if(materials != null) { this.populate_material_list_by_company_id(materials); }
				else { this.clear_results(); }
			//	console.log(lenses);
			}.bind(this)
		});
	
	},
	
	load_materials_by_company_ids: function(ids) {
	
		this.request_materials_by_company_id.send({
			data: {
				'option': 'com_gpmaterial',
				'task': 'getMaterialsByCompanyId',
				'ids': JSON.encode(ids)
			}
		});
	
	},
	
	load_materials_by_searchstring: function(string) {
	
		if(string != '') {
	
			this.searchstring = string;
		
			this.request_materials_by_searchstring.send({
					data: {
						'option': 'com_gpmaterial',
						'task': 'getMaterialsBySearchstring',
						'string': string
					}
				});		
		
		}
		
		else {
		
			this.clear_results();
			this.request_lenses_by_searchstring.cancel();
		
		}
		
	},
	
	load_materials_by_sliders: function(data) {
	
		this.request_materials_by_sliders.send({
			data: {
				'option': 'com_gpmaterial',
				'task': 'getMaterialsBySliders',
				'data': JSON.encode(data)
			}
		});		
	
	},
	
	populate_material_list_by_company_id: function(materials) {

		try{
			console.log(materials);
		}catch(e){}
	
		this.content.set('html', '');
		this.materials = [];
	
		materials.each(function(m) {
			
			var material = new Element('a', {
				html: m.material_name,
				href: '#',
				'class': 'gp_lens_listing'
			}).inject(this.content);
			
			material.store('id', m.id);
			if(m.company != null) { material.store('company', m.company	); } else { material.store('company', '?'); }
			if(m.materialtype != null) { material.store('type', m.materialtype); } else { material.store('type', '?'); }
			if(m.dk != null) { material.store('dk', m.dk); } else { material.store('dk', '?'); }
			if(m.refractiveindex != null) { material.store('refractiveindex', m.refractiveindex); } else { material.store('refractiveindex', '?'); }
			if(m.wetangle != null) { material.store('wetangle', m.wetangle); } else { material.store('wetangle', '?'); }
			if(m.material_name != null) { material.store('name', m.material_name); } else { material.store('name', '?'); }
			if(m.specificgravity != null) { material.store('specificgravity', m.specificgravity); } else { material.store('specificgravity', '?'); }
			
			this.materials.include(material);
			
		}.bind(this));
		
		this.materials.each(function(material) {
		
			material.addEvents({
			
				'mouseenter': function(e) {
				
					material.setStyle('background-color', this.options.gloss_bg);
					this.tooltip_exists = true;
					this.create_tooltip(material, e.page.x, e.page.y);
				
				}.bind(this),
			
				'mouseleave': function() {
				
					material.setStyle('background-color', this.options.def_color);
					this.destroy_tooltip();
				
				}.bind(this),
				
				'click': function(event) {
				
					event.stop();
				
				}.bind(this),
				
				'mousedown': function() {
				
					this.selected_lens = material.retrieve('id');
					this.fireEvent('materialclick');
				
				}.bind(this),
				
				'blink': function() {
					
					material.setStyle('visibility', 'hidden');
					material.setStyle('visibility', 'visible');
					
				}
			
			});
		
		}.bind(this));
	
	},
	
	populate_materials_list_by_searchstring: function(materials) {
	
	//	console.log(lenses);
	
		if(materials == undefined) {
			this.clear_results();
			return;
		}
	
		this.content.set('html', '');
		this.materials = [];
	
		materials.each(function(m) {
		
			var bolder_opener_html = '<span class="gp_searchstring_bolder">';
			var bolder_closer_html = '</span>';
			var starter_position = m.material_name.toLowerCase().indexOf(this.searchstring.toLowerCase());
			var ender_position = starter_position + this.searchstring.length;
			
			var starter = m.material_name.substring(0, starter_position);
			var middle = m.material_name.substring(starter_position, ender_position);
			var ender = m.material_name.substring(ender_position, m.material_name.length);		
			
			var bolded_name = starter + bolder_opener_html + middle + bolder_closer_html + ender;
			
			var material = new Element('a', {
				html: m.material_name,
				href: '#',
				'class': 'gp_lens_listing'
			}).inject(this.content);
			
			material.store('id', m.id);
			if(m.company != null) { material.store('company', m.company	); } else { material.store('company', '?'); }
			if(m.materialtype != null) { material.store('type', m.materialtype); } else { material.store('type', '?'); }
			if(m.dk != null) { material.store('dk', m.dk); } else { material.store('dk', '?'); }
			if(m.refractiveindex != null) { material.store('refractiveindex', m.refractiveindex); } else { material.store('refractiveindex', '?'); }
			if(m.wetangle != null) { material.store('wetangle', m.wetangle); } else { material.store('wetangle', '?'); }
			if(m.material_name != null) { material.store('name', m.material_name); } else { material.store('name', '?'); }
			if(m.specificgravity != null) { material.store('specificgravity', m.specificgravity); } else { material.store('specificgravity', '?'); }
			
			this.materials.include(material);
						
		//	i++;
			
		}.bind(this));
		
		this.materials.each(function(material) {
		
			material.addEvents({
			
				'mouseenter': function(e) {
				
					material.setStyle('background-color', this.options.gloss_bg);
					this.tooltip_exists = true;
					this.create_tooltip(material, e.page.x, e.page.y);
				
				}.bind(this),
			
				'mouseleave': function() {
				
					material.setStyle('background-color', this.options.def_color);
					this.destroy_tooltip();
				
				}.bind(this),
				
				'click': function(event) {
				
					event.stop();
				
				}.bind(this),
				
				'mousedown': function() {
				
					this.selected_lens = material.retrieve('id');
					this.fireEvent('materialclick');
				
				}.bind(this)
			
			});
		
		}.bind(this));	
	},
	
	create_tooltip: function(material, startx, starty) {
		
		this.tooltip = new Element('div', {
			html: '<div class="gp_tooltip_lens">' + material.retrieve('name') + '</div><div class="gp_tooltip_company"><div class="gp_tooltip_label">Company:</div> ' + material.retrieve('company') + '</div><div class="gp_tooltip_type"><div class="gp_tooltip_label">Type:</div> ' + material.retrieve('type') + '</div><div class="gp_tooltip_subtype"><div class="gp_tooltip_label">Dk:</div> ' + material.retrieve('dk') + '</div><div class="gp_tooltip_subtype"><div class="gp_tooltip_label">Wet Angle:</div> ' + material.retrieve('wetangle') + '</div><div class="gp_tooltip_subtype"><div class="gp_tooltip_label">Specific Gravity:</div> ' + material.retrieve('specificgravity') + '</div><div class="gp_tooltip_subtype"><div class="gp_tooltip_label">Refractive Index:</div> ' + material.retrieve('refractiveindex') + '</div>',
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
			src: 'components/com_gpmaterial/img/loader.gif',
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