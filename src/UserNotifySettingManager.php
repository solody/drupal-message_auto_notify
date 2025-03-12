<?php

namespace Drupal\message_auto_notify;

use Drupal\message_auto_notify\Entity\Notification;
use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * The UserNotifySettingManager service.
 */
class UserNotifySettingManager implements UserNotifySettingManagerInterface {

  /**
   * The notifications.
   *
   * @var array
   */
  private array $notifications = [];

  /**
   * Constructs a new UserNotifySettingManager object.
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public function getSetting(int $uid): array {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      return $user_setting_entity->getData() + $this->getDefaultSetting();
    }
    else {
      return $this->getDefaultSetting();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function modifySetting(int $uid, array $data): array {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      $data += $user_setting_entity->getData();
      $user_setting_entity->setData($data);
      $user_setting_entity->save();
    }
    else {
      $user_setting_entity = $this->createUserSetting($uid, $data);
    }

    return $user_setting_entity->getData() + $this->getDefaultSetting();
  }

  /**
   * Get the default setting.
   */
  private function getDefaultSetting() {
    if (empty($this->notifications)) {
      $this->notifications = Notification::loadMultiple();
    }

    $setting = [];
    foreach ($this->notifications as $notification) {
      /** @var \Drupal\message_auto_notify\Entity\Notification $notification */
      $setting[$notification->id()] = TRUE;
    }

    return $setting;
  }

  /**
   * {@inheritdoc}
   */
  public function loadUserSettingEntity(int $uid): ?UserNotifySetting {
    $user_notify_settings = \Drupal::entityTypeManager()->getStorage('user_notify_setting')->loadByProperties([
      'user_id' => $uid,
    ]);
    if (count($user_notify_settings)) {
      return array_pop($user_notify_settings);
    }
    else {
      return NULL;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createUserSetting(int $uid, array $data): UserNotifySetting {
    $entity = UserNotifySetting::create([
      'user_id' => $uid,
      'data' => serialize($data),
    ]);
    $entity->save();
    return $entity;
  }

}
