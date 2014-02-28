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
