<?php

namespace Drupal\message_auto_notify;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\message_auto_notify\Entity\Notification;
use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * The UserNotifySettingManager service.
 */
class UserNotifySettingManager implements UserNotifySettingManagerInterface {

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * The notifications.
   *
   * @var array
   */
  private array $notifications = [];

  /**
   * Constructs a new UserNotifySettingManager object.
   */
  public function __construct(ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function getNotificationSettings(int $uid): array {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      return $user_setting_entity->getNotificationSettings() + $this->getDefaultNotificationSettings();
    }
    else {
      return $this->getDefaultNotificationSettings();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function modifyNotificationSettings(int $uid, array $data): array {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      $data += $user_setting_entity->getNotificationSettings();
      $user_setting_entity->setNotificationSettings($data);
      $user_setting_entity->save();
    }
    else {
      $user_setting_entity = $this->createUserSetting($uid, $data, []);
    }
    return $user_setting_entity->getNotificationSettings() + $this->getDefaultNotificationSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function getClientSettings(int $uid): array {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      return $user_setting_entity->getClientSettings() + $this->getDefaultClientSettings();
    }
    else {
      return $this->getDefaultClientSettings();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function modifyClientSettings(int $uid, array $data): array {
    $user_setting_entity = $this->loadUserSettingEntity($uid);
    if ($user_setting_entity) {
      $data += $user_setting_entity->getClientSettings();
      $user_setting_entity->setClientSettings($data);
      $user_setting_entity->save();
    }
    else {
      $user_setting_entity = $this->createUserSetting($uid, [], $data);
    }
    return $user_setting_entity->getClientSettings() + $this->getDefaultClientSettings();
  }

  /**
   * Get the default notification settings.
   */
  private function getDefaultNotificationSettings(): array {
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
   * Get the default client settings.
   */
  private function getDefaultClientSettings(): array {
    $default_client_settings = [];
    $this->moduleHandler->alter(
      'default_client_settings',
      $default_client_settings
    );
    return $default_client_settings;
  }

  /**
   * {@inheritdoc}
   */
  public function loadUserSettingEntity(int $uid): ?UserNotifySetting {
    $user_notify_settings = \Drupal::entityTypeManager()->getStorage('user_notify_setting')->loadByProperties([
      'uid' => $uid,
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
  public function createUserSetting(int $uid, array $notification_settings, array $client_settings): UserNotifySetting {
    $entity = UserNotifySetting::create([
      'uid' => $uid,
      'notification_settings' => $notification_settings,
      'client_settings' => $client_settings,
    ]);
    $entity->save();
    return $entity;
  }

}
