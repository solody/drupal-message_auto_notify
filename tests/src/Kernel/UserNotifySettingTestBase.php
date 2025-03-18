<?php

declare(strict_types=1);

namespace Drupal\Tests\message_auto_notify\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\message_auto_notify\Entity\UserNotifySetting;

/**
 * Test description.
 */
abstract class UserNotifySettingTestBase extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['message_auto_notify', 'user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('user_notify_setting');
  }

}
