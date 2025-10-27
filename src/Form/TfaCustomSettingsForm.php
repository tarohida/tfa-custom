<?php

namespace Drupal\tfa_custom\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * TFA Custom設定フォーム。
 */
class TfaCustomSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tfa_custom_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['tfa_custom.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('tfa_custom.settings');

    $form['redirect_after_login'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ログイン後のリダイレクト先'),
      '#description' => $this->t('TFA検証後のリダイレクト先を指定します。例: /user, /dashboard, &lt;front&gt;'),
      '#default_value' => $config->get('redirect_after_login') ?: '<front>',
      '#required' => TRUE,
    ];

    $form['email_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('メール送信設定'),
    ];

    $form['email_settings']['send_email_on_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('TFA有効化時にメールを送信'),
      '#description' => $this->t('ユーザーがTFAを有効化した際に通知メールを送信します。'),
      '#default_value' => $config->get('send_email_on_enable') ?? TRUE,
    ];

    $form['email_settings']['send_email_on_disable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('TFA無効化時にメールを送信'),
      '#description' => $this->t('ユーザーがTFAを無効化した際に通知メールを送信します。'),
      '#default_value' => $config->get('send_email_on_disable') ?? TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('tfa_custom.settings')
      ->set('redirect_after_login', $form_state->getValue('redirect_after_login'))
      ->set('send_email_on_enable', $form_state->getValue('send_email_on_enable'))
      ->set('send_email_on_disable', $form_state->getValue('send_email_on_disable'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
