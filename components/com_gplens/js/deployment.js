window.addEvent('domready', function() {

	if(!document.getElementById('gp_container')){
		return;
	}

	this.search_box = new SearchBox('gp_search_box', {
	
		onKeyup: function(value) {
		
			this.right_box.load_lenses_by_searchstring(value);
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
	
		onCompanyselection: function() {
		
			if(this.left_box.selected_companies.length > 0) {
				this.right_box.load_lenses_by_company_ids(this.left_box.selected_companies);
			}
			
			else {
				this.right_box.clear_results();
			}
		
		}.bind(this),
		
		onParamaterselectionchange: function() {
		
			var params = {
				'category': this.left_box.current_category_param, 
				'subcategory': this.left_box.current_subcategory_param, 
				'material': this.left_box.current_material_param
			};
			
			this.right_box.load_lenses_by_parameters({ 'category': this.left_box.current_category_param[0], 'subcategory': this.left_box.current_subcategory_param[0], 'material': this.left_box.current_material_param[0] });
		
		}.bind(this)
	
	});
	
	this.right_box = new RightBox('gp_right_box', {
	
		onLensclick: function() {
		
			this.details.load_lens_details(this.right_box.selected_lens);
		
		}.bind(this)
	
	});
	
	this.details = new Details('gp_container', 'page', 'gp_left_box', 'gp_right_box');

	// IGNITIONS

	this.left_box.load_company_search();

	res = /[\?&]q=(.*)/.exec(window.location.href);
	if(res){
		res[1] = res[1].split('%20').join(' ');
		res[1] = res[1].split('%2C').join(' ');
		this.search_box.quick_search(res[1]);
	}

});
