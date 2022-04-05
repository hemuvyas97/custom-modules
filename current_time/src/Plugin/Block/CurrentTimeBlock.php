<?php

namespace Drupal\current_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'Custom Current Time' Block.
 *
 * @Block(
 *   id = "current_time_block",
 *   admin_label = @Translation("Current Time Block"),
 *   category = @Translation("Custom Block"),
 * )
 */
class CurrentTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('current_time_location.settings');
    $item = [
      'country' => $config->get('country') ?? NULL,
      'city' => $config->get('city') ?? NULL,
    ];
    $placeholder = 'current_timezone_time';
    return [
      '#theme' => 'current_time',
      '#item' => $item,
      '#lazy_builder_var' => $placeholder,
      '#attached' => [
        'placeholders' => [
          $placeholder => [
            '#lazy_builder' => [
              'current_time.helper:getTime',
              [],
            ],
            '#create_placeholder' => TRUE,
          ],
        ],
      ],
      '#cache' => [
        'tags' => ['config:current_time_location.settings'],
        'contexts' => ['user'],
      ],
    ];
  }

}
