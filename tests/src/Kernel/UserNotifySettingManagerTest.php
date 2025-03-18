<?php

declare(strict_types=1);

namespace Drupal\Tests\message_auto_notify\Kernel;

use PHPUnit\Framework\Attributes\Group;

/**
 * Test description.
 */
#[Group('message_auto_notify')]
final class UserNotifySettingManagerTest extends UserNotifySettingTestBase {

  /**
   * Test callback.
   */
  public function testManager(): void {
    /** @var \Drupal\message_auto_notify\UserNotifySettingManagerInterface $manager */
    $manager = $this->container->get('message_auto_notify.user_notify_setting_manager');
    $user = $this->drupalCreateUser();
    self::assertEmpty($manager->getNotificationSettings((int) $user->id()));
    self::assertEmpty($manager->getClientSettings((int) $user->id()));

    $notification_settings = [
      'some_notification_id' => FALSE,
    ];
    $manager->modifyNotificationSettings((int) $user->id(), $notification_settings);
    self::assertEquals($notification_settings, $manager->getNotificationSettings((int) $user->id()));

    $client_settings = [
      'xxx' => FALSE,
    ];
    $manager->modifyClientSettings((int) $user->id(), $client_settings);
    self::assertEquals($client_settings, $manager->getClientSettings((int) $user->id()));
    self::assertEquals($notification_settings, $manager->getNotificationSettings((int) $user->id()));
  }

}
