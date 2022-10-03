<?php

declare(strict_types=1);

namespace Drupal\sitelocation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Configure settings for this site.
 */
class SiteLocationConfig extends ConfigFormBase {
  /**
   * Config settings.
   *
   * @var string
   */
  private const SETTINGS = 'sitelocation.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sitelocation_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * Build form implementation.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['text_header'] = [
      '#prefix' => '<strong>',
      '#suffix' => '<br><br></strong>',
      '#markup' => $this->t('Configure site location and timezone.'),
      '#weight' => -100,
    ];

    $form['sitelocation_country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Enter country of your choice.'),
      '#required' => TRUE,
      '#default_value' => $this->config(static::SETTINGS)->get('sitelocation_country'),
      '#weight' => 1,
    ];

    $form['sitelocation_city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#description' => $this->t('Enter city.'),
      '#required' => TRUE,
      '#default_value' => $this->config(static::SETTINGS)->get('sitelocation_city'),
      '#weight' => 2,
    ];

    $form['sitelocation_timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#description' => $this->t('Select a timezone'),
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),
      ],
      '#required' => TRUE,
      '#default_value' => $this->config(static::SETTINGS)->get('sitelocation_timezone'),
      '#weight' => 3,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit handler for sitelocation form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('sitelocation_country', $form_state->getValue('sitelocation_country'))
      ->set('sitelocation_city', $form_state->getValue('sitelocation_city'))
      ->set('sitelocation_timezone', $form_state->getValue('sitelocation_timezone'))
      ->save();

    // Invalidate cache tag for this block.
    Cache::invalidateTags(['sitelocation']);

    parent::submitForm($form, $form_state);
  }

}
