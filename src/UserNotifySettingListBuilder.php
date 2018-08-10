<?php

namespace Drupal\message_auto_notify;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of User notify setting entities.
 *
 * @ingroup message_auto_notify
 */
class UserNotifySettingListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('User notify setting ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\message_auto_notify\Entity\UserNotifySetting */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.user_notify_setting.edit_form',
      ['user_notify_setting' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
