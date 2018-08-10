<?php

namespace Drupal\message_auto_notify\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for User notify setting edit forms.
 *
 * @ingroup message_auto_notify
 */
class UserNotifySettingForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\message_auto_notify\Entity\UserNotifySetting */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label User notify setting.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label User notify setting.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.user_notify_setting.canonical', ['user_notify_setting' => $entity->id()]);
  }

}
