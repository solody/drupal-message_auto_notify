<?php

namespace Drupal\message_auto_notify\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\wechat_connect\Entity\WechatApplication;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings Form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typedConfigManager,
    private ModuleHandlerInterface $moduleHandler,
  ) {
    parent::__construct($config_factory, $typedConfigManager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'message_auto_notify_settings_form';
  }

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return [
      'message_auto_notify.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('message_auto_notify.settings');

    if ($this->moduleHandler->moduleExists('wechat_connect')) {
      $wechat_connect_applications = WechatApplication::loadMultiple();
      $options = [];
      foreach ($wechat_connect_applications as $wechat_connect_application) {
        /** @var \Drupal\wechat_connect\Entity\WechatApplication $wechat_connect_application */
        if ($wechat_connect_application->getType() === 'media_platform') {
          $options[$wechat_connect_application->id()] = $wechat_connect_application->label();
        }
      }

      $form['wechat_notifier_wechat_connect_app'] = [
        '#type' => 'select',
        '#title' => $this->t('Wechat Connect Application'),
        '#description' => $this->t('The Wechat Connect Application to use for sending notifications.'),
        '#options' => $options,
        '#default_value' => $config->get('wechat_notifier_wechat_connect_app'),
        '#weight' => '0',
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('message_auto_notify.settings');
    if ($this->moduleHandler->moduleExists('wechat_connect')) {
      $config->set('wechat_notifier_wechat_connect_app', $form_state->getValue('wechat_notifier_wechat_connect_app'));
    }
    $config->save();
  }

}
