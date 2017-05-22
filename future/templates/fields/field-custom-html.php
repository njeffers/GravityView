<?php
/**
 * The default custom content field output template.
 *
 * @since future
 */
$field_id = $gravityview->field->ID;
$field = $gravityview->field->field;
$value = $gravityview->value;
$form = $gravityview->view->form->form;
$display_value = $gravityview->display_value;
$entry = $gravityview->entry->as_entry();
$field_settings = $gravityview->field->as_configuration();

/** Default to empty. */
if ( empty( $gravityview->field->content ) ) {
	$field_settings['content'] = '';
}

// Make sure the class is loaded in DataTables
if ( ! class_exists( 'GFFormDisplay' ) ) {
	include_once GFCommon::get_base_path() . '/form_display.php';
}

/**
 * @filter `gravityview/fields/custom/content_before` Modify Custom Content field output before Merge Tag processing
 * @since 1.6.2
 * @param string $content HTML content of field
 *
 * @since future
 * @param stdClass The gravityview template context object.
 */
$content = apply_filters( 'gravityview/fields/custom/content_before', $gravityview->field->content, $gravityview );
$content = trim( rtrim( $content ) );

// No custom content
if ( empty( $content ) ) {
	return;
}

// Replace the variables
$content = GravityView_API::replace_variables( $content, $form, $entry );

/**
 * @filter `gravityview/fields/custom/decode_shortcodes` Decode brackets in shortcodes
 * @since 1.16.5
 * @param boolean $decode Enable/Disable decoding of brackets in the content (default: false)
 * @param string $content HTML content of field
 *
 * @since future
 * @param stdClass The gravityview template context object.
 */
if ( apply_filters( 'gravityview/fields/custom/decode_shortcodes', false, $content, $gravityview ) ) {
	$content = GVCommon::decode_shortcodes( $content );
}

// Add paragraphs?
if ( ! empty( $gravityview->field->wpautop ) ) {
	$content = wpautop( $content );
}

/**
 * @filter `gravityview/fields/custom/content_after` Modify Custom Content field output after Merge Tag variables get replaced, before shortcodes get processed
 * @since 1.6.2
 * @param string $content HTML content of field
 *
 * @since future
 * @param stdClass The gravityview template context object.
 */
$content = apply_filters( 'gravityview/fields/custom/content_after', $content, $gravityview );

// Enqueue scripts needed for Gravity Form display, if form shortcode exists.
// Also runs `do_shortcode()`
echo GFCommon::gform_do_shortcode( $content );
