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
		
		this.current_category_param = 'all';
		this.current_subcategory_param = 'all';
		this.current_material_param = 'all';
		this.subcategory_state = 'inactive'; // inactive or active
		
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
		
		this.request_parameters = new Request.JSON({
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
			onSuccess: function(parameters) {
				this.end_loader();
				this.populate_parameters(parameters);
			}.bind(this)
		});
	
	},
	
	load_company_search: function() {
	
		this.state = 'company';
	//	this.topper_area.set('html', '');
		
	/*	this.topper = new Element('span', {
			html: 'Companies'
		}).inject(this.topper_area);
	*/	
		this.request_companies.send({
			data: {
				'option': 'com_gplens',
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
					
						company.setStyles({
							'background-color': this.options.select_bg,
							'color': '#ffffff !important'
						});
							
						company.store('selection_status', true);
						this.selected_companies.include(company.retrieve('id'));
						this.selected_companies_dom.include(company);
					//	console.log(this.selected_companies);
						this.fireEvent('companyselection');
												
					}

					else if(company.retrieve('selection_status') == true) {
						
						company.setStyles({
							'background-color': this.options.def_color,
							'color': this.options.text_color + ' !important'
						});
							
						company.store('selection_status', false);
						this.selected_companies.erase(company.retrieve('id'));
						this.selected_companies_dom.erase(company);
					//	console.log(this.selected_companies);
						this.fireEvent('companyselection');					
						
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
				'color': this.options.text_color + ' !important'
			});
							
			company.store('selection_status', false);
			this.selected_companies.erase(company.retrieve('id'));
			this.fireEvent('companyselection');		
						
		}.bind(this));
		
		this.selected_companies = [];
		this.selected_companies_dom = [];
	
	},
	
	load_parameter_search: function() {
	
		this.state = 'parameter';
		
		this.request_parameters.send({
			data: {
				'option': 'com_gplens',
				'task': 'getParameters'
			}
		});
	
	},
	
	populate_parameters: function(params) {
	
		this.content.set('html', '');
		
		var category_options = [];
		var subcategory_options = [];
		var materials_options = [];
		
		this.design_category_options = new Element('select', {
			id: 'gp_category_select'
		}).inject(this.content);
		
			var design_category_topper = new Element('option', {
				html: 'All Design Categories'
			}).inject(this.design_category_options).store('id', 'all');
			
			category_options.include(design_category_topper);
			
			params.DesignCategory.each(function(cat) {
				
				var option = new Element('option', {
					html: cat.name
				}).inject(this.design_category_options).store('id', cat.tid.toInt());
				
				category_options.include(option);
				
			}.bind(this));
			
		this.subcategory_options = new Element('select', {
			id: 'gp_subcategory_select',
			disabled: 'disabled'
		}).inject(this.content);
		
			var subcategory_topper = new Element('option', {
				html: 'All Subcategories'
			}).inject(this.subcategory_options).store('id', 'all');	
			
			subcategory_options.include(materials_topper);
		
		this.materials_options = new Element('select', {
			id: 'gp_materials_select'
		}).inject(this.content);
		
			var materials_topper = new Element('option', {
				html: 'All Materials'
			}).inject(this.materials_options).store('id', 'all');
			
			materials_options.include(materials_topper);
			
			params.Materials.each(function(mat) {
				
				var option = new Element('option', {
					html: mat.name
				}).inject(this.materials_options).store('id', mat.tid.toInt());
				
				materials_options.include(option);
				
			}.bind(this));
			
	
	//	category_options.each(function(o) {
		
			this.design_category_options.addEvents({
			
				'change': function() {
						
				//	console.log('changed');
				
					this.current_category_param = this.design_category_options.getSelected().retrieve('id');
					this.current_subcategory_param = this.subcategory_options.getSelected().retrieve('id');
					this.current_material_param = this.materials_options.getSelected().retrieve('id');
					
					var matches = false;
					
					params.Subcategory.each(function(subcat) {
					
						if(subcat.designCategoryID == this.current_category_param) {
						
							var option = new Element('option', {
								html: subcat.name
							}).inject(this.subcategory_options).store('id', subcat.tid.toInt());
							
							matches = true;
							
						}
					
					}.bind(this));
					
					// activate subcategory dropdown
					if(matches == true && this.subcategory_state == 'inactive') {
					
						this.subcategory_options.setStyles({
							'color': '#363636',
							'font-weight': 'bold'
						}).removeProperty('disabled');
						
						this.subcategory_state = 'active';
						
					}
					
					// inactivate subcategory dropdown
					else if(matches == false && this.subcategory_state == 'active') {	
						
						this.subcategory_options.setStyles({
							'color': '#ababab',
							'font-weight': 'normal'
						}).setProperty('disabled', 'disabled');			
					
						this.subcategory_state = 'inactive';
						
						this.subcategory_options.getElements('option').each(function(e) { e.destroy(); });
					
						var subcategory_topper = new Element('option', {
							html: 'All Subcategories'
						}).inject(this.subcategory_options).store('id', 'all');	
					
					}
					
					this.fireEvent('paramaterselectionchange');
				
				}.bind(this)
			
			});
		
	//	}.bind(this));
		
	//	subcategory_options.each(function(o) {
		
			this.subcategory_options.addEvents({
			
				'change': function() {
				
					this.current_category_param = this.design_category_options.getSelected().retrieve('id');
					this.current_subcategory_param = this.subcategory_options.getSelected().retrieve('id');
					this.current_material_param = this.materials_options.getSelected().retrieve('id');
					
					this.fireEvent('paramaterselectionchange');
				
				}.bind(this)
			
			});
		
	//	}.bind(this));
		
	//	materials_options.each(function(o) {
		
			this.materials_options.addEvents({
			
				'change': function() {
				
					this.current_category_param = this.design_category_options.getSelected().retrieve('id');
					this.current_subcategory_param = this.subcategory_options.getSelected().retrieve('id');
					this.current_material_param = this.materials_options.getSelected().retrieve('id');
					
					this.fireEvent('paramaterselectionchange');
				
				}.bind(this)
			
			});
		
	//	}.bind(this));
	
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
	
	}
	
});
