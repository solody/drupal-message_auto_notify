<?php

declare(strict_types=1);

namespace Drupal\Tests\message_auto_notify\Functional\Update;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;
use PHPUnit\Framework\Attributes\Group;

/**
 * Tests the upgrade path for adding product weight field.
 */
#[Group('message_auto_notify')]
class Update8001Test extends UpdatePathTestBase {

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
  protected function setDatabaseDumpFiles(): void {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../../fixtures/update/8000-db-dump.php.gz',
    ];
  }

  /**
   * Tests that numeric argument plugins are updated properly.
   */
  public function testAddingProductWeightField(): void {
    // $this->assertTrue(function_exists('message_auto_notify_update_8001'), 'The message_auto_notify_update_8001() has been loaded');

    /** @var \Drupal\Core\Update\UpdateHookRegistry $update_registry */
    $update_registry = \Drupal::service('update.update_hook_registry');
    $this->assertEquals(8000, $update_registry->getInstalledVersion('message_auto_notify'));

    $config = \Drupal::configFactory()->get('message_auto_notify.notification.test');
    $this->assertEquals(0, $config->get('use_remote_template'));

    $this->runUpdates();
    $config = \Drupal::configFactory()->get('message_auto_notify.notification.test');
    $this->assertFalse($config->get('use_remote_template'));

    /** @var \Drupal\Core\Update\UpdateHookRegistry $update_registry */
    $update_registry = \Drupal::service('update.update_hook_registry');
    $this->assertEquals(8001, $update_registry->getInstalledVersion('message_auto_notify'));
  }

}
