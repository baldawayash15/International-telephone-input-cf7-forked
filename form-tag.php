<?php

add_action( 'wpcf7_init', 'wpcf7_add_formtag_intl_tel' );

function wpcf7_add_formtag_intl_tel() {
	wpcf7_add_form_tag(
		array( 'intl-tel', 'intl-tel*' ),
		'wpcf7_intl_tel_formtag_handler', true);
}

function wpcf7_intl_tel_formtag_handler ( $tag ){
    
    $tag = new WPCF7_FormTag( $tag );

	if ( empty( $tag->name ) )
		return '';
		
	
    wpcf7_intl_tel_add_static_files();
    
	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'wpcf7-intl-tel' );

	if ( $validation_error )
		$class .= ' wpcf7-not-valid';

	$atts = array();
	
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	if ( $tag->has_option( 'readonly' ) ):
		$atts['readonly'] = 'readonly';
	endif;
	if ( $tag->is_required() ):
		$atts['aria-required'] = 'true';
	endif;
	$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

	$placeholder = (string) reset( $tag->values );

	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$atts['placeholder'] = $placeholder;
	}
	if ( $tag->has_option('size') ):
		$atts['size'] = $tag->get_option('size', '', true);
	endif;
	if ( $tag->has_option('preferredCountries') ):
		$atts['data-preferredcountries'] = $tag->get_option('preferredCountries', '', true);
	endif;

	$value = $tag->get_default_option();
	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts['value'] = $value;
	$atts['type'] = 'tel';
	$atts['name'] = $tag->name . '-cf7it-national';

	$atts_hidden=array();
	$atts_hidden['name'] = $tag->name;
	$atts_hidden['type'] = 'hidden';
	$atts_hidden['class'] = 'wpcf7-intl-tel-full';
	
	$atts_country=array();
	$atts_country['type'] = 'hidden';
	$atts_country['name'] = $tag->name . '-cf7it-country-name';
	$atts_country['class'] = 'wpcf7-intl-tel-country-name';
	
	$atts_country_code=array();
	$atts_country_code['type'] = 'hidden';
	$atts_country_code['name'] = $tag->name . '-cf7it-country-code';
	$atts_country_code['class'] = 'wpcf7-intl-tel-country-code';
	
	$atts_country_iso=array();
	$atts_country_iso['type'] = 'hidden';
	$atts_country_iso['name'] = $tag->name . '-cf7it-country-iso2';
	$atts_country_iso['class'] = 'wpcf7-intl-tel-country-iso2';

	$atts_valid_phone=array();
	$atts_valid_phone['type'] = 'hidden';
	$atts_valid_phone['name'] = $tag->name . '-cf7it-valid-phone';
	$atts_valid_phone['class'] = 'wpcf7-intl-tel-valid-phone';

	$atts = wpcf7_format_atts( $atts );
	$atts_hidden = wpcf7_format_atts( $atts_hidden );
	$atts_country = wpcf7_format_atts( $atts_country );
	$atts_country_code = wpcf7_format_atts( $atts_country_code );
	$atts_country_iso = wpcf7_format_atts( $atts_country_iso );
	$atts_valid_phone = wpcf7_format_atts( $atts_valid_phone );

	$html = sprintf(
		'<span class="wpcf7-form-control-wrap %1$s"><input %2$s /><input %3$s /><input %5$s /><input %6$s /><input %7$s /><input %8$s />%4$s</span>',
		sanitize_html_class( $tag->name ), $atts, $atts_hidden, $validation_error, $atts_country, $atts_country_code, $atts_country_iso, $atts_valid_phone);

	return $html;
}

function wpcf7_intl_tel_add_static_files(){
	$extension='.min.js';
	if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
		$extension='.js';
	}
  
	//wp_enqueue_script( 'wpcf7-intl-tel-lib-js', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.3/js/intlTelInput'.$extension ,  array( 'jquery' ), '12.1.3', true);
	 //wp_enqueue_style( 'wpcf7-intl-tel-css', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.3/css/intlTelInput.css', '', '12.1.3', 'all');
	wp_enqueue_script('wpcf7-intl-tel-lib-js', plugins_url('assets/js/intlTelInput-jquery.min.js', __FILE__), array( 'jquery' ), '16.0.0', true);
    wp_enqueue_style('wpcf7-intl-tel-css', plugins_url('assets/css/intlTelInput.min.css', __FILE__),'', '16.0.0', 'all');
    wp_enqueue_script( 'wpcf7-intl-tel-js', plugins_url( 'script.js', __FILE__ ), array('jquery', 'wpcf7-intl-tel-lib-js'), '1.4.0', true);
    wp_localize_script( 'wpcf7-intl-tel-js', 'wpcf7_utils_url', plugins_url('assets/js/utils.js',__FILE__));
} 

function wpcf7_intl_tel_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>