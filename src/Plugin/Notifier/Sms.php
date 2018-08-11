<?php

namespace Drupal\message_auto_notify\Plugin\Notifier;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\message\MessageInterface;
use Drupal\message_auto_notify\Entity\Notification;
use Drupal\message_auto_notify\Entity\NotificationInterface;
use Drupal\message_notify\Plugin\Notifier\MessageNotifierBase;
use Drupal\sms\Provider\PhoneNumberProviderInterface;
use Drupal\sms\Provider\SmsProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SMS notifier.
 *
 * @Notifier(
 *   id = "sms",
 *   title = @Translation("SMS"),
 *   descriptions = @Translation("Send messages via SMS."),
 *   viewModes = {
 *     "default"
 *   }
 * )
 */
class Sms extends MessageNotifierBase {
  /**
   * The SMS phone number provider service.
   *
   * @var \Drupal\sms\Provider\PhoneNumberProviderInterface
   */
  protected $phoneNumberProvider;
  /**
   * The SMS provider service.
   *
   * @var \Drupal\sms\Provider\SmsProviderInterface
   */
  protected $smsProvider;

  /**
   * Constructs the SMS notifier plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   *   The message_notify logger channel.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Render\RendererInterface $render
   *   The rendering service.
   * @param \Drupal\message\MessageInterface $message
   *   (optional) The message entity. This is required when sending or
   *   delivering a notification. If not passed to the constructor, use
   *   ::setMessage().
   * @param \Drupal\sms\Provider\PhoneNumberProviderInterface $phone_number_provider
   *   The SMS phone number provider.
   * @param \Drupal\sms\Provider\SmsProviderInterface $sms_provider
   *   The SMS provider service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelInterface $logger, EntityTypeManagerInterface $entity_type_manager, RendererInterface $render, MessageInterface $message = NULL, PhoneNumberProviderInterface $phone_number_provider, SmsProviderInterface $sms_provider) {
    // Set configuration defaults.
    $configuration += [
      'mail' => FALSE,
      'language override' => FALSE,
    ];
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger, $entity_type_manager, $render, $message);
    $this->phoneNumberProvider = $phone_number_provider;
    $this->smsProvider = $sms_provider;
  }

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
      $message,
      $container->get('sms.phone_number'),
      $container->get('sms.provider')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function deliver(array $output = []) {
    $phone = null;
    if (!$this->message->get('phone')->isEmpty()) {
      $phone = $this->message->get('phone')->value;
    } else {
      $phone = $this->message->getOwner()->get('phone')->value;
    }

    if (empty($phone)) {
      \Drupal::logger('message_auto_notify')->notice('一条短信通知发送失败，因为找不到手机号：' . $this->message->getText());
      return;
    }

    $content = (string)$this->message->getText()[0];

    /** @var \Drupal\sms\Provider\SmsProviderInterface $sms_service */
    $sms_service = \Drupal::service('sms.provider');
    $sms = (new \Drupal\sms\Message\SmsMessage())
      ->setMessage($content)// Set the message.
      ->addRecipient($phone)// Set recipient phone number
      ->setDirection(\Drupal\sms\Direction::OUTGOING);

    if ($this->configuration['notification']) {
      /** @var NotificationInterface $notification */
      $notification = $this->configuration['notification'];

      if ($notification->getUseRemoteTemplate()) {
        $sms->setOption('remote_template', $notification->getRemoteTemplate());
        $sms->setOption('remote_template_data', $this->message->getArguments());
      }
    }

    try {
      $sms_service->send($sms);
      return true;
    } catch (\Drupal\sms\Exception\RecipientRouteException $e) {
      // Thrown if no gateway could be determined for the message.
      \Drupal::logger('message_auto_notify')->notice($e->getMessage() . $this->message->getText());
    } catch (\Exception $e) {
      // Other exceptions can be thrown.
      \Drupal::logger('message_auto_notify')->notice($e->getMessage() . $this->message->getText());
    }
  }
}