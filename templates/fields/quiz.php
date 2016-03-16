<?php
/**
 * Display Gravity Forms Quiz output
 *
 * @package GravityView
 * @subpackage GravityView/templates/fields
 */

$gravityview_view = GravityView_View::getInstance();

$field = $gravityview_view->getCurrentField();

// If there's no grade, don't continue
if( gv_empty( $field['value'] ) ) {
	return;
}

// Get the setting for show/hide explanation
$show_answer = rgars( $field, 'field_settings/quiz_show_explanation' );

// Update the quiz field so GF generates the output properly
$field['field']->gquizShowAnswerExplanation = ! empty( $show_answer );

// Generate the output
echo GFQuiz::get_instance()->display_quiz_on_entry_detail( $field['value'], $field['field'], $field['entry'], $field['form'] );