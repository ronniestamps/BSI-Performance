// ---------------------------------------------------------
// Frontend jQuery on DOM load
// ---------------------------------------------------------

jQuery(document).ready(function($) {

	"use strict";

	var $window			= $(window),
		window_width	= $window.width(),
		$body			= $('body'),
		$header			= $('#branding'),
		$primary_menu	= $('.tb-primary-menu'),
		youtube			= null,
		yt_players		= {},
		$popout_img 	= $('.site-inner.full_width .entry-content .alignnone'),
		tbmethods		= {

			/**
			 * Inititate control over youtube
			 * background video. Required in
			 * order to mute the video.
			 */
			yt_init: function() {
				$('.tb-bg-video.youtube .video').each(function() {

					var $el = $(this),
						params = $el.data();

					yt_players[this.id] = new YT.Player(this.id, {
						videoId: params.vid,
						playerVars: params,
						events: {
							'onReady': tbmethods.yt_ready
						}
					});

				});
			},
			yt_ready: function(e) {

				var iframe = e.target.getIframe(),
					$el = $('#'+iframe.id);

				e.target.mute();
				e.target.playVideo();

				setTimeout(function(){ // eliminate intial flicker

					$el.closest('.youtube').addClass('playing');

					tbmethods.bg_video_size($el);

					$window.on('resize', function(){
						tbmethods.bg_video_size($el);
					});

				}, 500);

			},

			/**
			 * Resize background video to fit
			 * sections.
			 */
			bg_video_size: function( $el ) {

				if ( ! $el.is('video, iframe') ) {
					return;
				}

				var id 			= $el.attr('id'),
					css 		= '',
					$section 	= $el.closest('.has-bg-video'),
					section_w 	= $section.outerWidth(),
					section_h 	= $section.outerHeight(),
					ratio		= $el.closest('.tb-bg-video').data('ratio').split(':'),
					w			= 0,
					h 			= 0,
					video_w		= ratio[0],
					video_h		= ratio[1],
					cover_h		= ( video_h * section_w ) / video_w,
					cover_w		= ( video_w * section_h ) / video_h;

				css = '#'+id+' {';

				if ( section_w / section_h > video_w / video_h ) {
					w = Math.ceil( section_w );
					h = Math.ceil( cover_h );
				} else {
					w = Math.ceil( cover_w );
					h = Math.ceil( section_h );
				}

				css += 'width: ' + (w + 2) + 'px !important;'; // 2px buffer
				css += 'height: ' + (h + 2) + 'px !important;'; // 2px buffer

				css += '}';

				$('#'+id+'-styles').remove();

				$("<style type='text/css' id='"+id+"-styles'>"+css+"</style>").appendTo('head:first');

			},

			/**
			 * Resize images to popout and fill viewport
			 */
			popout_img: function( $body, $window, $popout_img ) {

				$popout_img.each(function(){

					var $el = $(this),
						width = $body.hasClass('tb-boxed') ? $('#container').width() : $window.width();

					if ( $window.width() >= 992 ) {
						if ( $el.hasClass('wp-caption') ) {

							var $img = $el.find('img');

							if ( $img.hasClass('size-full') ) {

								if ( $body.hasClass('rtl') ) {
									$el.css({ 'margin-right': ( ( $el.closest('.entry-content').width() / 2 ) - ( width / 2 ) ), 'max-width': 'none' }).addClass('tb-img-popout');
								} else {
									$el.css({ 'margin-left': ( ( $el.closest('.entry-content').width() / 2 ) - ( width / 2 ) ), 'max-width': 'none' }).addClass('tb-img-popout');
								}

								$el.add($img).css({'width': width, 'max-width': 'none' });
							}

						} else if ( $el.hasClass('size-full') ) {

							if ( $body.hasClass('rtl') ) {
								$el.css( { 'margin-right': ( ( $el.closest('.entry-content').width() / 2 ) - ( width / 2 ) ), 'margin-left': '0', 'max-width': 'none', 'width': width }).addClass('tb-img-popout');
							} else {
								$el.css( { 'margin-left': ( ( $el.closest('.entry-content').width() / 2 ) - ( width / 2 ) ), 'margin-right': '0', 'max-width': 'none', 'width': width }).addClass('tb-img-popout');
							}
						}
					}

				});
			}
		};

	// ---------------------------------------------------------
	// Dynamic Body Classes
	// ---------------------------------------------------------

	// Add "mobile-on" and "tablet-on" classes to body,
	// only depending on viewport size.
	//
	// NOTE: These are different than the default "mobile"
	// and "desktop" classes filtered onto WP's body_class(),
	// which denote if user is on a true mobile device
	if ( window_width < 768 ) {

		$body.addClass('mobile-on');
		$body.removeClass('tablet-on');

		if ( $header.hasClass('transparent') ) {
			$header.removeClass('transparent');
			$header.addClass('transparent-off');
		}

	} else if ( window_width < 992 ) {
		$body.addClass('tablet-on');
		$body.removeClass('mobile-on');
	}

	$window.on('resize', function(){

		var window_width = $window.width();

		if ( window_width < 768 ) {

			$body.addClass('mobile-on');
			$body.removeClass('tablet-on');

			if ( $header.hasClass('transparent') ) {
				$header.removeClass('transparent');
				$header.addClass('transparent-off');
			}

		} else if ( window_width < 992 ) {

			$body.addClass('tablet-on');
			$body.removeClass('mobile-on');

			if ( $header.hasClass('transparent-off') ) {
				$header.removeClass('transparent-off');
				$header.addClass('transparent');
			}

		} else {

			$body.removeClass('tablet-on');
			$body.removeClass('mobile-on');

			if ( $header.hasClass('transparent-off') ) {
				$header.removeClass('transparent-off');
				$header.addClass('transparent');
			}
		}
	});

	// ---------------------------------------------------------
	// Menus
	// ---------------------------------------------------------

	// Responsive side menu
	if ( themeblvd.mobile_panel == 'true' ) {

		// Add initial class that denotes the menu is hidden on
		// page load. The menu will be hidden on its own, but
		// this allows for CSS3 transitions.
		$body.addClass('mobile-menu-'+themeblvd.mobile_menu_location+'-off');

		// Create empty wrapper for the side menu
		$('#wrapper').after('<div class="tb-mobile-menu-wrapper '+themeblvd.mobile_panel_class+' '+themeblvd.mobile_menu_location+'"><div class="wrap"></div></div>');

		// Generate content for side menu
		var $side_holder = $('.tb-mobile-menu-wrapper > .wrap'),
			$extras = $('.tb-to-mobile-menu'), // Any items that you want to be moved in the side menu location, add class "tb-to-mobile-menu"
			$nav_trigger = $('.tb-nav-trigger'),
			max = parseInt(themeblvd.mobile_menu_viewport_max);

		// Add search box, if exists in header
		$('.tb-floating-search .tb-search').first().clone().addClass('mini panel-item').appendTo( $side_holder );

		// Add primary menu
		if ( $primary_menu.hasClass('tb-to-mobile-menu') ) {
			$primary_menu.first().clone().removeClass('sf-menu tb-primary-menu tb-to-mobile-menu').addClass('tb-mobile-menu tb-side-menu panel-item').appendTo( $side_holder ); // "tb-side-menu" class allows for level 2+ tree styling
		}

		// Add side panel menu
		if ( themeblvd.side_panel == 'true' ) {

			var $desktop_side_panel = $('.tb-side-panel > .wrap');

			if ( $side_holder.find('.tb-mobile-menu').length ) {
				$desktop_side_panel.find('.menu').children('li').clone().appendTo( $side_holder.find('.tb-mobile-menu') );
			} else {
				$desktop_side_panel.find('.menu').first().clone().addClass('tb-mobile-menu tb-side-menu panel-item').appendTo( $side_holder );
			}

			// Remove submenu toggles
			$side_holder.find('.submenu-toggle').remove();

			// Add secondary menu from side panel
			$desktop_side_panel.find('.secondary-menu').clone().addClass('panel-item').appendTo( $side_holder );

		}

		// Add header text and social icons, if they exist
		$body.find('.header-text.to-mobile').first().clone().addClass('panel-item').appendTo( $side_holder );
		$body.find('.tb-social-icons.to-mobile').first().clone().removeClass('grey dark light flat').addClass('panel-item').appendTo( $side_holder );

		// If original <ul> had search or contact, remove because
		// it's now elsewhere.
		$side_holder.find('li.menu-search, li.menu-contact, li.menu-cart').remove();

		// Add WPML switcher, if exists in header
		if ( ! $header.find('.menu-item-language').length ) { // Make sure main nav doesn't already hace switcher
			$header.find('.top-wpml').each(function() {

				var $el = $(this),
					$switcher = $('<li class="menu-item mobile-wpml"></li>'),
					$top = $('<span class="menu-btn">'+$el.find('.tb-wpml-switcher > ul > li > a').html()+'</span>'),
					$sub = $el.find('.lang-sub-menu');

				// Is there a dropdown of languages for this page?
				if ( $sub.length ) {
					$switcher.append( $top );
					$top.find('.fa').addClass('sf-sub-indicator');
					$switcher.append( $sub.clone().addClass('sub-menu non-mega-sub-menu') );
					$switcher.appendTo( $side_holder.find('.tb-mobile-menu') );
				}

			});
		}

		// Fire custom event
		$body.trigger('themeblvd-mobile-panel', $side_holder);

		// Show hide mobile menu
		$nav_trigger.on('click', function(){

			if ( $nav_trigger.hasClass('collapse') ) {
				$body.removeClass('mobile-menu-'+themeblvd.mobile_menu_location+'-on').addClass('mobile-menu-'+themeblvd.mobile_menu_location+'-off');
				$nav_trigger.removeClass('collapse');
			} else {
				$body.removeClass('mobile-menu-'+themeblvd.mobile_menu_location+'-off').addClass('mobile-menu-'+themeblvd.mobile_menu_location+'-on');
				$nav_trigger.addClass('collapse');
			}

			return false;
		});

		$('#wrapper').on('click', function(){
			$body.removeClass('mobile-menu-'+themeblvd.mobile_menu_location+'-on').addClass('mobile-menu-'+themeblvd.mobile_menu_location+'-off');
			$nav_trigger.removeClass('collapse');
		});

		$window.on('resize', function(){
			if ( $window.width() > max ) {
				$body.removeClass('mobile-menu-'+themeblvd.mobile_menu_location+'-on').addClass('mobile-menu-'+themeblvd.mobile_menu_location+'-off');
				$nav_trigger.removeClass('collapse');
			}
		});

	}

	// All side menus
	$('.tb-side-menu').on('click', '.tb-side-menu-toggle', function() {

		var $el = $(this);

		if ( $el.hasClass('open') ) {
			$el.next('.sub-menu, .sf-mega').slideUp(100);
			$el.removeClass('open fa-'+$el.data('close'));
			$el.addClass('fa-'+$el.data('open'));
		} else {
			$el.next('.sub-menu, .sf-mega').slideDown(100);
			$el.removeClass('fa-'+$el.data('open'));
			$el.addClass('open fa-'+$el.data('close'));
		}

		return false;
	});

	// Side Panel
	if ( themeblvd.side_panel == 'true' ) {

		$('.tb-side-trigger').on('click', function(){

			var $el = $(this);

			if ( $el.hasClass('collapse') ) {
				$el.removeClass('collapse');
				$body.removeClass('side-panel-on');
			} else {
				$el.addClass('collapse');
				$body.addClass('side-panel-on');
			}

			return false;
		});

		$('.tb-side-panel .submenu-toggle').on('click', function() {

			var $el = $(this);

			if ( $el.hasClass('collapse') ) {
				$el.removeClass('collapse').closest('li').find('.sub-menu').slideUp(200);
			} else {
				$el.addClass('collapse').closest('li').find('.sub-menu').slideDown(200);
			}

			return false;
		});

	}

	// Sticky Menu
	if ( themeblvd.sticky !== 'false' ) {

		// Build sticky menu
		var $sticky = $('<div id="sticky-menu" class="tb-sticky-menu"><div class="wrap sticky-wrap clearfix"><div class="nav"></div></div></div>').insertAfter( $('#wrapper') );

		if ( themeblvd.sticky_class ) {
			$sticky.addClass(themeblvd.sticky_class);
		}

		// Add the logo
		$header.find('.header-logo:first-child').clone().appendTo( $sticky.find('.sticky-wrap') );

		if ( themeblvd.sticky_logo ) {
			$sticky.find('.header_logo_image img').attr('src', themeblvd.sticky_logo).removeAttr('width height srcset');
		}

		// Add nav menu
		$primary_menu.clone().appendTo( $sticky.find('.sticky-wrap > .nav') );

		// Contact icons
		if ( $sticky.find('.tb-primary-menu li.menu-contact').length ) {

			// Popover already exists in menu, so just adjust the container
			$sticky.find('.tb-contact-trigger').data('container', '.tb-sticky-menu');

		} else if ( $header.find('.tb-social-icons').length ) {

			// No popover yet in menu, but it's present in header.
			// Transfer this to a menu item.
			$sticky.find('.tb-primary-menu').append('<li class="menu-item level-1 menu-contact"><a href="#" class="tb-contact-trigger menu-btn" tabindex="0" data-toggle="popover" data-container=".tb-sticky-menu" data-placement="bottom" data-open="envelope-o" data-close="close"><i class="fa fa-envelope-o"></i></a></li>');
			$header.find('.tb-social-icons').first().clone().appendTo( $sticky.find('.menu-contact') ).wrap('<div class="contact-popover-content hide"></div>');

		}

		// We can't have "light" icons in the contact popover because
		// that background is white.
		if ( $sticky.find('.tb-social-icons').hasClass('light') ) {
			$sticky.find('.tb-social-icons').removeClass('light').addClass('grey');
		}

		// Floating cart trigger
		if ( $header.find('.tb-cart-trigger').length && ! $sticky.find('.tb-primary-menu li.menu-cart').length ) {
			$header.find('.tb-cart-trigger').first().clone().appendTo( $sticky.find('.tb-primary-menu') ).wrap('<li class="menu-item level-1 menu-cart"></li>');
		}

		// Floating search
		if ( $sticky.find('.tb-primary-menu li.menu-search').length ) {

			// Search trigger already exists in menu, so just adjust
			// the placement and add search box.
			$sticky.find('.tb-search-trigger').addClass('menu-btn');

		} else if ( $header.find('.tb-search-trigger').length ) {

			// No search yet in menu, but it's present in header.
			// Transfer this to a menu item.
			$header.find('.tb-search-trigger').first().clone().appendTo( $sticky.find('.tb-primary-menu') ).wrap('<li class="menu-item level-1 menu-search"></li>');
			$sticky.find('.tb-search-trigger').addClass('menu-btn');

		}

		// Remove any list items from menu with class "no-sticky"
		$sticky.find('.no-sticky').remove();

		// Sticky menu, make selector dynamic
		$header.viewportChecker({
			classToAdd: 'visible',
			offset: parseInt(themeblvd.sticky_offset),
			repeat: true,
			callbackFunction: function($elem, action){

				if ( $elem.hasClass('visible') ) {

					$body.removeClass('sticky-on');

					// Close open contact popovers
					$sticky.find('.tb-contact-trigger').popover('hide');

					// Close open drodpdown menus
					$sticky.find('.menu-item').each(function(){

						var $item = $(this).closest('li');

						if ( $item.hasClass('sfHover') ) {
							$item.removeClass('sfHover');
							$item.find('.non-mega-sub-menu').hide();
							$item.find('.sf-mega').hide();
						}

					});

				} else {

					$body.addClass('sticky-on');

				}
			}
		});

	}

	// Activate Superfish
	if ( themeblvd.superfish == 'true' ) {

		// Main navigation
		$('ul.sf-menu').superfish({
			speed: 200,
			popUpSelector: '.non-mega-sub-menu,.sf-mega',
			onBeforeShow: function() {

				// Fix for dropdown to flyout the opposite direction,
				// when too close to the edge of the viewport.

				var $ul = $(this),
					$li = $ul.closest('li'),
					location = $li.offset(),
					space = 200;

				if ( ! location ) {
					return;
				}

				if ( $body.hasClass('rtl') ) {
					location = location['right'];
				} else {
					location = location['left'];
				}

				if ( ! $ul.hasClass('non-mega-sub-menu') || ! $li.hasClass('level-1') ) {
					return;
				}

				if ( $ul.find('.level-4').length > 0 ) {
					space = 600;
				} else if ( $ul.find('.level-3').length > 0 ) {
					space = 400;
				}

				if ( ( $(window).width() - location ) <= space ) {

					$ul.addClass('reverse');

					$ul.find('.fa-caret-right, .fa-caret-left, .fa-angle-right, fa-angle-left').each(function(){
						$(this).addClass('inverse');
					});

				} else {

					$ul.removeClass('reverse');

					$ul.find('.fa-caret-right, .fa-caret-left, .fa-angle-right, fa-angle-left').each(function(){
						$(this).removeClass('inverse');
					});

				}

			}
		});

		// Mobile navigation
		$('.tb-mobile-menu').superfish({
			speed: 200,
			popUpSelector: '.non-mega-sub-menu,.sf-mega'
		});

	}

	// No-click menu items
	$('ul.sf-menu li.no-click, ul.tb-mobile-menu li.no-click, .tb-side-panel li.no-click').find('a:first').on('click', function(){
		return false;
	});

	$('a.no-click').on('click', function(){
		return false;
	});

	// ---------------------------------------------------------
	// Floating Search
	// ---------------------------------------------------------

	$('.tb-search-trigger').on('click', function() {

		var $el = $('.tb-floating-search');

		$el.addClass('on');

		if ( ! $body.hasClass('mobile') ) {
			$el.find('.search-input').focus();
		}

	    return false;

	});

	$('.tb-floating-search .close-search').on('click', function() {

		$(this).closest('.tb-floating-search').removeClass('on');

		return false;

	});

	$('.tb-floating-search .search-input').on('focusout', function() {

		$(this).closest('.tb-floating-search').removeClass('on');

	});

	// ---------------------------------------------------------
	// "Epic" Thumbnails
	// ---------------------------------------------------------

	$('.epic-thumb.gallery').on({
		mouseenter: function () {
			if ( $window.width() >= 768 ) {
				$(this).addClass('hover').find('.entry-header').stop().fadeOut(200);
			}
		},
		mouseleave: function () {
			if ( $window.width() >= 768 ) {
				$(this).removeClass('hover').find('.entry-header').stop().fadeIn(200);
			}
		}
	});

	// ---------------------------------------------------------
	// Popout Images
	// ---------------------------------------------------------

	if ( $body.hasClass('tb-img-popout') ) {

		// Establish on initial page load
		tbmethods.popout_img( $body, $window, $popout_img );

		// Adjust images with browser resize
		$window.resize(function(){
			tbmethods.popout_img( $body, $window, $popout_img );
		});

	}

	// ---------------------------------------------------------
	// Carousel Galleries
	// ---------------------------------------------------------

	if ( $.isFunction( $.fn.owlCarousel ) ) {

		$window.on('load', function() {

			$('.tb-gallery-carousel').each(function(){

				var $wrap = $(this),
					$carousel = $wrap.find('.owl-carousel'),
					loop = true;

				if ( $wrap.hasClass('has-nav-thumbs') || $wrap.hasClass('has-nav-dots') ) {
					loop = false; // We can't support looping with thumbnail nav or dots nav, because of how owl carousel clones items.
				}

				$carousel.owlCarousel({
					rtl: $body.hasClass('rtl') ? true : false,
					center: true,
					autoWidth: true,
					loop: loop,
					dots: false,
					nav: false,
					margin: 1,
					onInitialized: function() {
						$wrap.find('.tb-loader').fadeOut(100);
					},
					onChanged: function(e) {

						if ( ! loop ) { // Only when thumbnail nav or dots nav exists, and looping is disabled.

							// Make sure next and prev buttons don't show
							// when at start or end of cycle.
							if ( e.item.index == 0 ) {

								$wrap.find('.tb-slider-arrows a.prev').fadeOut(100);
								$wrap.find('.tb-slider-arrows a.next').fadeIn(100);

							} else if ( e.item.index + 1 == e.item.count ) {

								$wrap.find('.tb-slider-arrows a.next').fadeOut(100);
								$wrap.find('.tb-slider-arrows a.prev').fadeIn(100);

							} else {

								$wrap.find('.tb-slider-arrows a.next, .tb-slider-arrows a.prev').fadeIn(100);

							}

							// Make sure thumbnail nav is effected on
							// all state changes.
							if ( $wrap.hasClass('has-nav-thumbs') ) {
								$wrap.find('.carousel-thumb-nav li').removeClass('active');
								$wrap.find('.carousel-thumb-nav li:nth-child(' + (e.item.index + 1) + ')').addClass('active');
							}

							// Make sure dots nav is effected on all
							// state changes.
							if ( $wrap.hasClass('has-nav-dots') ) {
								$wrap.find('.carousel-indicators li').removeClass('active');
								$wrap.find('.carousel-indicators li:nth-child(' + (e.item.index + 1) + ')').addClass('active');
							}

						}
					}
				});

				// Arrow Navigation
				$wrap.find('.tb-slider-arrows a').on('click', function(){

					if ( $(this).hasClass('next') ) {
						$carousel.trigger('next.owl.carousel');
					} else {
						$carousel.trigger('prev.owl.carousel');
					}

					return false;

				});

				// Thumbnail/Dots Navigation
				$wrap.find('.carousel-thumb-nav li, .carousel-indicators li').on('click', function(){

					$carousel.trigger( 'to.owl.carousel', $(this).data('slide-to') );

				});

			}); // end each()

		}); // end on('load')

	} // end if ( $.isFunction( $.fn.owlCarousel ) )

	// ---------------------------------------------------------
	// Sorting/Masonry
	// ---------------------------------------------------------

	$window.on('load', function() {
		$('.tb-isotope').each(function(){

			var $container = $(this),
				mode = 'fitRows',
				origin_left = true;

			if ( $container.hasClass('tb-masonry') ) {
				mode = 'masonry';
			}

			if ( $body.hasClass('rtl') ) {
				origin_left = false;
			}

			$container.find('.post-wrap > .row').fadeIn(400, function(){

				var $iso = $container.find('.post-wrap > .row').isotope({
					layoutMode: mode,
					itemSelector: '.iso-item',
					filter: '*',
					isOriginLeft: origin_left
				});

				$container.find('.tb-loader').fadeOut(100);

				if ( $container.hasClass('tb-filter') ) {

					$container.find('.tb-filter-nav').fadeIn(400);

					$container.find('.filter-menu > li > a').on('click', function(){

						var $link = $(this),
							select = $link.data('filter');

						$container.find('.filter-menu > li').removeClass('active');
						$link.closest('li').addClass('active');
						$iso.isotope({ filter: select });

						$link.closest('.tb-filter').find('.grid-item, .showcase-item').each(function(){

							var $el = $(this);

							if ( $el.is(select) ) {
								$el.find('a.lightbox-gallery-item').addClass('visible');
							} else {
								$el.find('a.lightbox-gallery-item').removeClass('visible');
							}

						});

						return false;
					});
				}

			});

		});
	});

	// ---------------------------------------------------------
	// Contact popover
	// ---------------------------------------------------------

	$('.tb-contact-trigger').popover({
		html : true,
		template : '<div class="tb-contact-popover popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
		content : function() {
			return $(this).next('.contact-popover-content').html();
		}
	}).on('click', function(e){
		e.preventDefault();
	});

	$('.tb-contact-trigger').on('show.bs.popover', function() {
		var $el = $(this);
		$el.html('<i class="fa fa-'+$el.data('close')+'"></i>');
	});

	$('.tb-contact-trigger').on('hide.bs.popover', function() {
		var $el = $(this);
		$el.html('<i class="fa fa-'+$el.data('open')+'"></i>');
	});

	// ---------------------------------------------------------
	// Scroll-to Buttons
	// ---------------------------------------------------------

	if ( themeblvd.scroll_to_top == 'true' ) {

		var $scroll_to_top = $('.tb-scroll-to-top');

		$window.on( 'scroll', function(){
			if ( $(this).scrollTop() > 400 ) {
				$scroll_to_top.fadeIn();
			} else {
				$scroll_to_top.fadeOut();
			}
		});

		$scroll_to_top.on( 'click', function(){
			$('html, body').animate({scrollTop : 0}, 400);
			return false;
		});

	}

	$('.tb-scroll-to').on('click', function(){

		var $el = $(this),
			to = null,
			top = 0;

		if ( this.hash && this.hash != '#' ) {
			to = this.hash;
		} else {
			to = '#'+$el.closest('.element-section').next().attr('id');
		}

		top = $(to).offset().top;

		if ( themeblvd.scroll_to_section_offset ) {
			top = top - parseInt( themeblvd.scroll_to_section_offset );
		} else if ( themeblvd.sticky !== 'false' ) {
			top = top - parseInt( themeblvd.sticky_offset );
		}

		$('html, body').animate({
			scrollTop: top
		}, 800);

		return false;
	});

	// ---------------------------------------------------------
	// Social Share Buttons
	// ---------------------------------------------------------

	$('.tb-share .btn-share.popup').on('click', function(){
		if ( ! window.open( $(this).attr('href'), '', 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no' ) ) {
			document.location.href = $(this).attr('href');
		}
		return false;
	});

	// ---------------------------------------------------------
	// Gallery Shortcode Integration
	// ---------------------------------------------------------

	// Since our gallery integration is specifically designed
	// to work with Magnific Popup, if it isn't included,
	// we'll halt it all together.
	if ( themeblvd.gallery == 'true' ) {

		$('.gallery').each(function(){

			var gallery = $(this);

			gallery.addClass('themeblvd-gallery clearfix');

			gallery.find('.gallery-item a').each(function(){

				// Account for any parameters being added to
				// URL, like with W3 Total Cache, for example.
				var url = this.href.split('?'),
					url = url[0],
					css_class = 'tb-thumb-link';

				// Add bootstrap thumbnail class
				if ( themeblvd.gallery_thumb_frame == 'true' ) {
					css_class += ' thumbnail';
				}

				// Append lightbox and hover effect if thumb links to an image
				if ( url.match(/\.(jpe?g|png|bmp|gif|tiff?)$/i ) ) {

					css_class += ' image';

					if ( themeblvd.magnific_popup == 'true' ) {
						css_class += ' lightbox-gallery-item mfp-image image-button visible';
					}

				} else {

					css_class += ' post';

				}

				$(this).addClass(css_class);

			});

		});
	}

	// ---------------------------------------------------------
	// Lightbox
	// ---------------------------------------------------------

	// Bind magnifico
	if ( themeblvd.magnific_popup == 'true' ) {

		var remove_delay = 0, main_class = '';

		if ( themeblvd.lightbox_animation != 'none' ) {
			remove_delay = 160;
			main_class = 'themeblvd-mfp-'+themeblvd.lightbox_animation;
		}

		// Galleries
		$('.themeblvd-gallery').each(function() {

			$(this).find('.themeblvd-lightbox').each(function(){
				$(this).removeClass('themeblvd-lightbox').addClass('lightbox-gallery-item visible');
			});

			$(this).magnificPopup({
				disableOn: themeblvd.lightbox_mobile_gallery,
				delegate: 'a.lightbox-gallery-item.visible',
				gallery: { enabled: true },
				removalDelay: remove_delay,
				mainClass: main_class,
				image: {
					cursor: null,
				},
				iframe: {
					// Add bottom bar for iframes in gallery
					markup: '<div class="mfp-iframe-scaler">'+
					            '<div class="mfp-close"></div>'+
					            '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
					            '<div class="mfp-bottom-bar">'+
					              '<div class="mfp-title"></div>'+
					              '<div class="mfp-counter"></div>'+
					            '</div>'+
					          '</div>'
				},
				callbacks: {
					markupParse: function(template, values, item) {
						values.title = item.el.attr('title');
					}
  				}
			});

		});

		// Standard, non-gallery lightbox links
		$('.themeblvd-lightbox').magnificPopup({
			disableOn: themeblvd.lightbox_mobile,
			removalDelay: remove_delay,
			mainClass: main_class,
			image: {
				cursor: null,
			}
		});

		// Specific lightbox for iframe (videos, google maps)
		// so we can designate a separate option to disable
		// on mobile. -- Videos seem to be harder to view in
		// the lightbox than images on mobile.
		$('.themeblvd-lightbox.lightbox-iframe, li.menu-item.video > a:first-child').magnificPopup({
			disableOn: themeblvd.lightbox_mobile_iframe,
			type: 'iframe',
			removalDelay: remove_delay,
			mainClass: main_class,
			iframe: {
				// Add bottom bar for iframes in gallery
				markup: '<div class="mfp-iframe-scaler">'+
							'<div class="mfp-close"></div>'+
							'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
							'<div class="mfp-bottom-bar">'+
							  '<div class="mfp-title"></div>'+
							  '<div class="mfp-counter"></div>'+
							'</div>'+
						  '</div>'
			},
			callbacks: {
				markupParse: function(template, values, item) {
					values.title = item.el.attr('title');
				}
			}
		});

		// Localize
		$.extend( true, $.magnificPopup.defaults, {
			tClose: themeblvd.lightbox_close,
			tLoading: themeblvd.lightbox_loading,
			gallery: {
				tPrev: themeblvd.lightbox_previous,
				tNext: themeblvd.lightbox_next,
				tCounter: themeblvd.lightbox_counter
			},
			image: {
				tError: themeblvd.lightbox_error
			},
			ajax: {
				tError: themeblvd.lightbox_error
			}
		});

	}

	// ---------------------------------------------------------
	// Jump Menu
	// ---------------------------------------------------------

	$(".tb-jump-menu").change(function() {
		window.location.href = $(this).val();
	});

	// ---------------------------------------------------------
	// Block & Jumbotron sliders
	// ---------------------------------------------------------

	$('.tb-block-slider').each(function(){

		var $slider_wrap = $(this),
			$slider = $slider_wrap.find('.slider-inner'),
			fx = $slider_wrap.data('fx'),
			speed = $slider_wrap.data('timeout'),
			slideshow = true,
			nav = $slider_wrap.data('nav'),
			pause = false,
			smooth = false,
			controlnav = false;

		if ( $body.hasClass('mobile') ) {
			fx = 'slide'; // Always want "slide" on touch devices
		}

		if ( speed && speed !== '0' && ! $body.hasClass('mobile') ) {
			speed = speed+'000';
		} else {
			slideshow = false; // Auto-rotate always disabled on mobile
		}

		if ( nav && nav !== '0' ) {
			nav = true;
		} else {
			nav = false;
		}

		if ( ! nav ) {
			pause = true;
		}

		$window.on('load', function() {
			$slider.flexslider({
				smoothHeight: smooth,
				animation: fx,
				easing: 'swing',
				slideshowSpeed: speed,
				animationSpeed: '1000',
				slideshow: slideshow,
				directionNav: false,	// Using custom slider controls outputted with slider markup
				controlNav: controlnav,
				pauseOnHover: pause,	// If nav exists, replace with manual action below
				pauseOnAction: false, 	// Replaced with manual action below
				start: function($s){
					if ( ! $body.hasClass('mobile') ) {
						$slider_wrap.find('.tb-slider-arrows').fadeIn(100);
					}
					$slider_wrap.find('.tb-loader').fadeOut(100);
				}
			});
		});

		if ( nav ) {

			// Manual pause on hover, will not continue when hovered off
			$slider.on('mouseover', function(){
				$slider.data('flexslider').flexslider('pause');
			});

			// Custom slider controls
			$slider_wrap.find('.slider-inner > .tb-slider-arrows a').on('click', function(){

				if ( $(this).hasClass('next') ) {
					$slider.data('flexslider').flexslider('next');
				} else {
					$slider.data('flexslider').flexslider('prev');
				}

				$slider.data('flexslider').flexslider('pause');

				return false;
			});
		}

	}); // end $('.tb-block-slider').each()

	// Hero unit slider
	$('.tb-jumbotron-slider').each(function(){

		var $slider = $(this);

		$slider.on('slide.bs.carousel', function(e){

			var height = $(e.relatedTarget).find('.jumbotron-outer').outerHeight();

			if ( ! height ) {
				height = $(e.relatedTarget).find('.jumbotron-wrap').outerHeight();
			}

			$slider.animate({ height: height }, 1000);

			if ( ! $body.hasClass('mobile') ) {
				$slider.addClass('animate');
				setTimeout(function () {
					$slider.removeClass('animate');
				}, 1000);
			}

		});

		$slider.find('.tb-slider-arrows a, .carousel-indicators li').on('click', function(){
			$slider.carousel('pause');
		});

		$window.on('load', function(){
			$slider.find('.carousel-control-wrap').fadeIn(200, function(){
				var height = $slider.find('.item.active .jumbotron-outer').outerHeight();
				$slider.animate({'height': height});
				$slider.find('.tb-loader').fadeOut(100);
				tbmethods.bg_video_size( $slider.find('.tb-bg-video video, .tb-bg-video iframe') );
			});
		});

	});

	$window.on('resize', function(){
		$('.tb-jumbotron-slider').each(function(){

			var $slider = $(this),
				height = $slider.find('.item.active .jumbotron-outer').outerHeight();

			$slider.css('height', height);
		});
	});

	// ---------------------------------------------------------
	// Parallax background effect
	// ---------------------------------------------------------

	var $parallax = $('.tb-parallax .parallax-figure');

	$window.on('load', function() {

		$parallax.each(function() {

			var $el = $(this),
				$img = $el.find('img, .img');

			// Disable for small screens and true mobile devices.
			if ( $body.hasClass('mobile') || $window.width() > 991 && $window.height() > 499 ) {

				var img_height = $img.height(),
					container_height = ($el.height() > 0) ? $el.height() : 500,
					parallax_dist = img_height - container_height,
					bottom = $el.offset().top + container_height,
					top = $el.offset().top,
					scroll_top = $window.scrollTop(),
					window_height = window.innerHeight,
					window_bottom = scroll_top + window_height,
					percent_scrolled = (window_bottom - top) / (container_height + window_height),
					parallax = Math.round((parallax_dist * percent_scrolled));

				if ( (bottom > scroll_top) && (top < (scroll_top + window_height)) ) {
					$img.css('transform', "translate3D(-50%," + parallax + "px, 0)");
				}

			}

			$el.addClass('on').find('.tb-loader').fadeOut(); // fade in the image, after it's been placed

		}); // end $parallax.each(
	}); // end $window.load()

	$window.on('scroll resize', function() {

		// Disable for small screens and true mobile devices.
		if ( $body.hasClass('mobile') || $window.width() < 992 || $window.height() < 500 ) {
			return;
		}

		$parallax.each(function() {

			var $el = $(this),
				$img = $el.find('img, .img'),
				img_height = $img.height(),
				container_height = ($el.height() > 0) ? $el.height() : 500,
				parallax_dist = img_height - container_height,
				bottom = $el.offset().top + container_height,
				top = $el.offset().top,
				scroll_top = $window.scrollTop(),
				window_height = window.innerHeight,
				window_bottom = scroll_top + window_height,
				percent_scrolled = (window_bottom - top) / (container_height + window_height),
				parallax = Math.round((parallax_dist * percent_scrolled));

			if ( (bottom > scroll_top) && (top < (scroll_top + window_height)) ) {
				$img.css('transform', "translate3D(-50%," + parallax + "px, 0)");
			}

		});

	});

	// ---------------------------------------------------------
	// Custom Buttons
	// ---------------------------------------------------------

	if ( themeblvd.custom_buttons == 'true' ) {
		$('.tb-custom-button').hover(
			function() {
				var $el = $(this);
				$el.css({
					'background-color': $el.data('bg-hover'),
					'color': $el.data('text-hover')
				});
			}, function() {
				var $el = $(this);
				$el.css({
					'background-color': $el.data('bg'),
					'color': $el.data('text')
				});
			}
		);
	}

	// ---------------------------------------------------------
	// Google Maps
	// ---------------------------------------------------------

	if ( typeof google === 'object' && typeof google.maps === 'object' ) {
		$('.tb-map').each(function(){

			var $map = $(this);

			var init = function() {

		        var map_id = $map.find('.map-canvas').attr('id'),
					mode = $map.data('mode'),
		        	map_options,
		            map;

				if ( mode == 'roadmap' ) { // ... @TODO Not working and not sure why?
					mode = google.maps.MapTypeId.ROADMAP;
				} else if ( mode == 'satellite' ) {
					mode = google.maps.MapTypeId.SATELLITE;
				} else if ( mode == 'hybrid' ) {
					mode = google.maps.MapTypeId.HYBRID;
				} else if ( mode == 'terrain' ) {
					mode = google.maps.MapTypeId.TERRAIN;
				}

		        map_options = {
		            center: new google.maps.LatLng( $map.find('.map-center').data('lat'), $map.find('.map-center').data('long') ),
		            zoom: $map.data('zoom'),
		            draggable: $map.data('draggable'),
		            panControl: $map.data('pan_control'),
		            zoomControl: $map.data('zoom_control'),
		            mapMaker: false,
		            mapTypeControl: false,
		            mapTypeId: mode, // ... @TODO Not working and not sure why?
		            backgroundColor: 'transparent',
		            streetViewControl: false,
		            scrollwheel: false
		        };

		        map = new google.maps.Map( document.getElementById(map_id), map_options );

		        // Markers
		        $map.find('.map-marker').each(function(){

		        	var $marker = $(this),
						img = null;

					if ( $marker.data('image') ) {

						img = {
							url: $marker.data('image')
						};

						if ( $marker.data('image-width') && $marker.data('image-height') ) {
							img.scaledSize = new google.maps.Size( parseInt( $marker.data('image-width') ), parseInt( $marker.data('image-height') ) );
						}

					}

			        var marker = new google.maps.Marker({
			            position: new google.maps.LatLng( $marker.data('lat'), $marker.data('long') ),
			            map: map,
			            title: $marker.data('name'),
			            icon: img,
			            animation: google.maps.Animation.DROP
			        });

			        var infowindow = new google.maps.InfoWindow({
			            content: '<div class="map-marker-info">'+$marker.find('.map-marker-info').html()+'</div>'
			        });

			        google.maps.event.addListener(marker, 'click', function() {
			            infowindow.open(map,marker);
			        });

			    });


		        // Style map
		        var stylers = [], style = [], map_type;

		        stylers.push({saturation: $map.data('saturation')});
		        stylers.push({lightness: $map.data('lightness')});

		        if ( $map.data('hue') ) {
		        	stylers.push({hue: $map.data('hue')});
		        }

		        if ( stylers.length ) {

		            style = [{
		                featureType: "all",
		                elementType: "all",
		                stylers: stylers
		            }];

		            map_type = new google.maps.StyledMapType(style, {name:'tb_map_style'});
		            map.mapTypes.set('tb_map_style', map_type);
		            map.setMapTypeId('tb_map_style');
		        }

		    };

		    google.maps.event.addDomListener(window, 'load', init);

		});
	}

	// ---------------------------------------------------------
	// Bootstrap Integration
	// ---------------------------------------------------------

	if ( themeblvd.bootstrap == 'true' ) {

		// Add standard table classes to calendar widget
		$('#calendar_wrap table').addClass('table table-bordered');

		// Tabs - Automatic fixed height
		// This allows the user to have the set of tabs
		// automatically stay the height of the tallest tab.
		$('.tabbable.fixed-height').each(function(){

			var tallest = 0;

			$(this).find('.tab-pane').each(function(){

				var currentHeight = $(this).height();

				if ( currentHeight > tallest )
					tallest = currentHeight;

			});

			$(this).find('.tab-pane').height(tallest);
		});

		// Collapsables expanded
		// This basically just toggles the Plus/Minus fontawesome
		// icon we've incorporated into the triggers for the toggles.
		$('.collapse').on('show.bs.collapse', function() {

			// Toggle is opening, add "active-trigger" class and
			// change icon to a minus sign.
			$(this).closest('.panel').find('.panel-heading a').addClass('active-trigger').find('.switch-me').removeClass('fa-plus-circle').addClass('fa-minus-circle');

		});

		$('.collapse').on('hide.bs.collapse', function() {

			// Toggle is closing, remove "active-trigger" class and
			// change icon to a plus sign.
			$(this).closest('.panel').find('.panel-heading a').removeClass('active-trigger').find('.switch-me').removeClass('fa-minus-circle').addClass('fa-plus-circle');

		});

		// And now if the user has wrapped a set of "toggles" into an
		// accordian, this will attach them all.
		var accordion_id;

		$('.tb-accordion').each(function(){
			accordion_id = $(this).attr('id');
			$(this).find('.panel-heading a').each(function(){
				$(this).attr('data-parent', '#'+accordion_id);
			});
		});

		// Carousel thumbnail navigation
		$('.tb-simple-slider .carousel-thumb-nav li').on('click', function(){

			var $el = $(this);

			if ( $el.closest('.tb-simple-slider').data('ride') ) {
				$el.closest('.carousel-thumb-nav').find('li').removeClass('active');
				$el.addClass('active');
			}

		});

		$('.tb-simple-slider').on('slid.bs.carousel', function () {

			var $el = $(this),
				data = $el.data('bs.carousel'),
				current = data.getItemIndex(data.$element.find('.item.active'))+1;

			$el.find('.carousel-thumb-nav li').removeClass('active');
			$el.find('.carousel-thumb-nav li:nth-child('+current+')').addClass('active');

		});

		// Carousel indicator dots navigation -- This fix allows
		// us to integrate links on the slides better by reducing
		// the space around navigation indicator dots.
		$('.tb-simple-slider .carousel-indicators').each(function(){

			var $el = $(this),
				width = ( 14 * $el.find('li').length );

			$el.css({
				'width': width + 'px',
				'margin-left': '-'+(width/2)+'px'
			});
		});

		// When user interacts with carousel controls or slide links,
		// stop auto rotate.
		$('.tb-simple-slider').each(function(){

			var $el = $(this);

			$el.find('.tb-thumb-link, .carousel-indicators li, .carousel-control, .tb-slider-arrows > li > a').on('click', function(){
				$el.carousel('pause');
			});
		});

		// Carousel mobile touch gesture
		if ( $.mobile ) {
			$('[data-ride="carousel"]').swiperight(function() {
				if ( $body.hasClass('rtl') ) {
					$(this).carousel('next');
				} else {
					$(this).carousel('prev');
				}
			});
			$('[data-ride="carousel"]').swipeleft(function() {
				if ( $body.hasClass('rtl') ) {
					$(this).carousel('prev');
				} else {
					$(this).carousel('next');
				}
			});
		}

		// Deep linking Bootstrap tabs
		if ( themeblvd.tabs_deep_linking == 'true' ) {

			// Note: To deep link to a tab, you need to prefix
			// the ID of the tab with "tab_" like this:
			// http://your-site.com/page-with-tabs/#tab_id_of_tab

			var hash = document.location.hash;

			if ( hash && hash.indexOf('tab_') != -1 ) {
				$('.element-tabs a[href="'+hash.replace('tab_', '')+'"]').tab('show');
			}
		}

		// Tooltips
		$('.desktop .tb-tooltip').tooltip();

	}


	// ---------------------------------------------------------
	// Charts
	// ---------------------------------------------------------

	// Milestone percent
	if ( $.fn.easyPieChart != 'undefined' ) {
		if ( ! $body.hasClass('tb-scroll-effects') || $body.hasClass('mobile') ) {
			$('.tb-milestone-percent').each(function(){

				var $chart = $(this).find('.chart');

				$chart.easyPieChart({
					lineWidth: 6,
					size: 160,
					animate: 1000,
					barColor: $chart.data('color'),
					trackColor: $chart.data('track-color'),
					scaleColor: false,
					lineCap: 'square',
					easing: 'easeOutBounce'
				});
			});
		}
	}

	// Bar, Doughnut, Line, and Bar charts
	if ( typeof Chart !== 'undefined' ) {

		// Global settings
		// Chart.defaults.global.responsive = true;

		// Pie/Doughnut charts
		$('.tb-chart.pie, .tb-chart.doughnut').each(function(){

			var $el = $(this),
				type = 'pie',
				context = $el.find('canvas').get(0).getContext('2d'),
				data = [],
				chart;

			if ( $el.hasClass('doughnut') ) {
				type = 'doughnut';
			}

			$el.find('.data').each(function(){

				var $data = $(this);

				data.push({
					value: $data.data('value'),
					color: $data.data('color'),
					highlight: $data.data('highlight'),
					label: $data.data('label')
				});
			});

			if ( type == 'doughnut' ) {

				chart = new Chart(context).Doughnut(data, {
					showTooltips: $el.data('tooltips')
				});

			} else {

				chart = new Chart(context).Pie(data, {
					showTooltips: $el.data('tooltips')
				});

			}

			// Legend
			if ( $el.data('legend') ) {
				$el.append( '<div class="legend">'+chart.generateLegend()+'</div>' );
			}

			// Scroll effects
			if ( $body.hasClass('tb-scroll-effects') && $body.hasClass('desktop') ) {
				$el.find('.chart-wrap').css('opacity', '0').viewportChecker({
					classToAdd: 'visible',
					offset: 0,
					callbackFunction: function($elem, action){
						$elem.css('opacity', '1');
						chart.render();
					}
				});
			}
		});

		// Bar/Line charts
		$('.tb-chart.bar, .tb-chart.line').each(function(){

			var $el = $(this),
				type = 'line',
				context = $el.find('canvas').get(0).getContext('2d'),
				data = [],
				datasets = [],
				chart;

			if ( $el.hasClass('bar') ) {
				type = 'bar';
			}

			$el.find('.data').each(function(){

				var $data = $(this);

				datasets.push({
					label: $data.data('label'),
					fillColor: $data.data('fill'),
					strokeColor: $data.data('stroke'),
					pointColor: $data.data('point'),
					data: $data.data('values').split(',')
				});

			});

			data = {
				labels: $el.data('labels').split(','),
				datasets: datasets
			};

			if ( type == 'bar' ) {

				chart = new Chart(context).Bar(data, {
					barShowStroke: false,
					scaleBeginAtZero: $el.data('zero'),
					showTooltips: $el.data('tooltips')
				});

			} else {

				chart = new Chart(context).Line(data, {
					bezierCurve: $el.data('curve'),
					datasetFill: $el.data('fill'),
					pointDot: $el.data('dot'),
					scaleBeginAtZero: $el.data('start'),
					showTooltips: $el.data('tooltips')
				});

			}

			// Legend
			if ( $el.data('legend') ) {
				$el.append( '<div class="legend">'+chart.generateLegend()+'</div>' );
			}

			// Scroll effects
			if ( $body.hasClass('tb-scroll-effects') && $body.hasClass('desktop') ) {
				$el.find('.chart-wrap').css('opacity', '0').viewportChecker({
					classToAdd: 'visible',
					offset: 0,
					callbackFunction: function($elem, action){
						$elem.css('opacity', '1');
						chart.render();
					}
				});
			}

		});
	}

	// ---------------------------------------------------------
	// Scroll effects (separate)
	// ---------------------------------------------------------

	// General custom layout sections and elements
	if ( $body.hasClass('tb-scroll-effects') ) {
		$('#custom-main').find('.element-section, .element-section > .element').viewportChecker({
			classToAdd: 'visible',
			offset: 0
		});
	}

	// Milestone standard
	$('.desktop.tb-scroll-effects .tb-milestone .milestone').each(function() {

		var $el = $(this),
			num = parseInt($el.data('num'));

		$el.viewportChecker({
			classToAdd: 'visible',
			offset: 0,
			callbackFunction: function($elem, action){
				$elem.find('.num').countTo({
					from: 0,
					to: num,
					speed: 900,
					refreshInterval: 30
				});
			}
		});

	});

	// Milestone percent
	if( $.fn.easyPieChart != 'undefined' ) {
		$('.desktop.tb-scroll-effects .tb-milestone-percent').each(function(){

			var $chart = $(this).find('.chart');

			$chart.css('opacity', '0').viewportChecker({
				classToAdd: 'visible',
				offset: 0,
				callbackFunction: function($elem, action){
					$elem.easyPieChart({
						lineWidth: 6,
						size: 160,
						animate: 1000,
						barColor: $chart.data('color'),
						trackColor: $chart.data('track-color'),
						scaleColor: false,
						lineCap: 'square',
						easing: 'easeOutBounce',
						onStart: function() {
							$chart.css('opacity', '1');
						}
					});
				}
			});

		});
	}


	// Milestone standard
	$('.desktop.tb-scroll-effects .tb-progress .progress-bar').each(function(){

		$(this).viewportChecker({
			classToAdd: 'visible',
			offset: 0,
			callbackFunction: function($elem, action){
				$elem.animate({width:$elem.data('percent')+'%'}, 400);
			}
		});

	});

	// ---------------------------------------------------------
	// Video Backgrounds
	// ---------------------------------------------------------

	// Self-hosted HTML5
	$('.tb-bg-video.html5').each(function(){

		var $video = $(this);

		$video.find('video').mediaelementplayer({
			startVolume: 0,
			loop: true,
			enableKeyboard: false,
			pauseOtherPlayers: false,
		    success: function( player ) {
	            player.addEventListener('canplay', function() {
					player.play();
					tbmethods.bg_video_size( $video.find('video') );
					$video.addClass('playing');
	            }, false);
		    }
		});

		$window.on('resize', function(){
			tbmethods.bg_video_size( $video.find('video') );
		});

	});

	// YouTube
	if ( $('.tb-bg-video.youtube').length ) {
		$.getScript("https://www.youtube.com/iframe_api", function(){
			if ( typeof YT === "object" ) {
				youtube = setInterval(function(){
			        if ( typeof YT === "object" ) {
			            tbmethods.yt_init();
			            clearInterval(youtube);
			        }
			    },500);
			}
		});
	}

	// Vimeo
	if ( typeof Froogaloop !== 'undefined' ) {
		$('.tb-bg-video.vimeo').each(function(){

			var $el = $(this),
				$iframe = $el.find('iframe'),
				player = $f( $iframe[0] );

			player.addEvent('ready', function(){

				tbmethods.bg_video_size( $iframe );

				$window.on('resize', function(){
					tbmethods.bg_video_size( $iframe );
				});

				player.addEvent('play', function(id){
					$el.addClass('playing');
				});

				player.api('play');
				player.api('setVolume', 0);

			});

		});
	}

});

// ---------------------------------------------------------
// Allow adding a class when item comes into viewport,
// and performing a callback.
// ---------------------------------------------------------

/*
    Version 1.4.2
    The MIT License (MIT)

    Copyright (c) 2014 Dirk Groenen

    Permission is hereby granted, free of charge, to any person obtaining a copy of
    this software and associated documentation files (the "Software"), to deal in
    the Software without restriction, including without limitation the rights to
    use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
    the Software, and to permit persons to whom the Software is furnished to do so,
    subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.
*/

(function($){
    $.fn.viewportChecker = function(useroptions){
        // Define options and extend with user
        var options = {
            classToAdd: 'visible',
            offset: 100,
            repeat: false,
            callbackFunction: function(elem, action){}
        };
        $.extend(options, useroptions);

        // Cache the given element and height of the browser
        var $elem = this,
            windowHeight = $(window).height(),
            scrollElem = ((navigator.userAgent.toLowerCase().indexOf('webkit') != -1) ? 'body' : 'html');

        this.checkElements = function(){

            // Set some vars to check with
            var viewportTop = $(scrollElem).scrollTop(),
                viewportBottom = (viewportTop + windowHeight);

            $elem.each(function(){
                var $obj = $(this);
                // If class already exists; quit
                if ($obj.hasClass(options.classToAdd) && !options.repeat){
                    return;
                }

                // define the top position of the element and include the offset which makes is appear earlier or later
                var elemTop = Math.round( $obj.offset().top ) + options.offset,
                    elemBottom = elemTop + ($obj.height());

                // Add class if in viewport
                if ((elemTop < viewportBottom) && (elemBottom > viewportTop)){
                    $obj.addClass(options.classToAdd);

                    // Do the callback function. Callback wil send the jQuery object as parameter
                    options.callbackFunction($obj, "add");

                // Remove class if not in viewport and repeat is true
                } else if ($obj.hasClass(options.classToAdd) && (options.repeat)){
                    $obj.removeClass(options.classToAdd);

                    // Do the callback function.
                    options.callbackFunction($obj, "remove");
                }
            });

        };

        // Run checkelements on load and scroll
        $(window).bind("load scroll touchmove", this.checkElements);

        // On resize change the height var
        $(window).on('resize', function(e){
            windowHeight = e.currentTarget.innerHeight;
        });

        return this;
    };
})(jQuery);

// ---------------------------------------------------------
// Animation for number counting
// script by Matt Huggins
// ---------------------------------------------------------

(function ($) {
	$.fn.countTo = function (options) {
		options = options || {};

		return $(this).each(function () {
			// set options for current element
			var settings = $.extend({}, $.fn.countTo.defaults, {
				from:            $(this).data('from'),
				to:              $(this).data('to'),
				speed:           $(this).data('speed'),
				refreshInterval: $(this).data('refresh-interval'),
				decimals:        $(this).data('decimals')
			}, options);

			// how many times to update the value, and how much to increment the value on each update
			var loops = Math.ceil(settings.speed / settings.refreshInterval),
				increment = (settings.to - settings.from) / loops;

			// references & variables that will change with each update
			var self = this,
				$self = $(this),
				loopCount = 0,
				value = settings.from,
				data = $self.data('countTo') || {};

			$self.data('countTo', data);

			// if an existing interval can be found, clear it first
			if (data.interval) {
				clearInterval(data.interval);
			}
			data.interval = setInterval(updateTimer, settings.refreshInterval);

			// initialize the element with the starting value
			render(value);

			function updateTimer() {
				value += increment;
				loopCount++;

				render(value);

				if (typeof(settings.onUpdate) == 'function') {
					settings.onUpdate.call(self, value);
				}

				if (loopCount >= loops) {
					// remove the interval
					$self.removeData('countTo');
					clearInterval(data.interval);
					value = settings.to;

					if (typeof(settings.onComplete) == 'function') {
						settings.onComplete.call(self, value);
					}
				}
			}

			function render(value) {
				var formattedValue = settings.formatter.call(self, value, settings);
				$self.text(formattedValue);
			}
		});
	};

	$.fn.countTo.defaults = {
		from: 0,               // the number the element should start at
		to: 0,                 // the number the element should end at
		speed: 1000,           // how long it should take to count between the target numbers
		refreshInterval: 100,  // how often the element should be updated
		decimals: 0,           // the number of decimal places to show
		formatter: formatter,  // handler for formatting the value before rendering
		onUpdate: null,        // callback method for every time the element is updated
		onComplete: null       // callback method for when the element finishes updating
	};

	function formatter(value, settings) {
		return value.toFixed(settings.decimals);
	}
}(jQuery));
