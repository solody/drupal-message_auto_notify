<?php

namespace Drupal\message_auto_notify\Controller;

use Drupal\commerce_payment\Entity\PaymentGateway;
use Drupal\Core\Controller\ControllerBase;
use Drupal\message\Entity\Message;
use Drupal\rest\ModifiedResourceResponse;

/**
 * Class TestController.
 */
class TestController extends ControllerBase {

  /**
   * Test.
   *
   * @return string
   *   Return Hello string.
   */
  public function test() {
    $message = Message::create(['template' => 'distribution_commission']);
    $message->setArguments([
      '@amount' => 200
    ]);
    $message->setOwnerId(4);
    $message->save();
    $entity = PaymentGateway::load('wechat_pay_h5_client');
    print_r([
      $entity
    ]);
    return new ModifiedResourceResponse();
  }

}
