<?php

namespace Drupal\message_auto_notify\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\message_auto_notify\UserNotifySettingManager;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
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
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var UserNotifySettingManager
   */
  protected $userNotifySettingManager;

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
   * @param UserNotifySettingManager $userNotifySettingManager
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    UserNotifySettingManager $userNotifySettingManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->userNotifySettingManager = $userNotifySettingManager;
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
      $container->get('message_auto_notify.user_notify_setting')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function get() {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $setting = $this->userNotifySettingManager->getSetting($this->currentUser->id());

    $response =  new ResourceResponse($setting, 200);

    $build = [
      '#cache' => [
        'tags' => ['user_notify_setting_list'],
      ],
    ];
    $cache_metadata = \Drupal\Core\Cache\CacheableMetadata::createFromRenderArray($build);
    $response->addCacheableDependency($cache_metadata);

    return $response;
  }

  /**
   * Responds to PATCH requests.
   *
   * @param $data
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function patch($data) {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $setting = $this->userNotifySettingManager->modifySetting($this->currentUser->id(), $data);

    return new ModifiedResourceResponse($setting, 200);
  }

}
