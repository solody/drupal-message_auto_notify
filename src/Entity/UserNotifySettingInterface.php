<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining User notify setting entities.
 *
 * @ingroup message_auto_notify
 */
interface UserNotifySettingInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the User notify setting data.
   *
   * @return array
   *   Data of the User notify setting.
   */
  public function getData();

  /**
   * Sets the User notify setting data.
   *
   * @param array $data
   *   The User notify setting data.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySettingInterface
   *   The called User notify setting entity.
   */
  public function setData($data);

  /**
   * Gets the User notify setting creation timestamp.
   *
   * @return int
   *   Creation timestamp of the User notify setting.
   */
  public function getCreatedTime();

  /**
   * Sets the User notify setting creation timestamp.
   *
   * @param int $timestamp
   *   The User notify setting creation timestamp.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySettingInterface
   *   The called User notify setting entity.
   */
  public function setCreatedTime($timestamp);

}
