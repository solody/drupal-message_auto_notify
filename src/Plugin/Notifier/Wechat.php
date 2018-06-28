<?php
namespace Drupal\message_auto_notify\Plugin\Notifier;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\message\MessageInterface;
use Drupal\message_auto_notify\AutoNotifySupportiveInterface;
use Drupal\message_notify\Plugin\Notifier\MessageNotifierBase;
use Drupal\sms\Provider\PhoneNumberProviderInterface;
use Drupal\sms\Provider\SmsProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SMS notifier.
 *
 * @Notifier(
 *   id = "wechat",
 *   title = @Translation("Wechat"),
 *   descriptions = @Translation("Send messages via Wechat."),
 *   viewModes = {
 *     "sms_body"
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
        /** @var \Drupal\sms\Provider\SmsProviderInterface $sms_service */
        $sms_service = \Drupal::service('sms.provider');
        $sms = (new \Drupal\sms\Message\SmsMessage())
            ->setMessage($output['sms_body']) // Set the message.
            ->addRecipient($this->message->getOwner()->get('phone')->value) // Set recipient phone number
            ->setDirection(\Drupal\sms\Direction::OUTGOING);

        try {
            $sms_service->queue($sms);
        }
        catch (\Drupal\sms\Exception\RecipientRouteException $e) {
            // Thrown if no gateway could be determined for the message.
        }
        catch (\Exception $e) {
            // Other exceptions can be thrown.
        }
    }

    public function supportRemoteTemplate()
    {
        return true;
    }
}