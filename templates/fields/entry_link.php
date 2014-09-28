<?php

global $gravityview_view;

extract( $gravityview_view->field_data );

// Don't show on single entry
if( $gravityview_view->context === 'single' ) { return; }

$link_text = empty( $field_settings['entry_link_text'] ) ? __('View Details', 'gravity-view') : $field_settings['entry_link_text'];

$output = apply_filters( 'gravityview_entry_link', GravityView_API::replace_variables( $link_text, $form, $entry ) );

$href = GravityView_API::entry_link( $entry, $field );
$output = '<a href="'. $href .'">'. $output . '</a>';

echo $output;
