<?php

namespace Drupal\message_auto_notify;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the User notify setting entity.
 *
 * @see \Drupal\message_auto_notify\Entity\UserNotifySetting.
 */
class UserNotifySettingAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\message_auto_notify\Entity\UserNotifySettingInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view user notify setting entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit user notify setting entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete user notify setting entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add user notify setting entities');
  }

}
