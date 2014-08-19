var SearchBox = new Class({

	Implements: [Options, Events],
	
	options: {
		transition: 'quad:out',
		fps: 50,
		open_width: 315,
		open_color: '#e7c348',
		duration: 300,
		defaults: {
			width: 150,
			color: '#a5a5a5',
			border_color: '#2687ae',
			value: 'Search everything...'
		}
	},
	
	initialize: function(container, options) {
	
		this.setOptions(options);
		this.container = document.id(container);
		this.input = this.container.getElement('input');
		
		this.state = 'closed'; // open or closed
		
		this.morph = new Fx.Morph(this.input, {
			duration: this.options.duration,
			transition: this.options.transition,
			fps: this.options.fps,
			link: 'cancel'
		});
		
		this.build_events();

		this.quick_search = function(q){
			this.input.fireEvent('focus');
			this.input.value = q;
			this.input.fireEvent('keyup');
		}

	},
	
	build_events: function() {
	
		this.input.addEvents({
		
			'focus': function() {
			
				if(this.state == 'closed') {
			
					this.morph.start({
						'border-top-color': this.options.open_color,
						'border-left-color': this.options.open_color,
						'border-right-color': this.options.open_color,
						'border-bottom-color': this.options.open_color,
						'width': this.options.open_width,
						'color': '#000000'
					});
					
					this.input.set('value', '');
				
					this.state = 'open';
				
				}
			
			}.bind(this),
			
			'blur': function() {
			
				if(this.input.get('value') == '' && this.state == 'open') {
			
					this.morph.start({
						'border-color': this.options.defaults.border_color,
						'width': this.options.defaults.width,
						'color': '#ffffff'
					}).chain(function() {
						this.input.set('value', this.options.defaults.value);
						this.morph.start({ 'color': this.options.defaults.color });
					}.bind(this));				
			
					this.state = 'closed';
			
				}
			
			}.bind(this),
			
			'keyup': function() {
				
				this.fireEvent('keyup', this.input.get('value').trim());
				
			}.bind(this)
		
		});
	
	},
	
	start_loader: function() {
	
		var input_position = this.input.getCoordinates();
		
		this.loader = new Element('img', {
			src: '/components/com_eyedocksearch/img/loader_small.gif',
			styles: {
				'position': 'absolute',
				'top': input_position.top + 6,
				'left': input_position.right - 22
			}			
		}).inject(document.body);
	
	},
	
	end_loader: function() {
	
		this.loader.destroy();
	
	}

});
