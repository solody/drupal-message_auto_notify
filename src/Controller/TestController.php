<?php

namespace Drupal\message_auto_notify\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\message\Entity\Message;
use Drupal\rest\ModifiedResourceResponse;

/**
 * Controller to test message notification.
 */
class TestController extends ControllerBase {

  /**
   * Response to the request.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   Return Hello string.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function test(): ModifiedResourceResponse {
    $message = Message::create(['template' => 'merchant_approved']);
    $message->setArguments([
      '@amount' => 200,
    ]);
    $message->setOwnerId(2);
    $message->save();
    return new ModifiedResourceResponse();
  }

}
