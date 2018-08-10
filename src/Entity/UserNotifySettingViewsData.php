<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for User notify setting entities.
 */
class UserNotifySettingViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
