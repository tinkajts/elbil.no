/**
 * Youjoomla Accordion News Slider
 * @version		1.0.0
 * @MooTools version 1.3+
 * Copyright Youjoomla.com
 * @author	Constantin Boiangiu
 */
var FancySlider = new Class({
	Implements: [Options],
	options: {
            container: null,
            elements: null,
            closedWidth: null,
            openedWidth: null,
            autoSlide: null,
            fxOpenDuration: null,
            infoItems: null,
            hideTo: -200
		},
	initialize: function(options) {
		this.setOptions(options);
		this.start()
	},
    start: function () {
        this.slides = $(this.options.container).getElements(this.options.elements);
        this.current = 0;
        if (this.options.autoSlide) {
            this.options.autoSlide += this.options.fxOpenDuration || 500
        }
        this.slides.each(function (c, a) {
            this.slides[a]["fx"] = new Fx.Morph(c, {
                wait: false,
                duration: this.options.fxOpenDuration || 500,
				link:'cancel'
            });
            var b = c.getElement(this.options.infoItems);
            this.slides[a]["infoFx"] = new Fx.Morph(b, {
                wait: false,
                duration: 300,
				link:'cancel'
            });
            if (c.hasClass("opened")) {
                this.current = a;
                this.slides[a]["infoFx"].start({
                    opacity: 1,
					zIndex:100
                })
            } else {
                this.slides[a]["infoFx"].set({
                    opacity:0,
					zIndex:-100
                })
            }
            var d = 0;
            this.slides.each(function (f, e) {
                if (e < a) {
                    d += this.slides[e].hasClass("opened") ? this.options.openedWidth : this.options.closedWidth
                }
            }.bind(this));
            this.slides[a]["left"] = d;
            c.setStyles({
                position: "absolute",
                display: "block",
                top: 0,
                left: d
            });
            c.addEvent("click", function (e) {
                if (a == this.current) {
                    return
                }
                if ((this.period !== undefined && this.period !== null) && this.options.autoSlide) {
                    clearTimeout(this.period)
                }
                this.changeSlide(a)
            }.bind(this));
            c.addEvent("mouseover", function () {
                if (a == this.current) {
                    if (this.options.autoSlide) {
                        clearTimeout(this.period)
                    }
                    this.slides[a]["infoFx"].start({
                        bottom: 0,
                        opacity: 1,
						zIndex:100
                    })
                }
            }.bind(this));
            c.addEvent("mouseout", function () {
                if (a == this.current) {
                    if (this.options.autoSlide) {
                        this.period = this.autoSlide.periodical(this.options.autoSlide, this)
                    }
                    this.slides[a]["infoFx"].start({
                        bottom: this.options.hideTo,
                        opacity: 1,
						zIndex:100
                    })
                }
            }.bind(this))
        }.bind(this));
        if (this.options.autoSlide) {
            this.period = this.autoSlide.periodical(this.options.autoSlide, this)
        }
    },
    autoSlide: function () {
        var a = this.current + 1 >= this.slides.length ? 0 : this.current + 1;
        this.changeSlide(a)
    },
    changeSlide: function (b) {
	
		
        this.slides[b]["fx"].start({
            width: this.options.openedWidth,
            left: this.slides[b]["left"] - (b == 0 ? 0 : this.options.openedWidth - this.options.closedWidth)
        });
		
		
		
        this.slides[b]["infoFx"].start({
            opacity: 1,
			zIndex:100
        });
        this.slides[this.current]["infoFx"].start({
            opacity: 0,
			zIndex:-100
        });
		
		this.slides[b].addClass('opened');
		this.slides[this.current].removeClass('opened');
		
        if (this.current < b) {
            for (var a = this.current; a < b; a++) {
                this.slides[a]["fx"].start({
                    width: this.options.closedWidth,
                    left: a * this.options.closedWidth
                })
            }
        } else {
            if (this.current > b) {
                var c = this.options.openedWidth;
                for (var a = b + 1; a < this.slides.length; a++) {
                    this.slides[a]["fx"].start({
                        width: this.options.closedWidth,
                        left: (a - 1) * this.options.closedWidth + this.options.openedWidth
                    })
                }
            }
        }
        this.current = b
    }
});