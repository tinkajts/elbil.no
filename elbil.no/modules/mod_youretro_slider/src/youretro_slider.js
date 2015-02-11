/**
 * YouScene Slider
 * @version		1.0.0
 * @MooTools version 1.1
 * @Copyright Youjoomla.com
 * @author		Constantin Boiangiu <info [at] constantinb.com>
 */

var YouretroSlider = new Class({
	Implements: [Options],
	options: {
            navigation:{
				container: null,
				elements:null,
				outer: null,
				visibleItems: 0
			},
			slides:{
				container:null,
				elements:null,
				infoContainer:null,
				infoContainerPosition:null,
				startFx:{'opacity':1},
				endFx:{'opacity':0},
				fadeDurr:null
			},
			startElem:null,
			autoSlide: null
		},

	initialize: function(options) {
		this.setOptions(options);		
		/* navigation links */
		this.navElements = $(this.options.navigation.container).getElements(this.options.navigation.elements);
		/* slides */
		this.slides = $(this.options.slides.container).getElements(this.options.slides.elements);
		/* navigation scroll effect */
		this.navScroll = new Fx.Scroll(this.options.navigation.outer, {
			wait: false,
			duration: 800,
			transition: Fx.Transitions.Quad.easeInOut
		});
		this.correction = Math.round(this.options.navigation.visibleItems/2.00001);
		this.start();
	},
	
	start: function(){
		/* starting element on page load can be set by user, defaults to first */
		this.currentElem = this.options.startElem ? this.options.startElem-1 : 0; 
		/*
			slides
		*/
		this.slides.each(function(slide, i){
			
			slide.setStyles({
				'display': 'block',
				'position':'absolute',
				'left':0,
				'top':0,
				'visibility':'visible',
				'opacity': (i==this.currentElem ? 1 : 0)
			});	
			
			if( i!==this.currentElem ){
				slide.setStyles(this.options.slides.endFx);
			}
			
			//this.slides[i]['fx'] = new Fx.Morph(slide, {duration: 700, transition: Fx.Transitions.linear, wait:false});
			
			this.slides[i]['fx'] = new Fx.Morph(slide, {link:'cancel', duration: this.options.slides.fadeDurr, transition: Fx.Transitions.linear,wait:false});
			
			
			/* if info container set, add effects to it */
			if( this.options.slides.infoContainer && this.options.slides.infoContainerPosition ){
				var info = slide.getElement(this.options.slides.infoContainer);
				var infoFx = new Fx.Morph(info, this.options.slides.infoContainerPosition, {duration:this.options.slides.fadeDurr, wait:false});
	
			}
			/* if slider on auto, stop it on slide mouse over and start it on mouse out */
			if( this.options.autoSlide ){
				slide.addEvent('mouseover', function(){
					clearTimeout(this.period);
				}.bind(this));
				slide.addEvent('mouseout', function(){
					this.period = this.rotate.bind(this).periodical(this.options.autoSlide);
				}.bind(this));
			}			
			
		}.bind(this));
		/*
			navigation
		*/
		this.navElements.each(function(elem, i){
			/* set select on the element to start with */
			if( i == this.currentElem ){
				this.navScroll.toElement(elem);
				elem.addClass(this.options.navigation.selectedClass);
			}
			/* navigation click events */
			elem.addEvent('click', function(event){
				event.stop();
				this.changeSlide(i);
				if( this.options.autoSlide ){
					clearTimeout(this.period);
					this.period = this.rotate.bind(this).periodical(this.options.autoSlide);
				}
			}.bind(this));
			
		}.bind(this));
		
		if( this.options.autoSlide ){
			this.period = this.rotate.bind(this).periodical(this.options.autoSlide);
		}
		
		$(this.options.navigation.container).addEvent('mousewheel', function(event){
			event.stop();
				var dir = event.wheel > 0 ? 1 : -1;
				var el = this.currentElem - dir;
			//var el = this.currentElem-event.wheel;
			if( event.wheel > 0 && el < 0 ) el = this.navElements.length-1;
			if( event.wheel < 0 && el > this.navElements.length-1 ) el = 0;
			//clearTimeout(this.period);
				if( this.options.autoslide == 0 ){
					clearTimeout(this.period);
					this.resetAutoslide();
				}
			//this.period = this.rotate.bind(this).periodical(this.options.autoSlide);
			this.changeSlide(el);					
		}.bind(this));	
		
	},
	
	changeSlide: function(key){
		
		/* if same element clicked, return */
		if( key == this.currentElem ) return;
		/* change slides */
		this.slides[this.currentElem]['fx'].start(this.options.slides.endFx);
		this.slides[key]['fx'].start(this.options.slides.startFx);
		/* go to nav element */
		this.navElements[this.currentElem].removeClass(this.options.navigation.selectedClass);
		this.navElements[key].addClass(this.options.navigation.selectedClass);
		/* slide to element */
		var navTo = key-this.correction < 0 ? 0 : key-this.correction;		
		this.navScroll.toElement(this.navElements[navTo]);
		/* store the current element clicked */
		this.currentElem = key;
		
	},
	
	rotate: function(){
		var key = this.currentElem+1 < this.navElements.length ? this.currentElem+1 : 0;		
		this.changeSlide(key);
	}
});
