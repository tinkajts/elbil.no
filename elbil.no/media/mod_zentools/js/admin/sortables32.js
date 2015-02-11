/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.7.2
 */

(function($) {
	$.fn.availableTags = function() {

		available = new Array();

		if($(imageSource).is(":selected")) {
			available = ['title','image','text','more','column2','column3','column4','tweet'];
		}

		// Joomla content as a source
		if($(joomlaSource).is(":selected")){
			available = ['title','image','text','date','category','more','column2','column3','column4','tweet','isfeatured'];
		}

		if($(k2Source).is(":selected")){
			available = ['title','image','text','date','category','more','comments','extrafields','video','column2','column3','column4','tweet','isfeatured']
		}

	}

	$.fn.initSortables = function() {
		// Retrieve the cookie contents for this instance of the module
		setItems = ($(current).val()).split(",");

		//if the current contents is empty initialise the array
		if(!setItems) {
			setItems = [];
		}
	}

	$.fn.setSortables = function() {

		$(this).availableTags();

		// Empty the sortable lists in case the default button clicked
		$(usedList).empty();
		$(unusedList).empty();

		// Reinstate the instructions
		$(usedList).prepend("<li class='disabled'>Drag items here to use</li>");
		$(unusedList).prepend("<li class='disabled'>Available Items</li>");

		// Array to store the unused items
		var unusedItems = Array();


		// Look through the array of possible tags and filter out the ones that are actually being used.
		$.each(available, function(i, val){
			 if($.inArray(val, setItems) < 0)
				 unusedItems.push(val);
		});


		// Store the used items so they will be retrieved on page load.
		$(current).val(setItems);


		// Create two strings out of the arrays
		var useditems = setItems.join(',');
		var unuseditems = unusedItems.join(',')

		// Then split them again
		var used = useditems.split(",");
		var unused = unuseditems.split(",");


		// Populate the list of sortable items
		if($(current).val().length > 0)  {
										// Repopulates the list of elements. We check for the length of the textbox and if its not empty populate it.
				$.each(used, function(i)
				{
					var li = $('<li/>').attr('id', used[i]).addClass(used[i]).text(used[i]).appendTo(usedList);
				});
			}

			// Sets the list of available items
			$.each(unused, function(i)
			{
				var li = $('<li/>').attr('id', unused[i]).addClass(unused[i]).text(unused[i]).appendTo(unusedList);
			});
	};


	$.fn.presetMessage = function(){

		$('#default span,#Default span').html('Preset applied ...');

		window.setTimeout(function () {
			$('#default span,#Default span').html('Set default options');
		}, 2000);
	}

	$.fn.clickDefault = function(){
		$('#default span,#Default span').html('Click here to apply preset ...');
	}


	$.fn.setAccordion = function() {
		setItems = ['title','column2','image','column3','text','more'];
		$(titlePanel).parent().show();
		$(text + ',' + image + ',' + more).parent().show();
		$(paramscol1option)[11].selected = true;
		$(paramscol2option)[3].selected = true;
		$(paramscol3option)[7].selected = true;
		$(this).presetMessage();
	}


	$.fn.setGrid = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel + ',' + gridPanel).parent().show();
		$(this).presetMessage();
	}

	$.fn.gridPanel = function() {
		$(gridPanel).parent().show();
	}

			$.fn.setGridFiltered = function() {
				setItems = ['image','title','text'];
				$(image + ',' + titlePanel + ',' + text  + ',' + gridPanel + ',' + filterPanel).parent().show();
				$(paramslayoutoption)[0].selected = true;
				$(paramscatfilter)[0].selected = true;
				$(paramscatfilter)[1].selected = false;
				$(this).presetMessage();
			}

			$.fn.setGridTwoColumns = function() {
				setItems = ['image','column2','title','text'];

				$(paramscol2).show();
				$(paramscol1option)[3].selected = true;
				$(paramscol2option)[7].selected = true;
				$(paramsgridsperrow)[2].selected = true;
				$(paramscatfilter)[1].selected = true;
				$(paramslayoutoption)[0].selected = true;
				$(this).presetMessage();
			}


			$.fn.setGridCaptify = function() {
				setItems = ['image','title'];
				$(image + ',' + titlePanel + ',' + gridPanel).parent().show();
				$(overlaygridOption)[0].selected = true;
				$(paramsgridsperrow)[3].selected = true;
				$(paramslayoutoption)[0].selected = true;
				$(this).presetMessage();
			}


	$.fn.setList = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).parent().show();
		$(overlaygridOption)[1].selected = true;
		$(this).presetMessage();
	}

			$.fn.setListTwoColumn = function() {
				setItems = ['image','column2','title','text'];
				$(image + ',' + titlePanel).parent().show();
				$(paramscol1option)[3].selected = true;
				$(paramscol2option)[7].selected = true;
				$(paramslayoutoption)[1].selected = true;

				$(this).presetMessage();

			}

			$.fn.setListThreeColumn = function() {
					setItems = ['title','date','category','column2','image','column3','text','more'];
					$(image + ',' + titlePanel +  ',' + date + ',' + more).parent().show();
					$(paramscol1option)[3].selected = true;
					$(paramscol2option)[3].selected = true;
					$(paramscol3option)[3].selected = true;
					$(paramslayoutoption)[1].selected = true;

				$(this).presetMessage();

			}


	$.fn.setCarousel = function() {
		setItems = ['image'];
		$(image + ',' + carouselPanel).parent().show();
		$(this).presetMessage();
	}

		$.fn.carouselPanel = function() {
			$(carouselPanel).parent().show();
		}

	$.fn.setMasonry = function() {
		setItems = ['image','title','text'];
		$(image + ',' + titlePanel + ',' + masonryPanel).parent().show();
		$(paramsmasonrywidths)[0].selected = true;
		$(paramsmasonrycolwidths)[3].selected = true;
		$(this).presetMessage();

	}

		$.fn.masonryPanel = function() {
			$(masonryPanel).parent().show();
		}

	$.fn.setSlideshow = function() {
		setItems = ['image','column2','title','text'];
		$(image + ',' + titlePanel + ',' + slideshowPanel).parent().show();
		$(paramscol1option)[3].selected = true;
		$(paramscol2option)[7].selected = true;
		$(paramsslideshowThemeOption)[2].selected = true;
		$(this).presetMessage();
	}

		$.fn.slideshowPanel = function() {
			$(slideshowPanel).parent().show();
		}

			$.fn.setSlideshowOverlay = function() {
				setItems = ['image','title'];
				$(image + ',' + titlePanel + ',' + slideshowPanel).parent().show();
				$(paramslayoutoption)[3].selected = true;
				$(this).presetMessage();
			}

			$.fn.setSlideshowFlat = function() {
				setItems = ['image','column2','title','text'];
				$(image + ',' + titlePanel + ',' + slideshowPanel).parent().show();
				$(paramslayoutoption)[3].selected = true;
				$(this).presetMessage();
			}

	$.fn.setLeading = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).parent().show();
		$(this).presetMessage();
	}

	$.fn.setSingle = function() {
		setItems = ['image'];
		$(image + ',' + lightboxpanel).parent().show();
		$(linkselected)[1].selected = true;
		$(this).presetMessage();

	}

	$.fn.setPagination = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).parent().show();
		$(this).presetMessage();
	}

	$.fn.imagePanel = function() {
		$(image).parent().show();
	}

	$.fn.k2Panel = function() {
		$(panelk2).parent().show();
	}

	$.fn.joomlaPanel = function() {
		$(panelcontent).parent().show();
	}

	$.fn.accordionPanel = function() {
		$(accordionPanel).parent().show();
	}

	$.fn.lightboxPanel = function() {
		$(lightboxpanel).parent().show();
	}

	$.fn.twitterPanel = function() {
		$(twitterPanel).parent().show();
	}

	$.fn.textPanel = function() {
		$(text).parent().show();
	}

	$.fn.titlePanel = function() {
		$(titlePanel).parent().show();
	}

	$.fn.datePanel = function() {
		$(date).parent().show();
	}

	$.fn.morePanel = function() {
		$(more).parent().show();
	}

	$.fn.paginationPanel = function() {
		$(pagination).parent().show();
	}

	$.fn.filterPanel = function() {
		$(filterPanel).parent().show();
	}
	$.fn.externalLinksPanel = function() {
		$(externallinkslbl).parent().parent().show();
		$(externallinks).parent().parent().show();
		$(linktarget).parent().parent().show();
		$(linktargetlbl).parent().parent().show();
		$(altlinks).parent().parent().hide();
		$(altlinkslbl).parent().parent().hide();
	}

	$.fn.contentLinksPanel = function() {
		$(altlinks).parent().parent().show();
		$(altlinkslbl).parent().parent().show();
		$(linktarget).parent().parent().show();
		$(linktargetlbl).parent().parent().show();
		$(externallinkslbl).parent().parent().hide();
		$(externallinks).parent().parent().hide();
	}

	$.fn.updateLink = function() {
		$(altlinks).parent().parent().hide();
		$(altlinkslbl).parent().parent().hide();
		$(linktarget).parent().parent().hide();
		$(linktargetlbl).parent().parent().hide();
		$(externallinkslbl).parent().parent().hide();
		$(externallinks).parent().parent().hide();

		switch ($(linkselect).text()) {
			case 'Lightbox':
				$(this).lightboxPanel();
			break;

			case 'External Links':
				$(this).externalLinksPanel();
			break;

			case 'Content item':
				$(this).contentLinksPanel();
			break;
		}
	}

	$.fn.layoutSwitch= function() {
		// Figure out which default to set

		$(
			imageSourcePanel
			+ ',' + text
			+ ',' + image
			+ ',' + accordionPanel
			+ ',' + slideshowPanel
			+ ',' + gridPanel
			+ ',' + carouselPanel
			+ ',' + filterPanel
			+ ',' + masonryPanel
			+ ',' + panelcontent
			+ ',' + panelk2
			+ ',' + titlePanel
			+ ',' + more
			+ ',' + date
			+ ',' + lightboxpanel
			+ ',' + pagination
			+ ',' + twitterPanel
			+ ',' + columnwidthPanel
		).parent().hide();

		switch ($(paramslayoutSelected).text()) {
			case 'Grid':
				$(this).gridPanel();
				$(this).filterPanel();
			break;

			case 'List':
				$(this).filterPanel();
			break;

			// Slideshow
			case 'Slideshow':
				$(this).slideshowPanel();
			break;

			// Carousel
			case 'Carousel':
				$(this).carouselPanel();
			break;

			// Masonry
			case 'Masonry':
				$(this).masonryPanel();
			break;

			// Masonry
			case 'Pagination':
				$(this).gridPanel();
				$(this).paginationPanel();
			break;

			// Masonry
			case 'Accordion':
				$(this).accordionPanel();
			break;
		}


		// Directory Source
		if($(imageSource).is(":selected")) {
			$(this).imagePanel();
			$(imageSourcePanel).parent().show();
		}

		// K2 Source
		if ($(k2Source).is(":selected")) {
			$(this).k2Panel();
		}


		// Joomla content as a source
		if($(joomlaSource).is(":selected")){
			$(this).joomlaPanel();
		}

		// toggle for the k2 image option
		if(($(k2Source).is(":selected")) && ($("#sortable li#image").length == 1) ) {

			$(k2imagetypelbl + ',' + k2imagetype + ',' + k2imageoptions).parent().parent().show();
			$(filterstartK2lbl).parent().parent().show();
			$(filterstartJoomlalbl).parent().parent().hide();
		}
		else {
			$(k2imagetypelbl + ',' + k2imagetype + ',' + k2imageoptions).parent().parent().hide();
			$(filterstartK2lbl).parent().parent().hide();
			$(filterstartJoomlalbl).parent().parent().show();
		}


		$(this).updateLink();


		// Hides text related panels if image isnt in the ordering
		if($("#sortable li#text").length == 1)  	{$(this).textPanel();}
		if($("#sortable li#date").length == 1)  	{$(this).datePanel();}
		if($("#sortable li#image").length == 1)  	{$(this).imagePanel();}
		if($("#sortable li#title").length == 1)  	{$(this).titlePanel();}
		if($("#sortable li#more").length == 1)  	{$(this).morePanel();}
		if($("#sortable li#tweet").length == 1)  	{$(this).twitterPanel();}


		$.fn.layoutSwitchColumns();
	};

	$.fn.hideAllPanels = function() {
		$(
			imageSourcePanel
			+ ',' + text
			+ ',' + image
			+ ',' + accordionPanel
			+ ',' + slideshowPanel
			+ ',' + gridPanel
			+ ',' + carouselPanel
			+ ',' + filterPanel
			+ ',' + masonryPanel
			+ ',' + panelcontent
			+ ',' + panelk2
			+ ',' + titlePanel
			+ ',' + more
			+ ',' + date
			+ ',' + lightboxpanel
			+ ',' + pagination
			+ ',' + twitterPanel
			+ ',' + columnwidthPanel
		).parent().hide();

		$(
			thumbwidth
			+ ',' + thumbwidthlbl
			+ ',' + thumbheight
			+ ',' + thumbheightlbl
			+ ',' + slideTitleWidth
			+ ',' + slideTitleWidthlbl
			+ ',' + slideTitleWidth
			+ ',' + slideTitleWidthlbl
			+ ',' + slideTitleTheme
			+ ',' + slideTitleThemelbl
			+ ',' + slideTitleBreak
			+ ',' + slideTitleBreaklbl
			+ ',' + itemsperpage
			+ ',' + itemsperpagelbl
			+ ',' + externallinks
			+ ',' + externallinkslbl
			+ ',' + altlinks
			+ ',' + altlinkslbl
			+ ',' + linktarget
			+ ',' + linktargetlbl
			+ ',' + k2imagetypelbl
			+ ',' + k2imagetype
			+ ',' + k2imageoptions
			+ ',' + paramscol1lbl
			+ ',' + paramscol2lbl
			+ ',' + paramscol3lbl
			+ ',' + paramscol4lbl
		).parent().parent().hide();
	};

	$.fn.layoutSwitchColumns = function() {
		if($("#sortable li#column2").length == 1)
		{
			col2 = 1;
			$(paramscol2lbl).parent().parent().show();
		}
		else
		{
			col2 = 0;
		}

		if($("#sortable li#column3").length == 1)
		{
			$(paramscol3lbl).parent().parent().show();
			col3 = 1;
		}
		else
		{
			col3 = 0;
		}

		if($("#sortable li#column4").length == 1)
		{
			$(paramscol4lbl).parent().parent().show();
			col4 = 1;
		}
		else
		{
			col4 = 0;
		}

		if((col2 + col3 + col4) > 0)
		{
			$(paramscol1lbl).parent().parent().show();
		}
		else
		{
			$(paramscol1lbl).parent().parent().hide();
		}
		if($("#sortable li#tweet").length == 1)
		{
			$(twitterPanel).parent().show();
		}
	};
})(jQuery);
