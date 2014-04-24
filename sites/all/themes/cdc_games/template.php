<?php

/**
 * Formats text for emphasized display in a placeholder inside a sentence.
 * Used automatically by t().
 *
 * @param $text
 *   The text to format (plain-text).
 * @return
 *   The formatted text (html).
 */
function cdc_games_placeholder($text) {
  return '<span class="placeholder">'. check_plain($text) .'</span>';
}

function cdc_games_preprocess_page(&$vars, $hook) {

  if (arg(1) == 'move') {

    $vars['head'] .= '<link '. drupal_attributes(array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css')
    ) ." />\n";

  }

}
