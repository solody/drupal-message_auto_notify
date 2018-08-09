<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Notification entity.
 *
 * @ConfigEntityType(
 *   id = "notification",
 *   label = @Translation("Notification"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\message_auto_notify\NotificationListBuilder",
 *     "form" = {
 *       "add" = "Drupal\message_auto_notify\Form\NotificationForm",
 *       "edit" = "Drupal\message_auto_notify\Form\NotificationForm",
 *       "delete" = "Drupal\message_auto_notify\Form\NotificationDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\message_auto_notify\NotificationHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "notification",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/message/notification/{notification}",
 *     "add-form" = "/admin/config/message/notification/add",
 *     "edit-form" = "/admin/config/message/notification/{notification}/edit",
 *     "delete-form" = "/admin/config/message/notification/{notification}/delete",
 *     "collection" = "/admin/config/message/notification"
 *   }
 * )
 */
class Notification extends ConfigEntityBase implements NotificationInterface {

  /**
   * The Notification ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Notification label.
   *
   * @var string
   */
  protected $label;

  /**
   * @var string
   */
  protected $template;

  /**
   * @var string
   */
  protected $notifier;

  /**
   * @var bool
   */
  protected $use_remote_template;

  /**
   * @var string
   */
  protected $remote_template;

  /**
   * @var string
   */
  protected $message_link;

  public function getTemplate() {
    return $this->template;
  }

  public function setTemplate($template) {
    $this->template = $template;
    return $this;
  }

  public function getNotifier() {
    return $this->notifier;
  }

  public function setNotifier($notifier) {
    $this->notifier = $notifier;
    return $this;
  }

  public function getUseRemoteTemplate() {
    return $this->use_remote_template;
  }

  public function setUseRemoteTemplate($use_remote_template) {
    $this->use_remote_template = $use_remote_template;
    return $this;
  }

  public function getRemoteTemplate() {
    return $this->remote_template;
  }

  public function setRemoteTemplate($remote_template) {
    $this->remote_template = $remote_template;
    return $this;
  }

  public function getMessageLink() {
    return $this->message_link;
  }

  public function setMessageLink($message_link) {
    $this->message_link = $message_link;
    return $this;
  }
}
