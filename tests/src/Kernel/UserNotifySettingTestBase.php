<?php

declare(strict_types=1);

namespace Drupal\Tests\message_auto_notify\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Test description.
 */
abstract class UserNotifySettingTestBase extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['message_auto_notify', 'user', 'rest', 'serialization'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['message_auto_notify']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('user_notify_setting');
  }

}
