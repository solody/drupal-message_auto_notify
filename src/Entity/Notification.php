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
 *   config_export = {
 *     "id",
 *     "label",
 *     "template",
 *     "notifier",
 *     "use_remote_template",
 *     "remote_template",
 *     "message_link"
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
   * The Notification template.
   *
   * @var string
   */
  protected $template;

  /**
   * The Notification notifier.
   *
   * @var string
   */
  protected $notifier;

  /**
   * Whether to use remote template.
   *
   * @var bool
   */
  protected $use_remote_template;

  /**
   * The remote template.
   *
   * @var string
   */
  protected $remote_template;

  /**
   * The message link.
   *
   * @var string
   */
  protected $message_link;

  /**
   * {@inheritdoc}
   */
  public function getTemplate() {
    return $this->template;
  }

  /**
   * {@inheritdoc}
   */
  public function setTemplate($template) {
    $this->template = $template;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNotifier() {
    return $this->notifier;
  }

  /**
   * {@inheritdoc}
   */
  public function setNotifier($notifier) {
    $this->notifier = $notifier;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUseRemoteTemplate() {
    return $this->use_remote_template;
  }

  /**
   * {@inheritdoc}
   */
  public function setUseRemoteTemplate($use_remote_template) {
    $this->use_remote_template = $use_remote_template;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteTemplate() {
    return $this->remote_template;
  }

  /**
   * {@inheritdoc}
   */
  public function setRemoteTemplate($remote_template) {
    $this->remote_template = $remote_template;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageLink() {
    return $this->message_link;
  }

  /**
   * {@inheritdoc}
   */
  public function setMessageLink($message_link) {
    $this->message_link = $message_link;
    return $this;
  }

}
