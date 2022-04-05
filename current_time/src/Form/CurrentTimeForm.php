<?php

namespace Drupal\current_time\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for Site's Current Time and Location.
 */
class CurrentTimeForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['current_time_location.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'current_time_location_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('current_time_location.settings');

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('country') ?? NULL,
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('city') ?? NULL,
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#required' => TRUE,
      '#options' => [
        '' => '- None -',
        'America' => [
          'America/Chicago' => 'Chicago',
          'America/New_York' => 'New York',
        ],
        'Asia' => [
          'Asia/Tokyo' => 'Tokyo',
          'Asia/Dubai' => 'Dubai',
          'Asia/Kolkata' => 'Kolkata',
        ],
        'Europe' => [
          'Europe/Amsterdam' => 'Amsterdam',
          'Europe/Oslo' => 'Oslo',
          'Europe/London' => 'London',
        ],
      ],
      '#default_value' => $config->get('timezone') ?? NULL,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('current_time_location.settings');
    $config->set('country', $form_state->getValue(['country']));
    $config->set('city', $form_state->getValue(['city']));
    $config->set('timezone', $form_state->getValue(['timezone']));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

}
