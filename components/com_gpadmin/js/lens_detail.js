var Details = new Class({

	Implements: [Options, Events],
	
	options: {
		gray_bg: '#e7e7e7',
		gloss_bg: '#9fc9e4',
		select_bg: '#2687ae',
		text_color: '#363636',
		def_color: '#ffffff',
		load_cover_opacity: 0.7,
		loader_size: 48,
		closer_id: 'gp_details_close',
		details_container_id: 'gp_lens_details',
		tooltip_offset_x: 30,
		tooltip_offset_y: 0
	},
	
	initialize: function(container, joomla_shell, left_box, right_box, options) {
	
		this.setOptions(options);
		this.container = document.id(container); // initializes with the id of the main table
		this.joomla_shell = document.id(joomla_shell);
		this.left_box = document.id(left_box);
		this.right_box = document.id(right_box);
		
		this.state = 'nodetails'; // nodetails, lensdetails, materialdetails
		
		this.is_company_tooltip = false;
		this.is_materials_tooltip = false;
		this.is_material_lens_tooltip = false;
		
		this.lens_details = new Element('div', {
			id: 'gp_lens_details_container'
		});
		
		this.material_details = new Element('div', {
			id: 'gp_material_details_container'
		});
		
		this.request_lens_details = new Request.HTML({
			url: 'index.php',
			method: 'get',
			link: 'cancel',
			onRequest: function() {
				if(this.state == 'nodetails') {
					this.start_loader();
				}
				else if(this.state == 'materialdetails') {
					this.start_details_loader();
				}
			}.bind(this),
			onFailure: function() {
				// handle?
			}.bind(this),
			onSuccess: function() {
				this.end_loader();
				this.show_and_gloss_lens_details();
			}.bind(this),
			append: this.lens_details
		});
		
		this.request_material_details = new Request.HTML({
			url: 'index.php',
			method: 'get',
			link: 'cancel',
			onRequest: function() {
				this.start_details_loader();
			}.bind(this),
			onFailure: function() {
				// handle?
			}.bind(this),
			onSuccess: function() {
				this.end_loader();
				this.show_and_gloss_material_details();
			}.bind(this),
			append: this.material_details
		});
	
	},
	
	load_lens_details: function(id) {
	
	//	console.log(id);
	
		this.request_lens_details.send({
			data: {
				'option': 'com_gplens',
				'task': 'getLensDetails',
				'id': id
			}
		});
	
	},
	
	load_material_details: function(id) {
	
		this.request_material_details.send({
			data: {
				'option': 'com_gplens',
				'task': 'getMaterialDetails',
				'id': id
			}
		});
	
	},
	
	show_and_gloss_lens_details: function(html) {
	
		if(this.state == 'materialdetails') {
			this.close_material_details();
		}
	
		this.state = 'lensdetails';
		
		this.container.setStyle('visibility', 'hidden');
		var container_coords = this.container.getCoordinates();
		this.lens_details.inject(document.body).setStyles({
			'position': 'absolute',
			'top': container_coords.top,
			'left': container_coords.left
		});
		
		var lens_json = JSON.decode(document.id('gp_lens_json').get('html'));
		var materials_json = JSON.decode(document.id('gp_materials_json').get('html'));
		
	//	console.log(lens_json);
	//	console.log(materials_json);
		
		var company_question_mark = document.id('gp_lens_question_mark').getElement('a');
		var materials_question_mark = document.id('gp_materials_question_mark').getElement('a');
		
		var company_question_mark_coords = company_question_mark.getCoordinates();
		var materials_question_mark_coords = materials_question_mark.getCoordinates();
		
		var company_tooltip_content = '';
		if(lens_json.company != '' && lens_json.company != undefined) { company_tooltip_content = company_tooltip_content + '<div class="gp_tooltip_lens">' + lens_json.company + '</div>'; }
		if(lens_json.c_phone != '' && lens_json.c_phone != undefined) { company_tooltip_content = company_tooltip_content + '<div class="gp_tooltip_type"><div class="gp_tooltip_label">Phone:</div> ' + lens_json.c_phone + '</div>'; }
		if(lens_json.c_url != '' && lens_json.c_url != undefined) { company_tooltip_content = company_tooltip_content + '<div class="gp_tooltip_type"><div class="gp_tooltip_label">Website:</div> <a href="' + lens_json.c_url + '">' + lens_json.c_url + '</a></div>'; }
		if(lens_json.c_address != '' && lens_json.c_address != undefined) { company_tooltip_content = company_tooltip_content + '<div class="gp_tooltip_type"><div class="gp_tooltip_label">Address:</div> ' + lens_json.c_address + '</div>'; }
		
	//	console.log(lens_json);
	//	console.log(materials_json);
		
		document.id('gp_details_close').addEvent('mousedown', function() {
			this.close_lens_details();
		}.bind(this));
		
		company_question_mark.addEvents({
		
			'mousedown': function(e) {
			
				if(company_tooltip_content != '') {
			
					if(this.is_company_tooltip == false) {
				
						this.company_tooltip = new Element('div', {
							html: company_tooltip_content,
							'class': 'gp_tooltip_eyedock',
							styles: {
								top: company_question_mark_coords.top + this.options.tooltip_offset_y,
								left: company_question_mark_coords.left + this.options.tooltip_offset_x
							}
						}).inject(document.body);

						this.is_company_tooltip = true;

					}
					
					else {
					
						this.company_tooltip.destroy();
						this.is_company_tooltip = false;
					
					}
					
				}
			
			}.bind(this),
		
			'click': function(event) {
			
				event.stop();
			
			}.bind(this)
		
		});
		
		if(materials_json != null) {
		
			materials_question_mark.addEvents({
			
				'mousedown': function(e) {
				
					if(this.is_materials_tooltip == false) {
				
						this.materials_tooltip = new Element('div', {
							html: '',
							'class': 'gp_tooltip_eyedock',
							styles: {
								top: materials_question_mark_coords.top + this.options.tooltip_offset_y,
								left: materials_question_mark_coords.left + this.options.tooltip_offset_x
							}
						}).inject(document.body);

						var material_links = [];
						
						materials_json.each(function(m) {
						
							var matlink = new Element('a', {
								'class': 'spaced_tooltip_type',
								html: m.material,
								href: '#',
							}).inject(this.materials_tooltip).store('id', m.id);
							
							material_links.include(matlink);
							
						}.bind(this));
						
						material_links.each(function(m) {
							
							m.addEvents({
							
								'mousedown': function() {
								
									var id = m.retrieve('id');
									if(this.is_company_tooltip) { this.company_tooltip.destroy(); this.is_company_tooltip = false; }
									if(this.is_materials_tooltip) { this.materials_tooltip.destroy(); this.is_materials_tooltip = false; }
									this.load_material_details(id);
								
								}.bind(this),
								
								'click': function(e) {
								
									e.stop();
								
								}
							
							});
							
						}.bind(this));
						
						this.is_materials_tooltip = true;

					}
					
					else {
					
						this.materials_tooltip.destroy();
						this.is_materials_tooltip = false;
					
					}
				
				}.bind(this),
			
				'click': function(event) {
				
					event.stop();
				
				}.bind(this)
			
			});
		
		}
		
		else {
		
			materials_question_mark.addEvents({
			
				'mousedown': function(e) {
				
					if(this.is_materials_tooltip == false) {
				
						this.materials_tooltip = new Element('div', {
							html: '',
							'class': 'gp_tooltip_eyedock',
							styles: {
								top: materials_question_mark_coords.top + this.options.tooltip_offset_y,
								left: materials_question_mark_coords.left + this.options.tooltip_offset_x
							}
						}).inject(document.body);
						
						var matlink = new Element('div', {
							'class': 'spaced_tooltip_type',
							html: 'This lens can be made with any material',
							href: '#',
						}).inject(this.materials_tooltip);
						
						this.is_materials_tooltip = true;

					}
					
					else {
					
						this.materials_tooltip.destroy();
						this.is_materials_tooltip = false;
					
					}
				
				}.bind(this),
			
				'click': function(event) {
				
					event.stop();
				
				}.bind(this)
			
			});			
		
		}
	
	},
	
	close_lens_details: function() {
	
		this.state = 'nodetails';

		if(this.is_company_tooltip) { this.company_tooltip.destroy(); this.is_company_tooltip = false; }
		if(this.is_materials_tooltip) { this.materials_tooltip.destroy(); this.is_materials_tooltip = false; }
		
		this.lens_details.destroy();
		this.container.setStyle('visibility', 'visible');
	
	},
	
	show_and_gloss_material_details: function() {
	
		this.state = 'materialdetails';
		
		this.container.setStyle('visibility', 'hidden');
		this.lens_details.destroy(); // ??? layer them indefinitely?
		var container_coords = this.container.getCoordinates();
		this.material_details.inject(document.body).setStyles({
			'position': 'absolute',
			'top': container_coords.top,
			'left': container_coords.left
		});
		
		var material_json = JSON.decode(document.id('gp_material_json').get('html'));
		var lenses_json = JSON.decode(document.id('gp_lenses_json').get('html'));
		var company_question_mark = document.id('gp_lens_question_mark').getElement('a');
		var company_question_mark_coords = company_question_mark.getCoordinates();
				
		var company_tooltip_content = '<div class="gp_tooltip_lens">' + material_json.company + '</div>';
		if(material_json.c_phone != '') { company_tooltip_content = company_tooltip_content + '<div class="gp_tooltip_type"><div class="gp_tooltip_label">Phone:</div> ' + material_json.c_phone + '</div>'; }
		if(material_json.c_url != '') { company_tooltip_content = company_tooltip_content + '<div class="gp_tooltip_type"><div class="gp_tooltip_label">Website:</div> <a href="' + material_json.c_url + '">' + material_json.c_url + '</a></div>'; }
		
		document.id('gp_details_close').addEvent('mousedown', function() {
			this.close_material_details();
		}.bind(this));		

		company_question_mark.addEvents({
		
			'mousedown': function(e) {
			
				if(this.is_company_tooltip == false) {
			
					this.company_tooltip = new Element('div', {
						html: company_tooltip_content,
						'class': 'gp_tooltip_eyedock',
						styles: {
							top: company_question_mark_coords.top + this.options.tooltip_offset_y,
							left: company_question_mark_coords.left + this.options.tooltip_offset_x
						}
					}).inject(document.body);

					this.is_company_tooltip = true;

				}
				
				else {
				
					this.company_tooltip.destroy();
					this.is_company_tooltip = false;
				
				}
			
			}.bind(this),
		
			'click': function(event) {
			
				event.stop();
			
			}.bind(this)
		
		});
		
		this.populate_lens_list_in_materials_details(lenses_json);
		
	},
	
	populate_lens_list_in_materials_details: function(lenses_json) {
	
		var content = document.id('gp_left_box_details_content');
		
		var lenses = [];
	
		lenses_json.each(function(l) {
			
			var lens = new Element('a', {
				html: l.name,
				href: '#',
				'class': 'gp_lens_listing'
			}).inject(content);
			
			lens.store('id', l.tid);
			lens.store('lens_name', l.name);
			lens.store('company', l.company);
			lens.store('type', l.type);
			lens.store('subtype', l.subtype);
			
			lenses.include(lens);
						
		//	i++;
			
		}.bind(this));
		
		lenses.each(function(lens) {
		
			lens.addEvents({
			
				'mouseenter': function(e) {
				
					lens.setStyle('background-color', this.options.gloss_bg);
					
					this.material_lens_tooltip = new Element('div', {
						html: '<div class="gp_tooltip_lens">' + lens.retrieve('lens_name') + '</div><div class="gp_tooltip_company"><div class="gp_tooltip_label">Company:</div> ' + lens.retrieve('company') + '</div><div class="gp_tooltip_type"><div class="gp_tooltip_label">Type:</div> ' + lens.retrieve('type') + '</div><div class="gp_tooltip_subtype"><div class="gp_tooltip_label">Subtype:</div> ' + lens.retrieve('subtype') + '</div>',
						'class': 'gp_tooltip_eyedock',
						styles: {
							top: e.page.y + this.options.tooltip_offset_y,
							left: e.page.x + this.options.tooltip_offset_x
						}
					}).inject(document.body);
					
					this.is_material_lens_tooltip = true;
					
					window.addEvent('mousemove', function(e) {
						
						if(this.is_material_lens_tooltip == true) {
						
							this.material_lens_tooltip.setStyles({
								top: e.page.y + this.options.tooltip_offset_y,
								left: e.page.x + this.options.tooltip_offset_x
							});
							
						}
						
					}.bind(this));
											
				}.bind(this),
			
				'mouseleave': function() {
				
					lens.setStyle('background-color', this.options.def_color);
					this.material_lens_tooltip.destroy();
					window.removeEvents('mousemove');
				
				}.bind(this),
				
				'click': function(event) {
				
					event.stop();
				
				}.bind(this),
				
				'mousedown': function() {
					
					if(this.is_material_lens_tooltip == true) {
						this.material_lens_tooltip.destroy();
					}
					var selected_lens = lens.retrieve('id');
					this.load_lens_details(selected_lens);
				
				}.bind(this)
			
			});
		
		}.bind(this));
		
	
	},
	
	close_material_details: function() {
	
		this.state = 'nodetails';

		if(this.is_company_tooltip) { this.company_tooltip.destroy(); this.is_company_tooltip = false; }
		if(this.is_materials_tooltip) { this.materials_tooltip.destroy(); this.is_materials_tooltip = false; }
		
		this.material_details.destroy();
		this.container.setStyle('visibility', 'visible');
		
	},
	
	start_loader: function() {
	
		var right_content_position = this.right_box.getCoordinates();
		var right_content_size = this.right_box.getSize();
		
		this.right_cover = new Element('div', {
			html: '',
			'class': 'load_cover',
			styles: {
				'position': 'absolute',
				'top': right_content_position.top,
				'left': right_content_position.left,
				'height': right_content_size.y,
				'width': right_content_size.x,
				'background-color': '#ffffff',
				'opacity': this.options.load_cover_opacity,
				'text-align': 'center'
			}
		}).inject(document.body);
		
		var loader = new Element('img', {
			src: 'components/com_gplens/img/loader.gif',
			styles: {
				'position': 'relative',
				'padding-top': ((right_content_size.y-this.options.loader_size)/2)-20
			}
		}).inject(this.right_cover);	
		
	},
	
	start_details_loader: function() {
	
		var right_box = document.id('gp_right_box_lens');
		var right_content_position = right_box.getCoordinates();
		var right_content_size = right_box.getSize();
		
		this.right_cover = new Element('div', {
			html: '',
			'class': 'load_cover',
			styles: {
				'position': 'absolute',
				'top': right_content_position.top,
				'left': right_content_position.left,
				'height': right_content_size.y,
				'width': right_content_size.x,
				'background-color': '#ffffff',
				'opacity': this.options.load_cover_opacity,
				'text-align': 'center'
			}
		}).inject(document.body);
		
		var loader = new Element('img', {
			src: 'components/com_gplens/img/loader.gif',
			styles: {
				'position': 'relative',
				'padding-top': ((right_content_size.y-this.options.loader_size)/2)-20
			}
		}).inject(this.right_cover);
	
	},
	
	end_loader: function() {
	
		this.right_cover.destroy();
	
	}
	
});