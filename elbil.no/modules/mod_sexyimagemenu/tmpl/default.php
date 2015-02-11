<?php
/**
* @Copyright Copyright (C) 2010- ... Vijay Padsumbiya
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * mod_sexyimagemenu is Commercial software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
**/
?>
<link rel="stylesheet" href="<?php echo JURI::root(); ?>modules/mod_sexyimagemenu/css/style.css" type="text/css" media="screen"/>
<?php if($media_option == 0 ) { $path = "modules/mod_sexyimagemenu/images/";   } else {  $path = "images/sexyimagemenu/";  } 
$menu_original_height = trim($menu_height, 'px');
?>	   
<style>
	.jsexyimgMenu {
	position: relative;
	width:<?php echo $menu_width;?>;
	height:<?php echo $menu_original_height;?>px;
	overflow: hidden;
	}
	
	.jsexyimgMenu ul {
	list-style: none;
	margin-left:<?php echo $left_margin;?>;
	margin-bottom:<?php echo $bottom_margin;?>;
	margin-top:<?php echo $top_margin;?>;
	
	width:1370px;
	display: block;
	overflow: hidden;
	}
	
	ul.accordion li{
   	float:left;
    width:10%;
    height:<?php echo $menu_original_height-20;?>px;
  
	}
	
	.main_heading{
	font-family:<?php echo $font_family;?>;
	 background-color:<?php echo $main_title_backcolor;?>;
	 font-size:<?php echo $main_title_size;?>;
     color:<?php echo $main_title_color;?>;
	}
	.desc_heading{
	font-family:<?php echo $font_family;?>;
	  font-size:<?php echo $sub_title_size;?>;
    	color:<?php echo $sub_title_color;?>;
	
	}
	
	.desc {
	 font-family:<?php echo $font_family;?>;
    font-size: <?php echo $sub_desc_size;?>;
   
	color:<?php echo $sub_desc_color;?>;
	}
	ul.accordion li div a{
	font-family:<?php echo $font_family;?>;
	  font-size:<?php echo $sub_link_size;?>;
    text-decoration:none;
    color:<?php echo $sub_link_color;?>;
	}
	ul.accordion li div.bgGradient {
    background:transparent url(<?php echo JURI::root();?>modules/mod_sexyimagemenu/images/<?php echo $accordin_background;?>) repeat-x top left;
    display: block;
    bottom: -340px;
    height: <?php echo $accordin_height;?>;
	}
	
	ul.accordion li.bg1{
	background-image:url(<?php echo JURI::root()."".$path."".$image_1;?>); 
	}
	ul.accordion li.bg2{
	background-image:url(<?php echo JURI::root()."".$path."".$image_2;?>);
	}
	ul.accordion li.bg3{
	background-image:url(<?php echo JURI::root()."".$path."".$image_3;?>);
	}
	ul.accordion li.bg4{
	background-image:url(<?php echo JURI::root()."".$path."".$image_4;?>);
	}
	ul.accordion li.bg5{
	background-image:url(<?php echo JURI::root()."".$path."".$image_5;?>);
	}
	ul.accordion li.bg6{
	background-image:url(<?php echo JURI::root()."".$path."".$image_6;?>);
	}
	ul.accordion li.bg7{
	background-image:url(<?php echo JURI::root()."".$path."".$image_7;?>);
	}
	ul.accordion li.bg8{
	background-image:url(<?php echo JURI::root()."".$path."".$image_8;?>);
	}
 </style>
    
        
<div class="jsexyimgMenu">
    <ul class="accordion" id="accordion">
        <li class="bg1" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_1;?>'); <?php } else { ?> window.location='<?php echo $link_1;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_1_title;?></span>
        <div>
        <span class="desc_heading"><?php echo $link_1_title;?></span>
        <br />
        <span class="desc"><?php echo $link_1_desc;?></span>
        <a href="<?php echo $link_1;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
    
        <li class="bg2" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_2;?>'); <?php } else { ?> window.location='<?php echo $link_2;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_2_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_2_title;?></span>
         <br />
        <span class="desc"><?php echo $link_2_desc;?></span>
         <a href="<?php echo $link_2;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
        
		
			<?php if($image_number >= 3) { ?>
        <li class="bg3" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_3;?>'); <?php } else { ?> window.location='<?php echo $link_3;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_3_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_3_title;?></span>
         <br />
        <span class="desc"><?php echo $link_3_desc;?></span>
         <a href="<?php echo $link_3;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
        
		
		<?php } if($image_number >= 4) { ?>
		
        <li class="bg4" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_4;?>'); <?php } else { ?> window.location='<?php echo $link_4;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_4_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_4_title;?></span>
         <br />
        <span class="desc"><?php echo $link_4_desc;?></span>
         <a href="<?php echo $link_4;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
        
		<?php } if($image_number >= 5) { ?>
        <li class="bg5" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_5;?>'); <?php } else { ?> window.location='<?php echo $link_5;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_5_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_5_title;?></span>
         <br />
        <span class="desc"><?php echo $link_5_desc;?></span>
         <a href="<?php echo $link_5;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
		
		<?php } if($image_number >= 6) { ?>
		 <li class="bg6" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_6;?>'); <?php } else { ?> window.location='<?php echo $link_6;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_6_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_6_title;?></span>
         <br />
        <span class="desc"><?php echo $link_6_desc;?></span>
         <a href="<?php echo $link_6;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
		
		<?php } if($image_number >= 7) { ?>
		 <li class="bg7" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_7;?>'); <?php } else { ?> window.location='<?php echo $link_7;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_7_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_7_title;?></span>
         <br />
        <span class="desc"><?php echo $link_7_desc;?></span>
         <a href="<?php echo $link_7;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
	
		
		<?php } if($image_number >= 8) { ?>
		 <li class="bg8" onclick="<?php if($link_open_window == 1) { ?> window.open('<?php echo $link_7;?>'); <?php } else { ?> window.location='<?php echo $link_8;?>' <?php } ?>">
        <span class="main_heading"><?php echo $link_8_title;?></span>
        <div>
         <span class="desc_heading"><?php echo $link_8_title;?></span>
         <br />
        <span class="desc"><?php echo $link_8_desc;?></span>
         <a href="<?php echo $link_8;?>" <?php if($link_open_window == 1) { ?> target="_blank" <?php } ?>><?php echo $link_text;?> &rarr;</a>
        </div>
        </li>
		<?php } ?>
    </ul>
</div>
<div style="clear:both;"/></div>

<!-- The JavaScript -->
<?php if($jquery_load == 1) { ?>    
<script type="text/javascript" src="<?php echo JURI::root(); ?>modules/mod_sexyimagemenu/js/jquery.min.js"></script>
<?php } ?>
<script type="text/javascript">

(function($) {
	
	var __eAccordionRunTimes = 0; 
	
	$.eAccordion = function(el, options) {
		
		// To avoid scope issues, use 'base' instead of 'this'
		// to reference this class from internal events and functions.
		var base = this;
	  
		// Keeps track of the index of the current instance
		__eAccordionRunTimes++;
		base.runTimes = __eAccordionRunTimes;
			
		// Gives Access to jQuery element
		base.$el = $(el);
		
		// Set up a few defaults
		base.currentPage = 1;
		base.timer = null;
		base.playing = false;
	
		// Add a reverse reference to the DOM object
		base.$el.data("ElegantAccordion", base);
		  
		base.init = function() {
					
			base.options = $.extend({},$.eAccordion.defaults, options);
				
			// Cache existing DOM elements for later 
			base.$items   = base.$el.children('li');
			base.$single  = base.$items.first();
			
			// Set the dimensions
			if (base.options.height) {
				base.$items.css('height', base.options.height);
			}
	
			// Get the details
			base.pages = base.$items.length;
			var expandedWidth;
			if (base.options.expandedWidth.indexOf("%") > -1) {
				expandedWidth = base.$el.width() * (parseInt(base.options.expandedWidth) / 100);
			} else {
				expandedWidth = parseInt(base.options.expandedWidth);
			}
			base.contractedWidth = (base.$el.width() - 900 ) / (base.pages - 1);
			
			
			// If autoPlay functionality is included, then initialize the settings
			if (base.options.autoPlay) {
				base.playing = !base.options.startStopped; // Sets the playing variable to false if startStopped is true
				base.startStop(base.playing);
			};
			
			// If pauseOnHover then add hover effects
			if (base.options.pauseOnHover) {
				base.$el.hover(function() {
					base.clearTimer();
				}, function() {
					base.startStop(base.playing);
				});
			}
			
			// Add formatting
			base.$items.prepend('<div class="bgGradient"/>').hover(function () {
				base.startStop(false);
				base.gotoPage(base.$items.index(this) + 1);
			},function(){
				if (!base.clickStopped) base.startStop(true);
			}).click(function () {
				base.startStop(false);
				// Prevents the hover-out from re-enabling
				base.clickStopped = true;
			}).children('div').width(expandedWidth);
			
			// If a hash can not be used to trigger the plugin, then go to page 1
			if ((base.options.hashTags == true && !base.gotoHash()) || base.options.hashTags == false) {
				base.gotoPage(1, false);
			};
		}
			
		base.gotoPage = function(page, animate) {
			if (typeof(page) == "undefined" || page == null) {
				page = 1;
			};
			
			// Stop the slider when we reach the last page, if the option stopAtEnd is set to true
			if(base.options.stopAtEnd){
				if(page == base.pages) base.startStop(false);
			}
			
			
			// Just check for bounds
			if (page > base.pages) page = 1;
			if (page < 1) page = 1;
			
			// Store the page to be shown
			var $page = base.$items.eq(page - 1);
			
			if (animate !== false) {
				$page.stop().animate(
					{'width':base.options.expandedWidth},
					base.options.animationTime,
					base.options.easing
				).siblings().stop().animate({
					'width':base.contractedWidth},
					base.options.animationTime - 10,
					base.options.easing
				);
				$page.children('.main_heading').stop(true,true).fadeOut();
				$page.children('div:not(.bgGradient)').stop(true,true).fadeIn();
				$page.children('.bgGradient').stop(true,true).animate(
					{bottom:0},
					base.options.animationTime
				);
				$page.siblings().children('.main_heading').stop(true,true).fadeIn();
				$page.siblings().children('div:not(.bgGradient)').stop(true,true).fadeOut();
				$page.siblings().children('.bgGradient').stop(true,true).animate(
					{bottom:'-340px'},
					base.options.animationTime
				);
			} else {
				$page.width(base.options.expandedWidth).siblings().width(base.contractedWidth);
				$page.children('.main_heading').hide();
				$page.children('div:not(.bgGradient)').show();
				$page.children('.bgGradient').css('bottom','0');
				$page.siblings().children('.main_heading').show();
				$page.siblings().children('div:not(.bgGradient)').hide();
				$page.siblings().children('.bgGradient').css('bottom','-340px');
			}
			
			// Update local variable
			base.currentPage = page;
		};
			
		base.goForward = function() {
			base.gotoPage(base.currentPage + 1);
		};

		base.goBack = function() {
			base.gotoPage(base.currentPage - 1);
		};
		
		// This method tries to find a hash that matches panel-X
		// If found, it tries to find a matching item
		// If that is found as well, then that item starts visible
		base.gotoHash = function(){
			var hash = window.location.hash.match(/^#?panel(\d+)-(\d+)$/);
			if (hash) {
				var panel = parseInt(hash[1]);
				if (panel == base.runTimes) {
					var slide = parseInt(hash[2]);
					var $item = base.$items.filter(':eq(' + slide + ')');
					if ($item.length != 0) {
						base.gotoPage(slide, false);
						return true;
					}
				}
			}
			return false; // A item wasn't found;
		};
		
		// Handles stopping and playing the slideshow
		// Pass startStop(false) to stop and startStop(true) to play
		base.startStop = function(playing) {
			if (playing !== true) playing = false; // Default if not supplied is false
			
			// Update variable
			base.playing = playing;
			
			if (playing){
				base.clearTimer(); // Just in case this was triggered twice in a row
				base.timer = window.setInterval(function() {
					base.goForward();
				}, base.options.delay);
			} else {
				base.clearTimer();
			};
		};
		
		base.clearTimer = function(){
			// Clear the timer only if it is set
			if(base.timer) window.clearInterval(base.timer);
		};
		
		// Taken from AJAXY jquery.history Plugin
		base.setHash = function (hash) {
			// Write hash
			if ( typeof window.location.hash !== 'undefined' ) {
				if ( window.location.hash !== hash ) {
					window.location.hash = hash;
				};
			} else if ( location.hash !== hash ) {
				location.hash = hash;
			};
			
			// Done
			return hash;
		};
		// <-- End AJAXY code

		// Trigger the initialization
		base.init();
	};

	
	
	$.fn.eAccordion = function(options) {
	  
		if (typeof(options) == "object"){
			return this.each(function(i){			
				(new $.eAccordion(this, options));
			});	
		
		} else if (typeof(options) == "number") {

			return this.each(function(i) {
				var eSlide = $(this).data('ElegantAccordion');
				if (eSlide) {
					eSlide.gotoPage(options);
				}
			});
			
		}
		
  };
	
})(jQuery);
</script>

<?php  if($conflict_load == 1) { ?> 
<script>
var $jx = jQuery.noConflict();
    $jx(function() {
        $jx('#accordion').eAccordion({
            easing: 'swing',                // Anything other than "linear" or "swing" requires the easing plugin
            autoPlay:<?php echo $auto_play;?>,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not
            startStopped: false,            // If autoPlay is on, this can force it to start stopped
            stopAtEnd: false,				// If autoplay is on, it will stop when it reaches the last slide
            delay:<?php echo $delay_autoplay;?>,                    // How long between slide transitions in AutoPlay mode
            animationTime:<?php echo $sliding_speed;?>,             // How long the slide transition takes
            hashTags:true,                 // Should links change the hashtag in the URL?
            pauseOnHover: false,             // If true, and autoPlay is enabled, the show will pause on hover
            width:null,					// Override the default CSS width
            height:null,					// Override the default CSS height
            expandedWidth:'<?php echo $single_menu_expand_width;?>'		// Width of the expanded slide
        });
    });
</script>
<?php } else { ?>
<script>
$(function() {
        $('#accordion').eAccordion({
            easing: 'swing',                // Anything other than "linear" or "swing" requires the easing plugin
            autoPlay:<?php echo $auto_play;?>,                 // This turns off the entire FUNCTIONALY, not just if it starts running or not
            startStopped: false,            // If autoPlay is on, this can force it to start stopped
            stopAtEnd: false,				// If autoplay is on, it will stop when it reaches the last slide
            delay:<?php echo $delay_autoplay;?>,                    // How long between slide transitions in AutoPlay mode
            animationTime:<?php echo $sliding_speed;?>,             // How long the slide transition takes
            hashTags:true,                 // Should links change the hashtag in the URL?
            pauseOnHover: true,             // If true, and autoPlay is enabled, the show will pause on hover
            width:null,					// Override the default CSS width
            height:null,					// Override the default CSS height
            expandedWidth:'<?php echo $single_menu_expand_width;?>'		// Width of the expanded slide
        });
    });
	</script>
<?php } ?>
        
        
        
        

 