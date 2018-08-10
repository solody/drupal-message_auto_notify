<?php

/**
 * @file
 * Contains user_notify_setting.page.inc.
 *
 * Page callback for User notify setting entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for User notify setting templates.
 *
 * Default template: user_notify_setting.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_user_notify_setting(array &$variables) {
  // Fetch UserNotifySetting Entity Object.
  $user_notify_setting = $variables['elements']['#user_notify_setting'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
