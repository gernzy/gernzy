<?php

namespace Lab19\Cart\Services;

class GeoLocationService
{
    // $geoLocation is the specific GeoCoding service implementation injected via the interface.
    // The default service bound into laravel is Maxmind
    protected $geoLocation;
    protected $coordinates;

    public function __construct(GeolocationInterface $geoLocation)
    {
        $this->geoLocation = $geoLocation;
    }

    /**
     * This injects the type of repo maxmind will use to lookup the geocoding info
     *
     * @param object
     */
    public function injectGeoRepositoryType($implementation)
    {
        $this->geoLocation->setGeoRepository($implementation);
        return $this;
    }


    /**
     * Find the country code by the provided geocoding service
     *
     * @param object
     */
    public function findCountryIsoCodeByIP($ip_address)
    {
        return $this->geoLocation->geoFindCountryISO($ip_address);
    }
}
