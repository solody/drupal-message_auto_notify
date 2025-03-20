<?php

namespace Drupal\message_auto_notify\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\message_auto_notify\Entity\UserNotifySettingInterface;
use Drupal\message_auto_notify\UserNotifySettingManager;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a resource to get and update user notify settings.
 *
 * @RestResource(
 *   id = "message_auto_notify_user_notify_setting",
 *   label = @Translation("User notify setting"),
 *   uri_paths = {
 *     "canonical" = "/api/rest/message-auto-notify/user-notify-setting"
 *   }
 * )
 */
class UserNotifySetting extends ResourceBase {

  /**
   * A current user instance.
   */
  protected AccountProxyInterface $currentUser;

  /**
   * The user notify setting manager.
   */
  protected UserNotifySettingManager $userNotifySettingManager;

  /**
   * Constructs a new UserNotifySetting object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Drupal\message_auto_notify\UserNotifySettingManager $user_notify_setting_manager
   *   The user notify setting manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    UserNotifySettingManager $user_notify_setting_manager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->userNotifySettingManager = $user_notify_setting_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('message_auto_notify'),
      $container->get('current_user'),
      $container->get('message_auto_notify.user_notify_setting_manager'),
    );
  }

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   */
  public function get(): ResourceResponse {
    $setting = [
      'notification' => $this->userNotifySettingManager->getNotificationSettings($this->currentUser->id()),
      'client' => $this->userNotifySettingManager->getClientSettings($this->currentUser->id()),
    ];
    $response = new ResourceResponse($setting, 200);

    $entity = $this->userNotifySettingManager->loadUserSettingEntity($this->currentUser->id());
    $tags = ['user_notify_setting_list', 'notification_list'];
    if ($entity instanceof UserNotifySettingInterface) {
      $tags[] = 'user_notify_setting:' . $entity->id();
    }
    $build = [
      '#cache' => [
        'tags' => $tags,
        'contexts' => ['user'],
      ],
    ];
    $cache_metadata = CacheableMetadata::createFromRenderArray($build);
    $response->addCacheableDependency($cache_metadata);
    $response->addCacheableDependency($this->currentUser);

    return $response;
  }

  /**
   * Responds to PATCH requests.
   *
   * @param array $data
   *   The data to apply to the user notify setting.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   */
  public function patch(array $data): ModifiedResourceResponse {
    if (isset($data['notification'])) {
      $this->userNotifySettingManager->modifyNotificationSettings($this->currentUser->id(), $data['notification']);
    }
    if (isset($data['client'])) {
      $this->userNotifySettingManager->modifyClientSettings($this->currentUser->id(), $data['client']);
    }
    $setting = [
      'notification' => $this->userNotifySettingManager->getNotificationSettings($this->currentUser->id()),
      'client' => $this->userNotifySettingManager->getClientSettings($this->currentUser->id()),
    ];
    return new ModifiedResourceResponse($setting, 200);
  }

}
