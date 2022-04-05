<?php

namespace Drupal\current_time\Services;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Service to get current time according to timezone.
 */
class CurrentTimeService implements TrustedCallbackInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get the current time according to timezone.
   */
  public function getTime() {
    $config = $this->configFactory->get('current_time_location.settings');
    $timezone = $config->get('timezone');
    if (!empty($timezone)) {
      $date_obj = new DrupalDateTime('now', $timezone);
      $date = $date_obj->format('dS M Y - h:i A');
      return [
        '#markup' => $date,
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return [
      'getTime',
    ];
  }

}
