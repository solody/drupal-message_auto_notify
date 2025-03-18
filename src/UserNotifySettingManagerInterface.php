<?php

namespace Drupal\message_auto_notify;

use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * Interface of UserNotifySettingManager.
 */
interface UserNotifySettingManagerInterface {

  /**
   * Get notification settings by uid.
   *
   * @param int $uid
   *   The user id.
   *
   * @return array
   *   The notification settings of the given user.
   */
  public function getNotificationSettings(int $uid): array;

  /**
   * Modify notification settings by uid.
   *
   * @param int $uid
   *   The user id.
   * @param array $data
   *   The new setting data to apply.
   *
   * @return array
   *   The new notification settings saved.
   */
  public function modifyNotificationSettings(int $uid, array $data): array;

  /**
   * Get client settings by uid.
   *
   * @param int $uid
   *   The user id.
   *
   * @return array
   *   The client settings of the given user.
   */
  public function getClientSettings(int $uid): array;

  /**
   * Modify client settings by uid.
   *
   * @param int $uid
   *   The user id.
   * @param array $data
   *   The new setting data to apply.
   *
   * @return array
   *   The new client settings saved.
   */
  public function modifyClientSettings(int $uid, array $data): array;

  /**
   * Load user setting entity by uid.
   *
   * @param int $uid
   *   The user id.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySetting|null
   *   The user setting entity or null if not found.
   */
  public function loadUserSettingEntity(int $uid): ?UserNotifySetting;

  /**
   * Create user setting entity for given user.
   *
   * @param int $uid
   *   The user id.
   * @param array $notification_settings
   *   The notification settings data to create the entity.
   * @param array $client_settings
   *   The client settings data to create the entity.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySetting
   *   The created and saved user setting entity.
   */
  public function createUserSetting(int $uid, array $notification_settings, array $client_settings): UserNotifySetting;

}
