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

  /**
   * Gets the settings for Notification switchers.
   *
   * @return array
   *   The settings data.
   */
  public function getNotificationSettings(): array;

  /**
   * Sets the settings for Notification switchers.
   *
   * @param array $data
   *   The settings data.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySettingInterface
   *   The called User notify setting entity.
   */
  public function setNotificationSettings(array $data): UserNotifySettingInterface;

  /**
   * Gets the settings for client customized.
   *
   * @return array
   *   The settings data.
   */
  public function getClientSettings(): array;

  /**
   * Sets the settings for client customized.
   *
   * @param array $data
   *   The settings data.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySettingInterface
   *   The called User notify setting entity.
   */
  public function setClientSettings(array $data): UserNotifySettingInterface;

  /**
   * Gets the User notify setting creation timestamp.
   *
   * @return int
   *   Creation timestamp of the User notify setting.
   */
  public function getCreatedTime(): int;

  /**
   * Sets the User notify setting creation timestamp.
   *
   * @param int $timestamp
   *   The User notify setting creation timestamp.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySettingInterface
   *   The called User notify setting entity.
   */
  public function setCreatedTime(int $timestamp): self;

}
