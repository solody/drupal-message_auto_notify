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
    $config = \Drupal::configFactory()->get('message_auto_notify.notification.test');
    $this->assertEquals(0, $config->get('use_remote_template'));

    $this->runUpdates();
    $config = \Drupal::configFactory()->get('message_auto_notify.notification.test');
    $this->assertFalse($config->get('use_remote_template'));
  }

}
