<?php

namespace Drupal\message_auto_notify\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Notification entities.
 */
interface NotificationInterface extends ConfigEntityInterface {
  public function getTemplate();

  public function setTemplate($template);

  public function getNotifier();

  public function setNotifier($notifier);

  public function getUseRemoteTemplate();

  public function setUseRemoteTemplate($use_remote_template);

  public function getRemoteTemplate();

  public function setRemoteTemplate($remote_template);

  public function getMessageLink();

  public function setMessageLink($message_link);
}
