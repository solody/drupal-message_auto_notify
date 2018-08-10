<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
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
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
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
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    if (empty($this->get('data')->value)) {
      return [];
    } else {
      return unserialize($this->get('data')->value);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setData($data) {
    $this->set('data', serialize($data));
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('配置所属用户'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setRequired(TRUE);

    $fields['data'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('配置数据'))
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
