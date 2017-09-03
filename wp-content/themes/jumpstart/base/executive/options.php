<?php
/**
 * Add theme options to framework.
 *
 * @since 2.0.0
 */
function jumpstart_ex_options() {

	// Background support
	add_theme_support( 'custom-background', array(
		'default-color'	=> 'f9f9f9',
		'default-image'	=> ''
	));

	$bg_types = array();

	if ( function_exists('themeblvd_get_bg_types') ) {
		$bg_types = themeblvd_get_bg_types('section');
	}

	// Theme Options
	$options = apply_filters('jumpstart_ex_options', array(
		'general' => array(
			'sub_group_start_1' => array(
				'id'		=> 'sub_group_start_1',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'layout_style' => array(
				'name' 		=> __('Site Layout Style', 'jumpstart'),
				'desc' 		=> __('Select whether you\'d like the layout of the theme to be boxed or not.', 'jumpstart'),
				'id' 		=> 'layout_style',
				'std' 		=> 'stretch',
				'type' 		=> 'select',
				'options'	=> array(
					'stretch' 	=> __('Stretch', 'jumpstart'),
					'boxed' 	=> __('Boxed', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'layout_shadow_size' => array(
				'id'		=> 'layout_shadow_size',
				'name'		=> __('Layout Shadow Size', 'jumpstart'),
				'desc'		=> __('Select the size of the shadow around the boxed layout. Set to 0px for no shadow.', 'jumpstart'),
				'std'		=> '5px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '20'
				),
				'class'		=> 'receiver receiver-boxed'
			),
			'layout_shadow_opacity' => array(
				'id'		=> 'layout_shadow_opacity',
				'name'		=> __('Layout Shadow Strength', 'jumpstart'),
				'desc'		=> sprintf(__('Select the opacity of the shadow for the boxed layout. The darker %s, the closer to 100%% you want to go.', 'jumpstart'), '<a href="'.esc_url(admin_url('customize.php?autofocus[control]=background_image')).'" target="_blank">'.__('your background', 'jumpstart').'</a>'),
				'std'		=> '0.3',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'receiver  receiver-boxed'
			),
			'layout_border_width' => array(
				'id'		=> 'layout_border_width',
				'name'		=> __('Layout Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the boxed layout. Set to 0px for no border.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '20'
				),
				'class'		=> 'receiver receiver-boxed'
			),
			'layout_border_color' => array(
				'id'		=> 'layout_border_color',
				'name'		=> __('Layout Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border around the boxed layout.', 'jumpstart'),
				'std'		=> '#cccccc',
				'type'		=> 'color',
				'class'		=> 'receiver receiver-boxed'
			),
			'sub_group_start_2' => array(
				'id'		=> 'sub_group_start_2',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide receiver receiver-stretch'
			),
			'apply_content_border' => array(
				'id'		=> 'apply_content_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Content Border', 'jumpstart').'</strong>: '.__('Apply border around content areas.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'content_border_color' => array(
				'id'		=> 'content_border_color',
				'name'		=> __('Content Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border around content areas.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'content_border_width' => array(
				'id'		=> 'content_border_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the border around content areas.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_2' => array(
				'id'		=> 'sub_group_end_2',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_1' => array(
				'id'		=> 'sub_group_end_1',
				'type' 		=> 'subgroup_end'
			),
			'style' =>  array(
				'id'		=> 'style',
				'name' 		=> __('Content Style', 'jumpstart'),
				'desc'		=> __('Select the content style of the site.', 'jumpstart'),
				'std'		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('Light', 'jumpstart'),
					'dark' 	=> __('Dark', 'jumpstart')
				)
			)
		),
		'header_info' => array(
			'sub_group_start_3' => array(
				'id'		=> 'sub_group_start_3',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'header_info' => array(
				'name' 		=> __('Header Info Display', 'jumpstart'),
				'desc' 		=> sprintf(__('Select where you\'d like the header info to display, configured at %s.', 'jumpstart'), '<em>'.__('Theme Options > Layout > Header', 'jumpstart').'</em>'),
				'id' 		=> 'header_info',
				'std' 		=> 'header_addon',
				'type' 		=> 'select',
				'options'	=> array(
					'header_top'	=> __('Top bar above header', 'jumpstart'),
					'header_addon'	=> __('Within header', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'top_bg_color' => array(
				'id'		=> 'top_bg_color',
				'name'		=> __('Top Bar Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the bar that runs across the top of the header.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'receiver receiver-header_top'
			),
			'top_bg_color_opacity' => array(
				'id'		=> 'top_bg_color_opacity',
				'name'		=> __('Top Bar Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the above background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'receiver receiver-header_top'
			),
			'top_text_color' => array(
			    'id'		=> 'top_text_color',
			    'name'		=> __('Top Bar Text Color', 'jumpstart'),
			    'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart'),
			    'std'		=> 'dark',
			    'type'		=> 'select',
			    'options'	=> array(
			        'dark'	=> __('Dark Text', 'jumpstart'),
			        'light'	=> __('Light Text', 'jumpstart')
			    ),
				'class'		=> 'receiver receiver-header_top'
			),
			'sub_group_start_4' => array(
				'id'		=> 'sub_group_start_4',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide receiver receiver-header_top'
			),
			'top_apply_border_bottom' => array(
				'id'		=> 'top_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Bar Bottom Border', 'jumpstart').'</strong>: '.__('Apply bottom border to the top bar of the header.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'top_border_bottom_color' => array(
				'id'		=> 'top_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the bottom border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'top_border_bottom_width' => array(
				'id'		=> 'top_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_4' => array(
				'id'		=> 'sub_group_end_4',
				'type' 		=> 'subgroup_end'
			),
			'top_mini' => array(
				'id'		=> 'top_mini',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Mini Display', 'jumpstart').'</strong>: '.__('Display top bar a bit smaller and more condensed.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'receiver receiver-header_top'
			),
			'sub_group_end_3' => array(
				'id'		=> 'sub_group_end_3',
				'type' 		=> 'subgroup_end'
			)
		),
		'header' => array(
			'sub_group_start_5' => array(
				'id'		=> 'sub_group_start_5',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'header_bg_type' => array(
				'id'		=> 'header_bg_type',
				'name'		=> __('Apply Header Background', 'jumpstart'),
				'desc'		=> __('Select if you\'d like to apply a custom background and how you want to control it.', 'jumpstart'),
				'std'		=> 'color',
				'type'		=> 'select',
				'options'	=> $bg_types,
				'class'		=> 'trigger'
			),
			'header_text_color' => array(
				'id'		=> 'header_text_color',
				'name'		=> __('Text Color', 'jumpstart'),
				'desc'		=> __('If you\'re using a dark background color, select to show light text, and vice versa.', 'jumpstart'),
				'std'		=> 'dark',
				'type'		=> 'select',
				'options'	=> array(
					'dark'	=> __('Dark Text', 'jumpstart'),
					'light'	=> __('Light Text', 'jumpstart')
				)
			),
			'header_bg_color' => array(
				'id'		=> 'header_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture receiver-image'
			),
			'header_bg_color_opacity' => array(
				'id'		=> 'header_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color. Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'header_bg_texture' => array(
				'id'		=> 'header_bg_texture',
				'name'		=> __('Background Texture', 'jumpstart'),
				'desc'		=> __('Select a background texture.', 'jumpstart'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'header_apply_bg_texture_parallax' => array(
				'id'		=> 'header_apply_bg_texture_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background texture.', 'jumpstart'),
				'type'		=> 'checkbox',
				'class'		=> 'hide receiver receiver-texture'
			),
			'sub_group_start_6' => array(
				'id'		=> 'sub_group_start_6',
				'type'		=> 'subgroup_start',
				'class'		=> 'select-parallax hide receiver receiver-image'
			),
			'header_bg_image' => array(
				'id'		=> 'header_bg_image',
				'name'		=> __('Background Image', 'jumpstart'),
				'desc'		=> __('Select a background image.', 'jumpstart'),
				'type'		=> 'background',
				'color'		=> false,
				'parallax'	=> true
			),
			'sub_group_end_6' => array(
				'id'		=> 'sub_group_end_6',
				'type' 		=> 'subgroup_end'
			),
			'header_bg_video' => array(
				'id'		=> 'header_bg_video',
				'name'		=> __('Background Video', 'jumpstart'),
				'desc'		=> __('You can upload a web-video file (mp4, webm, ogv), or input a URL to a video page on YouTube or Vimeo. Your fallback image will display on mobile devices.', 'jumpstart').'<br><br>'.__('Examples:', 'jumpstart').'<br>https://vimeo.com/79048048<br>http://www.youtube.com/watch?v=5guMumPFBag',
				'type'		=> 'background_video',
				'class'		=> 'hide receiver receiver-video'
			),
			'sub_group_start_7' => array(
				'id'		=> 'sub_group_start_7',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-image receiver-slideshow'
			),
			'header_apply_bg_shade' => array(
				'id'		=> 'header_apply_bg_shade',
				'name'		=> null,
				'desc'		=> __('Shade background with transparent color.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_bg_shade_color' => array(
				'id'		=> 'header_bg_shade_color',
				'name'		=> __('Shade Color', 'jumpstart'),
				'desc'		=> __('Select the color you want overlaid on your background.', 'jumpstart'),
				'std'		=> '#000000',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_bg_shade_opacity' => array(
				'id'		=> 'header_bg_shade_opacity',
				'name'		=> __('Shade Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the shade color overlaid on your background.', 'jumpstart'),
				'std'		=> '0.5',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_7' => array(
				'id'		=> 'sub_group_end_7',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_8' => array(
				'id'		=> 'sub_group_start_8',
				'type'		=> 'subgroup_start',
				'class'		=> 'section-bg-slideshow hide receiver receiver-slideshow'
			),
			'header_bg_slideshow' => array(
				'id' 		=> 'header_bg_slideshow',
				'name'		=> __('Slideshow Images', 'jumpstart'),
				'desc'		=> null,
				'type'		=> 'slider'
			),
			'header_bg_slideshow_crop' => array(
				'name' 		=> __('Slideshow Crop Size', 'jumpstart'),
				'desc' 		=> __('Select the crop size to be used for the background slideshow images. Remember that the background images will be stretched to cover the area.', 'jumpstart'),
				'id' 		=> 'header_bg_slideshow_crop',
				'std' 		=> 'full',
				'type' 		=> 'select',
				'select'	=> 'crop'
			),
			'header_apply_bg_slideshow_parallax' => array(
				'id'		=> 'header_apply_bg_slideshow_parallax',
				'name'		=> null,
				'desc'		=> __('Apply parallax scroll effect to background slideshow.', 'jumpstart'),
				'type'		=> 'checkbox',
			),
			'sub_group_end_8' => array(
				'id'		=> 'sub_group_end_8',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_5' => array(
				'id'		=> 'sub_group_end_5',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_9' => array(
				'id'		=> 'sub_group_start_9',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_apply_border_top' => array(
				'id'		=> 'header_apply_border_top',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Border', 'jumpstart').'</strong>: '.__('Apply top border to header.', 'jumpstart'),
				'std'		=> 1,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_border_top_color' => array(
				'id'		=> 'header_border_top_color',
				'name'		=> __('Top Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the top border.', 'jumpstart'),
				'std'		=> '#1e73be',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_border_top_width' => array(
				'id'		=> 'header_border_top_width',
				'name'		=> __('Top Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the top border.', 'jumpstart'),
				'std'		=> '7px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_9' => array(
				'id'		=> 'sub_group_end_9',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_10' => array(
				'id'		=> 'sub_group_start_10',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'header_apply_border_bottom' => array(
				'id'		=> 'header_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'jumpstart').'</strong>: '.__('Apply bottom border to header.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'header_border_bottom_color' => array(
				'id'		=> 'header_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the bottom border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'header_border_bottom_width' => array(
				'id'		=> 'header_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'jumpstart'),
				'std'		=> '5px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_10' => array(
				'id'		=> 'sub_group_end_10',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_11' => array(
			    'id'		=> 'sub_group_start_11',
			    'type' 		=> 'subgroup_start',
			    'class'		=> 'show-hide'
			),
			'header_apply_padding_top' => array(
			    'id'		=> 'header_apply_padding_top',
			    'name'		=> null,
			    'desc'		=> '<strong>'.__('Top Padding', 'jumpstart').':</strong> '.__('Apply custom padding top the top of the header.', 'jumpstart'),
			    'std'		=> 0,
			    'type'		=> 'checkbox',
			    'class'		=> 'trigger'
			),
			'header_padding_top' => array(
			    'id'		=> 'header_padding_top',
			    'name'		=> __('Top Padding', 'jumpstart'),
			    'desc'		=> __('Set the padding on the top of the header.', 'jumpstart'),
			    'std'		=> '20px',
			    'type'		=> 'slide',
			    'options'	=> array(
			        'units'		=> 'px',
			        'min'		=> '0',
			        'max'		=> '600'
			    ),
			    'class'		=> 'hide receiver'
			),
			'sub_group_end_11' => array(
			    'id'		=> 'sub_group_end_11',
			    'type' 		=> 'subgroup_end'
			),
			'sub_group_start_12' => array(
			    'id'		=> 'sub_group_start_12',
			    'type' 		=> 'subgroup_start',
			    'class'		=> 'show-hide'
			),
			'header_apply_padding_bottom' => array(
			    'id'		=> 'header_apply_padding_bottom',
			    'name'		=> null,
			    'desc'		=> '<strong>'.__('Bottom Padding', 'jumpstart').':</strong> '.__('Apply custom padding bottom the bottom of the header.', 'jumpstart'),
			    'std'		=> 0,
			    'type'		=> 'checkbox',
			    'class'		=> 'trigger'
			),
			'header_padding_bottom' => array(
			    'id'		=> 'header_padding_bottom',
			    'name'		=> __('Bottom Padding', 'jumpstart'),
			    'desc'		=> __('Set the padding on the bottom of the header.', 'jumpstart'),
			    'std'		=> '20px',
			    'type'		=> 'slide',
			    'options'	=> array(
			        'units'		=> 'px',
			        'min'		=> '0',
			        'max'		=> '600'
			    ),
			    'class'		=> 'hide receiver'
			),
			'sub_group_end_12' => array(
			    'id'		=> 'sub_group_end_12',
			    'type' 		=> 'subgroup_end'
			),
			'logo_center' => array(
				'id'		=> 'logo_center',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Center Logo', 'jumpstart').'</strong>: '.__('Center align the logo within the header.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			)
		),
		'header_mobile' => array(
			'header_mobile_bg_color' => array(
				'id'		=> 'header_mobile_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the mobile header.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color'
			),
			'header_mobile_bg_color_brightness' => array(
				'id' 		=> 'header_mobile_bg_color_brightness',
				'name' 		=> __('Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			)
		),
		'menu' => array(
			'sub_group_start_13' => array(
				'id'		=> 'sub_group_start_13',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'menu_bg_type' => array(
				'id'		=> 'menu_bg_type',
				'name'		=> __('Main Menu Background', 'jumpstart'),
				'desc'		=> __('Select if you\'d like to apply a custom background and how you want to control it.', 'jumpstart'),
				'std'		=> 'color',
				'type'		=> 'select',
				'options'	=> array(
					'color'				=> __('Custom color', 'jumpstart'),
					'glassy'			=> __('Custom color + glassy overlay', 'jumpstart'),
					'textured'			=> __('Custom color + noisy texture', 'jumpstart'),
					'gradient'			=> __('Custom gradient', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'menu_bg_color' => array(
				'id'		=> 'menu_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the main menu.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-glassy receiver-textured'
			),
			'menu_bg_gradient' => array(
				'id'		=> 'menu_bg_gradient',
				'name'		=> __('Background Gradient', 'jumpstart'),
				'desc'		=> __('Select two colors to create a gradient with for the main menu.', 'jumpstart'),
				'std'		=> array('start' => '#3c3c3c', 'end' => '#2b2b2b'),
				'type'		=> 'gradient',
				'class'		=> 'hide receiver receiver-gradient receiver-gradient_glassy'
			),
			'menu_bg_color_opacity' => array(
				'id'		=> 'menu_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color(s). Selecting "100%" means that the background color is not transparent, at all.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-color receiver-glassy receiver-textured receiver-gradient'
			),
			'menu_bg_color_brightness' => array(
				'id' 		=> 'menu_bg_color_brightness',
				'name' 		=> __('Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				),
				'class'		=> 'hide receiver receiver-color receiver-glassy receiver-textured receiver-gradient receiver-gradient_glassy'
			),
			'sub_group_end_13' => array(
				'id'		=> 'sub_group_end_13',
				'type' 		=> 'subgroup_end'
			),
			'menu_hover_bg_color' => array(
				'id'		=> 'menu_hover_bg_color',
				'name'		=> __('Button Hover Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for when buttons of the main are hovered on.', 'jumpstart'),
				'std'		=> '#000000',
				'type'		=> 'color'
			),
			'menu_hover_bg_color_opacity' => array(
				'id'		=> 'menu_hover_bg_color_opacity',
				'name'		=> __('Button Hover Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the color you selected in the previous option.', 'jumpstart'),
				'std'		=> '0.3',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				)
			),
			'menu_hover_bg_color_brightness' => array(
				'id' 		=> 'menu_hover_bg_color_brightness',
				'name' 		=> __('Button Hover Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			),
			'menu_sub_bg_color' => array(
				'id'		=> 'menu_sub_bg_color',
				'name'		=> __('Dropdown Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the main menu\'s drop down menus.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color'
			),
			'menu_sub_bg_color_brightness' => array(
				'id' 		=> 'menu_sub_bg_color_brightness',
				'name' 		=> __('Dropdown Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			),
			'menu_corners' => array(
				'id'		=> 'menu_corners',
				'name'		=> __('Menu Corners', 'jumpstart'),
				'desc'		=> __('Set the border radius of menu corners. Setting to 0px will mean the menu corners are square.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '50'
				)
			),
			'sub_group_start_14' => array(
				'id'		=> 'sub_group_start_14',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'menu_apply_border' => array(
				'id'		=> 'menu_apply_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Border', 'jumpstart').'</strong>: '.__('Apply border around menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'menu_border_color' => array(
				'id'		=> 'menu_border_color',
				'name'		=> __('Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border.', 'jumpstart'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'menu_border_width' => array(
				'id'		=> 'menu_border_width',
				'name'		=> __('Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_14' => array(
				'id'		=> 'sub_group_end_14',
				'type' 		=> 'subgroup_end'
			),
			'menu_text_shadow' => array(
				'id'		=> 'menu_text_shadow',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Text Shadow', 'jumpstart').'</strong>: '.__('Apply shadow to the text of the main menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			),
			'sub_group_start_15' => array(
			    'id'		=> 'sub_group_start_15',
			    'type' 		=> 'subgroup_start',
			    'class'		=> 'show-hide'
			),
			'menu_divider' => array(
			    'id'		=> 'menu_divider',
			    'name'		=> null,
			    'desc'		=> '<strong>'.__('Dividers', 'jumpstart').'</strong>: '.__('Add dividers between buttons of main menu.', 'jumpstart'),
			    'std'		=> 0,
			    'type'		=> 'checkbox',
			    'class'		=> 'trigger'
			),
			'menu_divider_color' => array(
			    'id'		=> 'menu_divider_color',
			    'name'		=> __('Divider Color', 'jumpstart'),
			    'desc'		=> __('Select a color for the menu dividers.', 'jumpstart'),
			    'std'		=> '#000000',
			    'type'		=> 'color',
			    'class'		=> 'hide receiver'
			),
			'sub_group_end_15' => array(
			    'id'		=> 'sub_group_end_15',
			    'type' 		=> 'subgroup_end'
			),
			'menu_center' => array(
				'id'		=> 'menu_center',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Center', 'jumpstart').'</strong>: '.__('Center the main menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			),
			'menu_search' => array(
				'id'		=> 'menu_search',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Search Bar', 'jumpstart').'</strong>: '.__('Add popup with search bar to main menu.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox'
			)
		),
		'menu_mobile' => array(
			'menu_mobile_bg_color' => array(
				'id'		=> 'menu_mobile_bg_color',
				'name'		=> __('Mobile Menu Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the mobile menu side panel.', 'jumpstart'),
				'std'		=> '#222222',
				'type'		=> 'color'
			),
			'menu_mobile_bg_color_brightness' => array(
				'id' 		=> 'menu_mobile_bg_color_brightness',
				'name' 		=> __('Mobile Menu Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			)
		),
		'side_panel' => array(
			'side_info' => array(
				'id'		=> 'side_info',
				'desc'		=> sprintf(__('These options apply to the side panel that shows on desktops when you assign a menu to the "Primary Side Navigation" or "Secondary Side Navigation" locations at %s.', 'jumpstart'), '<a href="nav-menus.php" target="_blank">'.__('Appearance > Menus', 'jumpstart').'</a>' ),
				'type'		=> 'info'
			),
			'side_bg_color' => array(
				'id'		=> 'side_bg_color',
				'name'		=> __('Side Panel Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the desktop side panel.', 'jumpstart'),
				'std'		=> '#222222',
				'type'		=> 'color'
			),
			'side_bg_color_brightness' => array(
				'id' 		=> 'side_bg_color_brightness',
				'name' 		=> __('Side Panel Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				)
			)
		),
		'footer' => array(
			'sub_group_start_16' => array(
				'id'		=> 'sub_group_start_16',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'footer_bg_type' => array(
				'id'		=> 'footer_bg_type',
				'name'		=> __('Apply Footer Background', 'jumpstart'),
				'desc'		=> __('Select if you\'d like to apply a custom background color to the footer.', 'jumpstart').'<br><br>'.sprintf(__('Note: To setup a more complex designed footer, go to %s and use the "Template Sync" feature.', 'jumpstart'), '<em>'.__('Layout > Footer', 'jumpstart').'</em>'),
				'std'		=> 'none',
				'type'		=> 'select',
				'options'	=> array(
					'none'		=> __('None', 'jumpstart'),
					'color'		=> __('Custom color', 'jumpstart'),
					'texture'	=> __('Custom color + texture', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'footer_bg_texture' => array(
				'id'		=> 'footer_bg_texture',
				'name'		=> __('Background Texture', 'jumpstart'),
				'desc'		=> __('Select a background texture.', 'jumpstart'),
				'type'		=> 'select',
				'select'	=> 'textures',
				'class'		=> 'hide receiver receiver-texture'
			),
			'footer_bg_color' => array(
				'id'		=> 'footer_bg_color',
				'name'		=> __('Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for the footer.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'footer_bg_color_brightness' => array(
				'id' 		=> 'footer_bg_color_brightness',
				'name' 		=> __('Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'std' 		=> 'dark',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'footer_bg_color_opacity' => array(
				'id'		=> 'footer_bg_color_opacity',
				'name'		=> __('Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color you chose.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-color receiver-texture'
			),
			'sub_group_end_16' => array(
				'id'		=> 'sub_group_end_16',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_17' => array(
				'id'		=> 'sub_group_start_17',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'footer_apply_border_top' => array(
				'id'		=> 'footer_apply_border_top',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Top Border', 'jumpstart').'</strong>: '.__('Apply top border to footer.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'footer_border_top_color' => array(
				'id'		=> 'footer_border_top_color',
				'name'		=> __('Top Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the top border.', 'jumpstart'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'footer_border_top_width' => array(
				'id'		=> 'footer_border_top_width',
				'name'		=> __('Top Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the top border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_17' => array(
				'id'		=> 'sub_group_end_17',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_start_19' => array(
				'id'		=> 'sub_group_start_19',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide'
			),
			'footer_apply_border_bottom' => array(
				'id'		=> 'footer_apply_border_bottom',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Bottom Border', 'jumpstart').'</strong>: '.__('Apply bottom border to footer.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'footer_border_bottom_color' => array(
				'id'		=> 'footer_border_bottom_color',
				'name'		=> __('Bottom Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the bottom border.', 'jumpstart'),
				'std'		=> '#181818',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'footer_border_bottom_width' => array(
				'id'		=> 'footer_border_bottom_width',
				'name'		=> __('Bottom Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the bottom border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_19' => array(
				'id'		=> 'sub_group_end_19',
				'type' 		=> 'subgroup_end'
			)
		),
		'typo' => array(
			'font_body' => array(
				'id' 		=> 'font_body',
				'name' 		=> __('Primary Font', 'jumpstart'),
				'desc' 		=> __('This applies to most of the text on your site.', 'jumpstart'),
				'std' 		=> array('size' => '16px', 'face' => 'google', 'weight' => '300', 'color' => '', 'google' => 'Raleway:300', 'style' => 'normal'),
				'atts'		=> array('size', 'face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'font_header' => array(
				'id' 		=> 'font_header',
				'name' 		=> __('Header Font', 'jumpstart'),
				'desc' 		=> __('This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', 'jumpstart'),
				'std' 		=> array('size' => '', 'face' => 'google', 'weight' => '400', 'color' => '', 'google' => 'Montserrat:400', 'style' => 'normal'),
				'atts'		=> array('face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'font_quote' => array(
				'id' 		=> 'font_quote',
				'name' 		=> __('Quote Font', 'jumpstart'),
				'desc' 		=> __('This applies to quoted text in blockquote tags.', 'jumpstart'),
				'std' 		=> array('size' => '', 'face' => 'google', 'weight' => '400', 'color' => '', 'google' => 'Libre Baskerville:400italic', 'style' => 'italic'),
				'atts'		=> array('face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'font_quote_sp' => array(
				'id' 		=> 'font_quote_sp',
				'name'		=> __('Quote Letter Spacing', 'jumpstart'),
				'desc'		=> __('Adjust the spacing between letters.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'	=> 'px',
					'min'	=> '0',
					'max'	=> '5',
					'step'	=> '1'
				)
			),
			'font_meta' => array(
				'id' 		=> 'font_meta',
				'name' 		=> __('Meta Info Font', 'jumpstart'),
				'desc' 		=> __('This applies to meta info like the "Posted" date below a post title, for example.', 'jumpstart'),
				'std' 		=> array('size' => '', 'face' => 'google', 'weight' => '400', 'color' => '', 'google' => 'Montserrat:400', 'style' => 'uppercase'),
				'atts'		=> array('face', 'style', 'weight'),
				'type' 		=> 'typography'
			),
			'font_meta_sp' => array(
				'id' 		=> 'font_meta_sp',
				'name'		=> __('Meta Info Letter Spacing', 'jumpstart'),
				'desc'		=> __('Adjust the spacing between letters.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'	=> 'px',
					'min'	=> '0',
					'max'	=> '5',
					'step'	=> '1'
				)
			),
			'font_epic' => array(
				'id' 		=> 'font_epic',
				'name' 		=> __('Featured Image Title Font', 'jumpstart'),
				'desc' 		=> __('This applies when displaying a title on top of featured images.', 'jumpstart'),
				'std' 		=> array('size' => '50px', 'face' => 'google', 'weight' => '700', 'color' => '', 'google' => 'Montserrat:700', 'style' => 'uppercase'),
				'atts'		=> array('face', 'style', 'weight', 'size'),
				'sizes'		=> array('25', '26', '150'),
				'type' 		=> 'typography'
			),
			'font_epic_sp' => array(
				'id' 		=> 'font_epic_sp',
				'name'		=> __('Featured Image Title Letter Spacing', 'jumpstart'),
				'desc'		=> __('Adjust the spacing between letters.', 'jumpstart'),
				'std'		=> '3px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'	=> 'px',
					'min'	=> '0',
					'max'	=> '5',
					'step'	=> '1'
				)
			),
			'font_menu' => array(
				'id' 		=> 'font_menu',
				'name' 		=> __('Main Menu Font', 'jumpstart'),
				'desc' 		=> __('This font applies to the top level items of the main menu.', 'jumpstart'),
				'std' 		=> array('size' => '13px', 'face' => 'google', 'weight' => '300', 'google' => 'Raleway:300', 'style' => 'normal'),
				'atts'		=> array('size', 'face', 'style', 'weight'),
				'type' 		=> 'typography',
				'sizes'		=> array('10', '11', '12', '13', '14', '15', '16', '17', '18')
			),
			'font_menu_sp' => array(
				'id' 		=> 'font_menu_sp',
				'name'		=> __('Main Menu Letter Spacing', 'jumpstart'),
				'desc'		=> __('Adjust the spacing between letters.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'	=> 'px',
					'min'	=> '0',
					'max'	=> '5',
					'step'	=> '1'
				)
			),
			'link_color' => array(
				'id' 		=> 'link_color',
				'name' 		=> __('Link Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links.', 'jumpstart'),
				'std' 		=> '#f9bc18',
				'type' 		=> 'color'
			),
			'link_hover_color' => array(
				'id' 		=> 'link_hover_color',
				'name' 		=> __('Link Hover Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links when they are hovered over.', 'jumpstart'),
				'std' 		=> '#f9d718',
				'type' 		=> 'color'
			),
			'footer_link_color' => array(
				'id' 		=> 'footer_link_color',
				'name' 		=> __('Footer Link Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links in the footer.', 'jumpstart'),
				'std' 		=> '#f9bc18',
				'type' 		=> 'color'
			),
			'footer_link_hover_color' => array(
				'id' 		=> 'footer_link_hover_color',
				'name' 		=> __('Footer Link Hover Color', 'jumpstart'),
				'desc' 		=> __('Choose the color you\'d like applied to links in the footer when they are hovered over.', 'jumpstart'),
				'std' 		=> '#f9d718',
				'type' 		=> 'color'
			)
		),
		'buttons' => array(
			'btn_default' => array(
				'id' 		=> 'btn_default',
				'name'		=> __('Default Buttons', 'jumpstart'),
				'desc'		=> __('Configure what a default button looks like.', 'jumpstart'),
				'std'		=> array(
					'bg' 				=> '#333333',
					'bg_hover'			=> '#222222',
					'border' 			=> '#000000',
					'text'				=> '#ffffff',
					'text_hover'		=> '#ffffff',
					'include_bg'		=> 1,
					'include_border'	=> 0
				),
				'type'		=> 'button'
			),
			'btn_primary' => array(
				'id' 		=> 'btn_primary',
				'name'		=> __('Primary Buttons', 'jumpstart'),
				'desc'		=> __('Configure what a primary button looks like.', 'jumpstart'),
				'std'		=> array(
					'bg' 				=> '#333333',
					'bg_hover'			=> '#222222',
					'border' 			=> '#000000',
					'text'				=> '#ffffff',
					'text_hover'		=> '#ffffff',
					'include_bg'		=> 1,
					'include_border'	=> 0
				),
				'type'		=> 'button'
			),
			'btn_border' => array(
				'id'		=> 'btn_border',
				'name'		=> __('General Button Border Width', 'jumpstart'),
				'desc'		=> __('If your buttons are set to include a border, select a width in pixels for those borders.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '5'
				)
			),
			'btn_corners' => array(
				'id'		=> 'btn_corners',
				'name'		=> __('General Button Corners', 'jumpstart'),
				'desc'		=> __('Set the border radius of button corners. Setting to 0px will mean buttons corners are square.', 'jumpstart'),
				'std'		=> '0px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '0',
					'max'		=> '50'
				)
			)
		),
		'widgets' => array(
			'sub_group_start_18' => array(
				'id'		=> 'sub_group_start_18',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle'
			),
			'widget_style' =>  array(
				'id'		=> 'widget_style',
				'name' 		=> __('Widget Style', 'jumpstart'),
				'desc'		=> __('Select how you want to style your widgets.', 'jumpstart').' <a href="http://getbootstrap.com/components/#panels" target="_blank">'.__('What\'s a Bootstrap panel?', 'jumpstart').'</a>',
				'std'		=> 'standard',
				'type' 		=> 'select',
				'options'	=> array(
					'standard'	=> __('Standard', 'jumpstart'),
					'panel'		=> __('Bootstrap Panel', 'jumpstart')
				),
				'class'		=> 'trigger'
			),
			'sub_group_start_23' => array(
				'id'		=> 'sub_group_start_23',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide-toggle hide receiver receiver-panel'
			),
			'widget_panel_style' => array(
				'name' 		=> __('Panel Style', 'jumpstart'),
				'desc' 		=> __('Select a style for the Bootstrap panel. You can use a preset style, or setup custom colors.', 'jumpstart'),
				'id' 		=> 'widget_panel_style',
				'std' 		=> 'default',
				'type' 		=> 'select',
				'options'	=> array(
					'custom'	=> __('Custom Style', 'jumpstart'),
					'default'	=> __('Bootstrap: Default', 'jumpstart'),
					'primary'	=> __('Bootstrap: Primary', 'jumpstart'),
					'info'		=> __('Bootstrap: Info (blue)', 'jumpstart'),
					'warning'	=> __('Bootstrap: Warning (yellow)', 'jumpstart'),
					'danger'	=> __('Bootstrap: Danger (red)', 'jumpstart')

				),
				'class'		=> 'trigger'
			),
			'widget_panel_title_bg_color' => array(
				'id'		=> 'widget_panel_title_bg_color',
				'name'		=> __('Panel Title Background', 'jumpstart'),
				'desc'		=> __('Select two colors to create a background gradient for widget titles. For a solid color, simply select the same color twice.', 'jumpstart'),
				'std'		=> array('start' => '#f5f5f5', 'end' => '#e8e8e8'),
				'type'		=> 'gradient',
				'class'		=> 'hide receiver receiver-custom'
			),
			'widget_panel_border_color' => array(
				'id'		=> 'widget_panel_border_color',
				'name'		=> __('Panel Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-custom'
			),
			'sub_group_end_23' => array(
				'id'		=> 'sub_group_end_23',
				'type' 		=> 'subgroup_end'
			),
			'widget_bg_color' => array(
				'id'		=> 'widget_bg_color',
				'name'		=> __('Widget Background Color', 'jumpstart'),
				'desc'		=> __('Select a background color for widgets.', 'jumpstart'),
				'std'		=> '#ffffff',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_bg_brightness' => array(
				'name' 		=> __('Widget Background Color Brightness', 'jumpstart'),
				'desc' 		=> __('In the previous option, did you go dark or light?', 'jumpstart'),
				'id' 		=> 'widget_bg_brightness',
				'std' 		=> 'light',
				'type' 		=> 'select',
				'options'	=> array(
					'light' => __('I chose a light color in the previous option.', 'jumpstart'),
					'dark' 	=> __('I chose a dark color in the previous option.', 'jumpstart')
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_bg_color_opacity' => array(
				'id'		=> 'widget_bg_color_opacity',
				'name'		=> __('Widget Background Color Opacity', 'jumpstart'),
				'desc'		=> __('Select the opacity of the background color you chose.', 'jumpstart'),
				'std'		=> '1',
				'type'		=> 'select',
				'options'	=> array(
					'0.05'	=> '5%',
					'0.1'	=> '10%',
					'0.15'	=> '15%',
					'0.2'	=> '20%',
					'0.25'	=> '25%',
					'0.3'	=> '30%',
					'0.35'	=> '35%',
					'0.4'	=> '40%',
					'0.45'	=> '45%',
					'0.5'	=> '50%',
					'0.55'	=> '55%',
					'0.6'	=> '60%',
					'0.65'	=> '65%',
					'0.7'	=> '70%',
					'0.75'	=> '75%',
					'0.8'	=> '80%',
					'0.85'	=> '85%',
					'0.9'	=> '90%',
					'0.95'	=> '95%',
					'1'		=> '100%'
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_color' => array(
				'id'		=> 'widget_title_color',
				'name'		=> __('Widget Title Text Color', 'jumpstart'),
				'desc'		=> __('Select the text color for titles of widgets.', 'jumpstart'),
				'std'		=> '#333333',
				'type'		=> 'color',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_size' => array(
				'id'		=> 'widget_title_size',
				'name'		=> __('Widget Title Text Size', 'jumpstart'),
				'desc'		=> __('Select the text size for titles of widgets.', 'jumpstart'),
				'std'		=> '18px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '10',
					'max'		=> '30'
				),
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'widget_title_shadow' => array(
				'id'		=> 'widget_title_shadow',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Widget Title Text Shadow', 'jumpstart').'</strong>: '.__('Apply shadow to widget title text.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'hide receiver receiver-standard receiver-panel'
			),
			'sub_group_start_20' => array(
				'id'		=> 'sub_group_start_20',
				'type' 		=> 'subgroup_start',
				'class'		=> 'show-hide hide receiver receiver-standard'
			),
			'widget_apply_border' => array(
				'id'		=> 'widget_apply_border',
				'name'		=> null,
				'desc'		=> '<strong>'.__('Widget Border', 'jumpstart').'</strong>: '.__('Apply border around widgets.', 'jumpstart'),
				'std'		=> 0,
				'type'		=> 'checkbox',
				'class'		=> 'trigger'
			),
			'widget_border_color' => array(
				'id'		=> 'widget_border_color',
				'name'		=> __('Border Color', 'jumpstart'),
				'desc'		=> __('Select a color for the border.', 'jumpstart'),
				'std'		=> '#f2f2f2',
				'type'		=> 'color',
				'class'		=> 'hide receiver'
			),
			'widget_border_width' => array(
				'id'		=> 'widget_border_width',
				'name'		=> __('Border Width', 'jumpstart'),
				'desc'		=> __('Select a width in pixels for the border.', 'jumpstart'),
				'std'		=> '1px',
				'type'		=> 'slide',
				'options'	=> array(
					'units'		=> 'px',
					'min'		=> '1',
					'max'		=> '10'
				),
				'class'		=> 'hide receiver'
			),
			'sub_group_end_20' => array(
				'id'		=> 'sub_group_end_20',
				'type' 		=> 'subgroup_end'
			),
			'sub_group_end_18' => array(
				'id'		=> 'sub_group_end_18',
				'type' 		=> 'subgroup_end'
			)
		),
		'extras' => array(
			'highlight' => array(
				'id'		=> 'highlight',
				'name' 		=> __('Highlight Color', 'jumpstart'),
				'desc'		=> __('Select a Highlight color to be used in a few little areas throughout your site.', 'jumpstart'),
				'std'		=> '#fec527',
				'type' 		=> 'color'
			),
			'box_titles' => array(
				'id'		=> 'box_titles',
				'name' 		=> null,
				'desc'		=> __('Display special styling to titles of info boxes and standard widgets.', 'jumpstart'),
				'std'		=> '1',
				'type' 		=> 'checkbox'
			),
			'thumbnail_circles' => array(
				'id'		=> 'thumbnail_circles',
				'name' 		=> null,
				'desc'		=> __('Display avatars and small featured images as circles', 'jumpstart'),
				'std'		=> '1',
				'type' 		=> 'checkbox'
			)
		),
		'css' => array(
			'custom_styles' => array(
				'id'		=> 'custom_styles',
				'name' 		=> null,
				'desc'		=> null,
				'std'		=> '',
				'type' 		=> 'code',
				'lang'		=> 'css'
			)
		)
	));

	themeblvd_add_option_tab( 'styles', __('Styles', 'jumpstart'), true );
	themeblvd_add_option_section( 'styles', 'presets', __('Preset Styles', 'jumpstart'), __('For a quick starting point, click any image below to merge its preset settings into your current option selections. Then, you can continue editing individual options.', 'jumpstart') . ' &mdash; ' . sprintf(__('Looking for more theme style variations? Try a different %s.', 'jumpstart'), '<a href="themes.php?page=jumpstart-base" target="_blank">Theme Base</a>' ), array() );

	if ( is_admin() ) {
		themeblvd_add_option_presets( jumpstart_ex_get_presets() );
	}

	themeblvd_add_option_section( 'styles', 'ex_general',		__('General', 'jumpstart'), 		null, $options['general'] );
	themeblvd_add_option_section( 'styles', 'ex_header_info',	__('Header Info', 'jumpstart'), 	null, $options['header_info'] );
	themeblvd_add_option_section( 'styles', 'ex_header',		__('Header', 'jumpstart'),			null, $options['header'] );
	themeblvd_add_option_section( 'styles', 'ex_header_mobile',	__('Mobile Header', 'jumpstart'),	null, $options['header_mobile'] );
	themeblvd_add_option_section( 'styles', 'ex_menu',			__('Main Menu', 'jumpstart'),		null, $options['menu'] );
	themeblvd_add_option_section( 'styles', 'ex_menu_mobile',	__('Mobile Menu', 'jumpstart'),		null, $options['menu_mobile'] );
	themeblvd_add_option_section( 'styles', 'ex_side_panel',	__('Side Panel', 'jumpstart'),		null, $options['side_panel'] );
	themeblvd_add_option_section( 'styles', 'ex_footer',		__('Footer', 'jumpstart'),			null, $options['footer'] );
	themeblvd_add_option_section( 'styles', 'ex_typo',			__('Typography', 'jumpstart'), 		null, $options['typo'] );
	themeblvd_add_option_section( 'styles', 'ex_buttons',		__('Buttons', 'jumpstart'),			null, $options['buttons'] );
	themeblvd_add_option_section( 'styles', 'ex_widgets',		__('Sidebar Widgets', 'jumpstart'),	null, $options['widgets'] );
	themeblvd_add_option_section( 'styles', 'ex_extras',		__('Extras', 'jumpstart'), 			null, $options['extras'] );
	themeblvd_add_option_section( 'styles', 'ex_css',			__('Custom CSS', 'jumpstart'), 		null, $options['css'] );

}
add_action('after_setup_theme', 'jumpstart_ex_options');
