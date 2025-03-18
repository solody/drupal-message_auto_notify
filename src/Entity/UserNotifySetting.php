<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the User notify setting entity.
 *
 * @ingroup message_auto_notify
 *
 * @ContentEntityType(
 *   id = "user_notify_setting",
 *   label = @Translation("User notify setting"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\message_auto_notify\UserNotifySettingListBuilder",
 *     "views_data" = "Drupal\message_auto_notify\Entity\UserNotifySettingViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\message_auto_notify\Form\UserNotifySettingForm",
 *       "add" = "Drupal\message_auto_notify\Form\UserNotifySettingForm",
 *       "edit" = "Drupal\message_auto_notify\Form\UserNotifySettingForm",
 *       "delete" = "Drupal\message_auto_notify\Form\UserNotifySettingDeleteForm",
 *     },
 *     "access" = "Drupal\message_auto_notify\UserNotifySettingAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\message_auto_notify\UserNotifySettingHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "user_notify_setting",
 *   admin_permission = "administer user notify setting entities",
 *   entity_keys = {
 *     "id" = "uns_id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *     "langcode" = "langcode"
 *   },
 *   links = {
 *     "canonical" = "/admin/message_auto_notify/user_notify_setting/{user_notify_setting}",
 *     "add-form" = "/admin/message_auto_notify/user_notify_setting/add",
 *     "edit-form" = "/admin/message_auto_notify/user_notify_setting/{user_notify_setting}/edit",
 *     "delete-form" = "/admin/message_auto_notify/user_notify_setting/{user_notify_setting}/delete",
 *     "collection" = "/admin/message_auto_notify/user_notify_setting",
 *   },
 *   field_ui_base_route = "user_notify_setting.settings"
 * )
 */
class UserNotifySetting extends ContentEntityBase implements UserNotifySettingInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getNotificationSettings(): array {
    if ($this->get('notification_settings')->isEmpty()) {
      return [];
    }
    else {
      return $this->get('notification_settings')->offsetGet(0)->getValue();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setNotificationSettings(array $data): UserNotifySettingInterface {
    $this->set('notification_settings', $data);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getClientSettings(): array {
    if ($this->get('client_settings')->isEmpty()) {
      return [];
    }
    else {
      return $this->get('client_settings')->offsetGet(0)->getValue();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setClientSettings(array $data): UserNotifySettingInterface {
    $this->set('client_settings', $data);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime(): int {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime(int $timestamp): UserNotifySettingInterface {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Owner'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setRequired(TRUE);

    $fields['notification_settings'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Switcher settings for each notification.'))
      ->setRequired(TRUE);

    $fields['client_settings'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Settings persistence for client customized.'))
      ->setRequired(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
