<?php

namespace Drupal\message_auto_notify\Plugin\Notifier;

use JPush\Client;
use Drupal\message_notify\Plugin\Notifier\MessageNotifierBase;

/**
 * SMS notifier.
 *
 * @Notifier(
 *   id = "app_notification_push",
 *   title = @Translation("App Notification Push"),
 *   descriptions = @Translation("Send messages via App notification pushing."),
 *   viewModes = {
 *     "default"
 *   }
 * )
 */
class AppNotificationPush extends MessageNotifierBase {

  /**
   * {@inheritdoc}
   */
  public function deliver(array $output = []): bool {

    $content = (string) $this->message->getText()[0];

    try {
      $client = new Client('cba0b2959d6ab8aef88025b5', '61f8c6ec1a5dfa5111e11206');
      $pusher = $client->push();
      $pusher->setPlatform('all');
      $pusher->addAlias('user#' . $this->message->getOwnerId());
      $pusher->setNotificationAlert($content);
      $pusher->send();
      return TRUE;
    }
    catch (\Exception $e) {
      // Other exceptions can be thrown.
      \Drupal::logger('message_auto_notify')->error($e->getMessage() . ', message: ' . $content);
    }

    return FALSE;
  }

}
