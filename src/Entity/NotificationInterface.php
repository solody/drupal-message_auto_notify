<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Notification entities.
 */
interface NotificationInterface extends ConfigEntityInterface {

  /**
   * Gets the template ID.
   */
  public function getTemplate();

  /**
   * Sets the template ID.
   */
  public function setTemplate($template);

  /**
   * Gets the notifier id.
   */
  public function getNotifier();

  /**
   * Sets the notifier id.
   */
  public function setNotifier($notifier);

  /**
   * Gets whether to use remote template.
   */
  public function getUseRemoteTemplate();

  /**
   * Sets whether to use remote template.
   */
  public function setUseRemoteTemplate($use_remote_template);

  /**
   * Gets the remote template.
   */
  public function getRemoteTemplate();

  /**
   * Sets the remote template.
   */
  public function setRemoteTemplate($remote_template);

}
