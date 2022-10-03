<?php

declare(strict_types=1);

namespace Drupal\sitelocation\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Site Location service class.
 */
class SiteLocationService
{
    private $config_factory;

    /**
     * SiteLocationService class constructor.
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     * The config factory.
     */
    public function __construct(ConfigFactoryInterface $config_factory)
    {
        $this->config_factory = $config_factory;
    }

    /**
     * Gives current site time.
     */
    public function siteTime(): string
    {
        $date_time = new DrupalDateTime();
        $site_location_timezone = $this->config_factory->get('sitelocation.settings');
        $now = $date_time->getTimestamp();
        $site_current_datetime = $date_time->createFromTimestamp(
            $now,
            $site_location_timezone->get('sitelocation_timezone')
        );

        return $site_current_datetime->format('H:iA');
    }

    /**
     * Gives current site day, date and year.
     */
    public function siteDayDate(): string
    {
        $date_time = new DrupalDateTime();
        $site_location_timezone = $this->config_factory->get('sitelocation.settings');
        $now = $date_time->getTimestamp();
        $site_current_datetime = $date_time->createFromTimestamp(
            $now,
            $site_location_timezone->get('sitelocation_timezone')
        );

        return $site_current_datetime->format('l, j M Y');
    }

    /**
     * Gives current site city & country.
     */
    public function siteCityCountry(): string
    {
        $site_location_timezone = $this->config_factory->get('sitelocation.settings');
        $country = $site_location_timezone->get('sitelocation_country');
        $city = $site_location_timezone->get('sitelocation_city');

        return $city . ', ' . $country;
    }
}
