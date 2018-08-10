<?php

namespace Drupal\message_auto_notify;

use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * Interface UserNotifySettingManagerInterface.
 */
interface UserNotifySettingManagerInterface {

  /**
   * @param $uid
   * @return array
   */
  public function getSetting($uid);

  /**
   * @param $uid
   * @param array $data
   * @return array
   */
  public function modifySetting($uid, array $data);

  /**
   * @param $uid
   * @return UserNotifySetting|null
   */
  public function loadUserSettingEntity($uid);

  /**
   * @param $uid
   * @param $data
   * @return UserNotifySetting
   */
  public function createUserSetting($uid, $data);
}
