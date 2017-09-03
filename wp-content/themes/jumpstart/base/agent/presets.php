<?php
/**
 * Get option presets.
 *
 * @since 2.1.1
 */
function jumpstart_ag_get_presets( $preset = '' ) {

	$args = array(
		'id'			=> 'style_presets',
		'tab' 			=> 'styles',
		'section'		=> 'presets',
		'sets'			=> array()
	);

    // Predefined attributes
    $atts = array(
        'id'			=> '',
        'icon'			=> get_template_directory_uri() . '/base/agent/img/',
        'icon_width'	=> '70',
        'icon_height'	=> '40',
        'icon_style'    => 'width: 70px; height: 40px',
        'settings'      => array()
    );

    // Predefined styles
    $shared = array(
        'highlight'                         => '#2ea3f2',
        'header_bg_color_brightness'        => 'dark',
        'menu_drop_bg_color_brightness'     => 'dark',
        'header_mobile_bg_color_brightness' => 'dark',
        'header_trans_bg_color'             => '#000000',
        'header_trans_bg_color_opacity'     => '1',
        'header_trans_bg_color_brightness'  => 'dark',
        'header_trans_hide_border'          => '0',
        'footer_bg_color_brightness'        => 'dark',
        'copyright_bg_color_brightness'     => 'dark',
        'side_bg_color_brightness'          => 'dark',
        'widget_bg'                         => '1',
        'widget_bg_color'                   => '#f8f8f8',
        'widget_bg_color_brightness'        => 'light',
        'searchform'                        => 'show',
        'custom_styles'                     => '',
        'font_menu_sp'                      => '1px',
        'font_menu'                         => array(
            'color'         => '#ffffff',
            'size'			=> '11px',
            'face'			=> 'google',
            'style' 		=> 'uppercase',
            'weight' 		=> '700',
            'google' 		=> 'Lato:700',
            'typekit'		=> '',
            'typekit_kit'	=> ''
        )
    );

    $styles = array(
        'style-1' => wp_parse_args(array(
            'name'              => __('Style 1', 'jumpstart'),
            'menu_placement'    => 'center',
            'header_stretch'    => '1'
        ), $shared),
        'style-2' => wp_parse_args(array(
            'name'              => __('Style 2', 'jumpstart'),
            'menu_placement'    => 'far',
            'header_stretch'    => '1'
        ), $shared),
        'style-3' => wp_parse_args(array(
            'name'              => __('Style 3', 'jumpstart'),
            'menu_placement'    => 'center',
            'header_stretch'    => '0'
        ), $shared),
        'style-4' => wp_parse_args(array(
            'name'              => __('Style 4', 'jumpstart'),
            'menu_placement'    => 'far',
            'header_stretch'    => '0'
        ), $shared)
    );

    // Predefined hex color values
    $hex = apply_filters('jumpstart_ag_preset_colors', array(
        'green'         => array( __('Green', 'jumpstart'),         '#4fba6f', '#4ca165' ),
        'sea-green'     => array( __('Sea Green', 'jumpstart'),     '#18a185', '#268f77' ),
        'blue'          => array( __('Blue', 'jumpstart'),          '#2880ba', '#3174a2' ),
        'midnight-blue' => array( __('Midnight Blue', 'jumpstart'), '#2c3e50', '#1a2836' ),
        'purple'        => array( __('Purple', 'jumpstart'),        '#894b9d', '#7b478b' ),
        'orange'        => array( __('Orange', 'jumpstart'),        '#d15627', '#b85128' ),
        'red'           => array( __('Red', 'jumpstart'),           '#c03b2b', '#a93b2a' ),
        'grey'          => array( __('Grey', 'jumpstart'),          '#7f8c8d', '#727e7e' )
    ));

    // Predefined colors
    $colors = array(
        'dark'  => array(
            'name'                              => __('Dark', 'jumpstart'),
            'btn_color'                         => '#1b1b1b',
            'header_bg_color'                   => '#101010',
            'menu_drop_bg_color'                => '#202020',
            'header_mobile_bg_color'            => '#101010',
            'footer_bg_color'                   => '#222222',
            'copyright_bg_color'                => '#1b1b1b',
            'side_bg_color'                     => '#1b1b1b',
            'link_color'                        => '#2ea3f2',
            'link_hover_color'                  => '#337ab7'
        ),
        'green' => array(
            'name'                              => $hex['green'][0],
            'btn_color'                         => $hex['green'][1],
            'header_bg_color'                   => $hex['green'][1],
            'menu_drop_bg_color'                => $hex['green'][2],
            'header_mobile_bg_color'            => $hex['green'][1],
            'footer_bg_color'                   => $hex['green'][2],
            'copyright_bg_color'                => $hex['green'][1],
            'side_bg_color'                     => $hex['green'][2],
            'link_color'                        => $hex['green'][1],
            'link_hover_color'                  => $hex['green'][2]
        ),
        'sea-green' => array(
            'name'                              => $hex['sea-green'][0],
            'btn_color'                         => $hex['sea-green'][1],
            'header_bg_color'                   => $hex['sea-green'][1],
            'menu_drop_bg_color'                => $hex['sea-green'][2],
            'header_mobile_bg_color'            => $hex['sea-green'][1],
            'footer_bg_color'                   => $hex['sea-green'][2],
            'copyright_bg_color'                => $hex['sea-green'][1],
            'side_bg_color'                     => $hex['sea-green'][2],
            'link_color'                        => $hex['sea-green'][1],
            'link_hover_color'                  => $hex['sea-green'][2]
        ),
        'blue' => array(
            'name'                              => $hex['blue'][0],
            'btn_color'                         => $hex['blue'][1],
            'header_bg_color'                   => $hex['blue'][1],
            'menu_drop_bg_color'                => $hex['blue'][2],
            'header_mobile_bg_color'            => $hex['blue'][1],
            'footer_bg_color'                   => $hex['blue'][2],
            'copyright_bg_color'                => $hex['blue'][1],
            'side_bg_color'                     => $hex['blue'][2],
            'link_color'                        => $hex['blue'][1],
            'link_hover_color'                  => $hex['blue'][2]
        ),
        'midnight-blue' => array(
            'name'                              => $hex['midnight-blue'][0],
            'btn_color'                         => $hex['midnight-blue'][1],
            'header_bg_color'                   => $hex['midnight-blue'][1],
            'menu_drop_bg_color'                => $hex['midnight-blue'][2],
            'header_mobile_bg_color'            => $hex['midnight-blue'][1],
            'footer_bg_color'                   => $hex['midnight-blue'][2],
            'copyright_bg_color'                => $hex['midnight-blue'][1],
            'side_bg_color'                     => $hex['midnight-blue'][2],
            'link_color'                        => $hex['midnight-blue'][1],
            'link_hover_color'                  => $hex['midnight-blue'][2]
        ),
        'purple' => array(
            'name'                              => $hex['purple'][0],
            'btn_color'                         => $hex['purple'][1],
            'header_bg_color'                   => $hex['purple'][1],
            'menu_drop_bg_color'                => $hex['purple'][2],
            'header_mobile_bg_color'            => $hex['purple'][1],
            'footer_bg_color'                   => $hex['purple'][2],
            'copyright_bg_color'                => $hex['purple'][1],
            'side_bg_color'                     => $hex['purple'][2],
            'link_color'                        => $hex['purple'][1],
            'link_hover_color'                  => $hex['purple'][2]
        ),
        'orange' => array(
            'name'                              => $hex['orange'][0],
            'btn_color'                         => $hex['orange'][1],
            'header_bg_color'                   => $hex['orange'][1],
            'menu_drop_bg_color'                => $hex['orange'][2],
            'header_mobile_bg_color'            => $hex['orange'][1],
            'footer_bg_color'                   => $hex['orange'][2],
            'copyright_bg_color'                => $hex['orange'][1],
            'side_bg_color'                     => $hex['orange'][2],
            'link_color'                        => $hex['orange'][1],
            'link_hover_color'                  => $hex['orange'][2]
        ),
        'red' => array(
            'name'                              => $hex['red'][0],
            'btn_color'                         => $hex['red'][1],
            'header_bg_color'                   => $hex['red'][1],
            'menu_drop_bg_color'                => $hex['red'][2],
            'header_mobile_bg_color'            => $hex['red'][1],
            'footer_bg_color'                   => $hex['red'][2],
            'copyright_bg_color'                => $hex['red'][1],
            'side_bg_color'                     => $hex['red'][2],
            'link_color'                        => $hex['red'][1],
            'link_hover_color'                  => $hex['red'][2]
        ),
        'grey' => array(
            'name'                              => $hex['grey'][0],
            'btn_color'                         => $hex['grey'][1],
            'header_bg_color'                   => $hex['grey'][1],
            'menu_drop_bg_color'                => $hex['grey'][2],
            'header_mobile_bg_color'            => $hex['grey'][1],
            'footer_bg_color'                   => $hex['grey'][2],
            'copyright_bg_color'                => $hex['grey'][1],
            'side_bg_color'                     => $hex['grey'][2],
            'link_color'                        => $hex['grey'][1],
            'link_hover_color'                  => $hex['grey'][2],
            'link_color'                        => '#2ea3f2',
            'link_hover_color'                  => '#337ab7'
        )
    );

    // Do we only want return one preset?
    if ( $preset ) {

        $parts = explode('-', $preset);
        $color = $parts[2];

        if ( isset( $parts[3] ) ) {
            $color .= '-' . $parts[3];
        }

        foreach ( $colors as $key => $val ) {
            if ( $key != $color ) {
                unset($colors[$key]);
            }
        }

        foreach ( $styles as $key => $val ) {
            if ( $key != $parts[0] . '-' . $parts[1] ) {
                unset($styles[$key]);
            }
        }

    }

    // Build presets
    foreach ( $styles as $style => $style_settings ) {

        $style_name = $style_settings['name'];
        unset($style_settings['name']);

        foreach ( $colors as $color => $color_settings ) {

            $args['sets'][$style.'-'.$color] = $atts;

            $args['sets'][$style.'-'.$color]['id'] .= $style . '-' . $color;

            $args['sets'][$style.'-'.$color]['name'] = $style_name . ' - ' . $color_settings['name'];
            unset( $color_settings['name'] );

            if ( $color == 'dark' ) {
                $args['sets'][$style.'-'.$color]['icon'] .= 'preset-' . $style . '-' . $color . '.jpg';
                $args['sets'][$style.'-'.$color]['icon_width'] = '100%';
                $args['sets'][$style.'-'.$color]['icon_height'] = '';
                $args['sets'][$style.'-'.$color]['icon_style'] = '';
            } else {
                $args['sets'][$style.'-'.$color]['icon'] .= 'preset-' . $color . '.jpg';
            }

            $args['sets'][$style.'-'.$color]['settings'] = wp_parse_args($color_settings, $style_settings);

        }

    }

	$args = apply_filters('jumpstart_ag_style_presets', $args, $colors);

    if ( $preset && isset( $args['sets'][$preset]['settings'] ) ) {
        $args = $args['sets'][$preset]['settings'];
    }

    return $args;
}
