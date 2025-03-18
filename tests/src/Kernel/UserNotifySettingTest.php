<?php

declare(strict_types=1);

namespace Drupal\Tests\message_auto_notify\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\message_auto_notify\Entity\UserNotifySetting;
use PHPUnit\Framework\Attributes\Group;

/**
 * Test description.
 */
#[Group('message_auto_notify')]
final class UserNotifySettingTest extends EntityKernelTestBase {

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

  /**
   * Test callback.
   */
  public function testMapFields(): void {
    $user = $this->drupalCreateUser();
    $uns = UserNotifySetting::create([
      'uid' => $user,
      'notification_settings' => [
        'some_notification_id' => FALSE,
      ],
    ]);
    $uns->save();

    self::assertEquals(1, $uns->id());
    self::assertEquals($user->id(), $uns->getOwnerId());
    self::assertEquals(['some_notification_id' => FALSE], $uns->getNotificationSettings());

    $new_settings = [
      'some_notification_id' => TRUE,
      'another_notification_id' => TRUE,
    ];
    $uns->setNotificationSettings($new_settings);
    self::assertEquals($new_settings, $uns->getNotificationSettings());

    $uns->save();
    $uns = $this->reloadEntity($uns);
    self::assertEquals($new_settings, $uns->getNotificationSettings());
  }

}
