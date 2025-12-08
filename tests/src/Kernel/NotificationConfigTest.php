<?php

declare(strict_types=1);

namespace Drupal\Tests\message_auto_notify\Kernel;

use PHPUnit\Framework\Attributes\Group;

/**
 * Test description.
 */
#[Group('message_auto_notify')]
final class NotificationConfigTest extends UserNotifySettingTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $configSchemaCheckerExclusions = [
    // Following are used to test lack of or partial schema. Where partial
    // schema is provided, that is explicitly tested in specific tests.
    'message_auto_notify.notification.test',
  ];

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['notification_config_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['notification_config_test']);
  }

  /**
   * Test callback.
   */
  public function testManager(): void {
    $config = \Drupal::configFactory()->get('message_auto_notify.notification.test');
    $this->assertFalse($config->get('use_remote_template'));
  }

}
