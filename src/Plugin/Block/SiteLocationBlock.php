<?php

declare(strict_types=1);

namespace Drupal\sitelocation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\sitelocation\Services\SiteLocationService;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a 'Site Location' Block.
 *
 * @Block(
 *   id = "sitelocation_block",
 *   admin_label = @Translation("Site DateTime"),
 *   category = @Translation("Location"),
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * SiteLocationBlock class constructor.
   *
   * @param mixed $configuration
   *   Variable.
   * @param mixed $plugin_id
   *   Variable.
   * @param mixed $plugin_definition
   *   Variable.
   * @param \Drupal\sitelocation\Services\SiteLocationService $sitelocation_service
   *   Variable.
   *
   * @return void
   */
  public function __construct($configuration, $plugin_id, $plugin_definition, SiteLocationService $sitelocation_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->sitelocation_service = $sitelocation_service;
  }

  /**
   * Container aware interface create static function.
   *
   * @param Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Variable.
   * @param array $configuration
   *   Variable.
   * @param string $plugin_id
   *   Variable.
   * @param mixed $plugin_definition
   *   Variable.
   *
   * @return static
   */
  public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition
    ) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('sitelocation.config')
      );
  }

  /**
   * Build function for Blocks.
   */
  public function build(): array {
    // Return formatted Datetime.
    $renderable = [
      '#theme' => 'sitelocation_block',
      '#site_time' => $this->sitelocation_service->siteTime(),
      '#site_day_date_year' => $this->sitelocation_service->siteDayDate(),
      '#site_city_country' => $this->sitelocation_service->siteCityCountry(),
    ];

    return $renderable;
  }

  /**
   * Invalidate cache tags.
   *
   * @return mixed
   *   Array.
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['sitelocation']);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    $date_time = new DrupalDateTime();
    $now = $date_time->getTimestamp();
    $site_current_datetime = $date_time->createFromTimestamp($now);
    $cache_every_min = (60 - (int) $site_current_datetime->format('s'));
    return $cache_every_min;
  }

}
