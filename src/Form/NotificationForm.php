<?php

namespace Drupal\message_auto_notify\Form;

use Drupal\message_auto_notify\EntityHelper;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\message\Entity\MessageTemplate;

/**
 * Notification edit Form.
 */
class NotificationForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\message_auto_notify\Entity\Notification $notification */
    $notification = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $notification->label(),
      '#description' => $this->t("Label for the Notification."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $notification->id(),
      '#machine_name' => [
        'exists' => '\Drupal\message_auto_notify\Entity\Notification::load',
      ],
      '#disabled' => !$notification->isNew(),
    ];

    /** @var \Drupal\message_notify\Plugin\Notifier\Manager $notifierManager */
    $notifierManager = \Drupal::getContainer()->get('plugin.message_notify.notifier.manager');
    $notifierOptionList = [];
    foreach ($notifierManager->getDefinitions() as $definition) {
      $notifierOptionList[$definition['id']] = $definition['title'];
    }
    $form['notifier'] = [
      '#type' => 'select',
      '#title' => $this->t('Notifier'),
      '#default_value' => $notification->getNotifier(),
      '#options' => $notifierOptionList,
      '#required' => TRUE,
    ];

    $form['template'] = [
      '#type' => 'select',
      '#title' => $this->t('Template'),
      '#default_value' => $notification->getTemplate(),
      '#options' => EntityHelper::extractLabels(MessageTemplate::loadMultiple()),
      '#required' => TRUE,
    ];

    $form['use_remote_template'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use remote template'),
      '#default_value' => $notification->getUseRemoteTemplate(),
    ];

    $form['remote_template'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Remote template'),
      '#maxlength' => 255,
      '#default_value' => $notification->getRemoteTemplate(),
      '#description' => $this->t("Remote template name."),
      '#required' => FALSE,
      '#states' => [
        // Only show this field when the 'toggle_me' checkbox is enabled.
        'visible' => [
          ':input[name="use_remote_template"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $notification = $this->entity;
    $status = $notification->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Notification.', [
          '%label' => $notification->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Notification.', [
          '%label' => $notification->label(),
        ]));
    }
    $form_state->setRedirectUrl($notification->toUrl('collection'));
  }

}
