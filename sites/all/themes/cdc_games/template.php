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

    $vars['styles'] .= '<link '. drupal_attributes(array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css')
    ) ." />\n";

    $vars['styles'] .= '<link '. drupal_attributes(array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' =>
        'http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.css')
    ) ." />\n";

    $vars['styles'] .= '<link '. drupal_attributes(array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' =>
        'http://leaflet.github.io/Leaflet.markercluster/dist/MarkerCluster.Default.css')
    ) ." />\n";

    $vars['scripts'] .= '<script '. drupal_attributes(array(
      'src' => 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js')
    ) ."></script>\n";

    $vars['scripts'] .= '<script '. drupal_attributes(array(
      'src' =>
              'http://leaflet.github.io/Leaflet.markercluster/dist/leaflet.markercluster.js')
    ) ."></script>\n";

  }

}
