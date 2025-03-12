<?php

namespace Drupal\message_auto_notify;

use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * Interface of UserNotifySettingManager.
 */
interface UserNotifySettingManagerInterface {

  /**
   * Get notify setting by uid.
   *
   * @param int $uid
   *   The user id.
   *
   * @return array
   *   The notify setting of the given user.
   */
  public function getSetting(int $uid): array;

  /**
   * Modify notify setting by uid.
   *
   * @param int $uid
   *   The user id.
   * @param array $data
   *   The new setting data to apply.
   *
   * @return array
   *   The new notify setting saved.
   */
  public function modifySetting(int $uid, array $data): array;

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
   * @param array $data
   *   The setting data to create the entity.
   *
   * @return \Drupal\message_auto_notify\Entity\UserNotifySetting
   *   The created and saved user setting entity.
   */
  public function createUserSetting(int $uid, array $data): UserNotifySetting;

}
