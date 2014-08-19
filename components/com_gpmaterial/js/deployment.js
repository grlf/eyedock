window.addEvent('domready', function() {

	this.search_box = new SearchBox('gp_search_box', {
	
		onKeyup: function(value) {
		
			this.right_box.load_materials_by_searchstring(value);
			this.left_box.deselect_all_companies();
		
		}.bind(this)
	
	});
	
	this.tabs = new Tabs('gp_tab_company', 'gp_tab_parameter', {
	
		onNewtab: function(newstate) {
		
			if(newstate == 'company' && this.tabs.state == 'parameter') {
			
				this.left_box.load_company_search();
				this.tabs.state = 'company';
				this.right_box.clear_results();
			
			}
			
			else if(newstate == 'parameter' && this.tabs.state == 'company') {

				this.left_box.load_parameter_search();
				this.tabs.state = 'parameter';		
				this.right_box.clear_results();		
			
			}
		
		}.bind(this)
	
	});
	
	this.left_box = new LeftBox('gp_left_box', {
	
		onMaterialselection: function() {
		
			if(this.left_box.selected_companies.length > 0) {
				this.right_box.load_materials_by_company_ids(this.left_box.selected_companies);
			}
			
			else {
				this.right_box.clear_results();
			}
		
		}.bind(this),
		
		onSliderchange: function() {
			
			var data = {
				dk: {
					val: this.left_box.dk_value,
					gl: this.left_box.dk_greater_less.status
				},
				wetting: {
					val: this.left_box.wetting_value,
					gl: this.left_box.wettingangle_greater_less.status
				},
				refractive: {
					val: this.left_box.refractive_value,
					gl: this.left_box.refractiveindex_greater_less.status
				},
				specific: {
					val: this.left_box.specific_value,
					gl: this.left_box.specificgravity_greater_less.status
				}
			}
			
		//	console.log(data);
			
			this.right_box.load_materials_by_sliders(data);
		
		}.bind(this)
	
	});
	
	this.right_box = new RightBox('gp_right_box', {
	
		onMaterialclick: function() {
		
			this.details.load_material_details(this.right_box.selected_lens);
		
		}.bind(this)
	
	});
	
	this.details = new Details('gp_container', 'page', 'gp_left_box', 'gp_right_box');

	// IGNITIONS
	
	this.left_box.load_company_search();

	res = /[\?&]q=(.*)/.exec(window.location.href);
	if(res){
		res[1] = res[1].split('%20').join(' ');
		this.search_box.quick_search(res[1]);
	}

});
