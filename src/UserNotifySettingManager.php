<?php

namespace Drupal\message_auto_notify;

use Drupal\message_auto_notify\Entity\Notification;
use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * Class UserNotifySettingManager.
 */
class UserNotifySettingManager implements UserNotifySettingManagerInterface {

  private $notifications = [];

  /**
   * Constructs a new UserNotifySettingManager object.
   */
  public function __construct() {

  }

  /**
   * @param $uid
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getSetting($uid) {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      return $user_setting_entity->getData() + $this->getDefaultSetting();
    } else {
      return $this->getDefaultSetting();
    }
  }

  /**
   * @param $uid
   * @param array $data
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function modifySetting($uid, array $data) {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      $data += $user_setting_entity->getData();
      $user_setting_entity->setData($data);
      $user_setting_entity->save();
    } else {
      $user_setting_entity = $this->createUserSetting($uid, $data);
    }

    return $user_setting_entity->getData() + $this->getDefaultSetting();
  }

  private function getDefaultSetting() {
    if (empty($this->notifications)) {
      $this->notifications = Notification::loadMultiple();
    }

    $setting = [];
    foreach ($this->notifications as $notification) {
      /** @var Notification $notification */
      $setting[$notification->id()] = true;
    }

    return $setting;
  }

  /**
   * @param $uid
   * @return UserNotifySetting|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function loadUserSettingEntity($uid) {
    $user_notify_settings = \Drupal::entityTypeManager()->getStorage('user_notify_setting')->loadByProperties([
      'user_id' => $uid
    ]);
    if (count($user_notify_settings)) {
      return array_pop($user_notify_settings);
    } else {
      return null;
    }
  }

  /**
   * @param $uid
   * @param $data
   * @return \Drupal\Core\Entity\EntityInterface|UserNotifySetting
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createUserSetting($uid, $data) {
    $entity = UserNotifySetting::create([
      'user_id' => $uid,
      'data' => serialize($data)
    ]);
    $entity->save();
    return $entity;
  }
}
