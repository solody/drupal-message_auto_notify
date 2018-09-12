<?php

namespace Drupal\message_auto_notify\Plugin\Notifier;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\message\MessageInterface;
use Drupal\message_auto_notify\AutoNotifySupportiveInterface;
use Drupal\message_auto_notify\Entity\Notification;
use Drupal\message_notify\Plugin\Notifier\MessageNotifierBase;
use Drupal\sms\Provider\PhoneNumberProviderInterface;
use Drupal\sms\Provider\SmsProviderInterface;
use Drupal\wechat_connect\Entity\WechatApplication;
use Drupal\wechat_connect\Plugin\WechatApplicationType\MediaPlatform;
use EasyWeChat\Foundation\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SMS notifier.
 *
 * @Notifier(
 *   id = "wechat",
 *   title = @Translation("Wechat"),
 *   descriptions = @Translation("Send messages via Wechat."),
 *   viewModes = {
 *     "default"
 *   }
 * )
 */
class Wechat extends MessageNotifierBase implements AutoNotifySupportiveInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MessageInterface $message = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.channel.message_notify'),
      $container->get('entity_type.manager'),
      $container->get('renderer'),
      $message
    );
  }

  /**
   * {@inheritdoc}
   */
  public function deliver(array $output = []) {
    $application = $this->getWechatConnectApplication();
    if ($application) {
      /** @var Notification $auto_notification */
      $auto_notification = $this->configuration['notification'];

      $wechat_args = [];
      foreach ($this->message->getArguments() as $key => $argument) {
        $wechat_args[str_replace('@', '', $key)] = $argument;
      }

      $rs = $application->sendTemplateMessage($this->message->getOwnerId(),
        $auto_notification->getRemoteTemplate(),
        $wechat_args,
        $this->message->get('message_link')->value);
      if (!$rs) {
        \Drupal::logger('message_auto_notify')->notice('消息自动通知没有成功。');
      }
    } else {
      \Drupal::logger('message_auto_notify')->notice('没找到合适的微信连接应用定义，消息自动通知没有成功。');
    }
  }

  public function supportRemoteTemplate() {
    return true;
  }

  /**
   * @return MediaPlatform|null
   */
  private function getWechatConnectApplication() {
    $config = \Drupal::config('message_auto_notify.settings');
    $wechat_application = WechatApplication::load($config->get('wechat_notifier_wechat_connect_app'));

    if ($wechat_application instanceof WechatApplication) {
      /** @var MediaPlatform $plugin */
      $plugin = \Drupal::getContainer()->get('plugin.manager.wechat_application_type')->createInstance($wechat_application->getType(), [
        'appId' => $wechat_application->id(),
        'appSecret' => $wechat_application->getSecret()
      ]);
      return $plugin;
    } else {
      return null;
    }
  }
}