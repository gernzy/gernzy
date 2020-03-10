<?php

namespace Gernzy\Server\GraphQL\Mutations;

use \App;
use GeoIp2\Database\Reader;
use Gernzy\Server\Exceptions\GernzyException;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cache;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SetSession
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function set($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $sessionService = App::make('Gernzy\SessionService');

        $sessionService->update($args['input']);

        return $sessionService->get();
    }

    public function getData($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $sessionService = App::make('Gernzy\SessionService');
        $data = $sessionService->get();
        return ['cart_uuid' => $data['cart_uuid'], 'currency' => isset($data['currency']) ? $data['currency'] : null];
    }

    public function setCurrency($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $sessionService = App::make('Gernzy\SessionService');

        $currency = $args['input']['currency'];

        // Throw error when there is no config containing the enbabled currencies
        // that is set by developers
        if (!$enabledCurrrencies = config('currency.enabled')) {
            throw new GernzyException(
                'An error occured.',
                'An error occured when determining the currency.'
            );
        }

        // Check if the selected currency is in the list of enabled currencies.
        // If it is not found then throw an appropriate error
        if (!in_array($currency, $enabledCurrrencies)) {
            throw new GernzyException(
                'Currency is invalid.',
                'The selected currency is not supported.'
            );
        }

        // Add the currency to the session data
        $sessionService->update(['currency' => $currency]);

        // Clear the previous rate for the user as a new currency has been chosen
        Cache::forget($sessionService->getToken());

        return $sessionService->get();
    }

    // setGeoLocation
    public function setGeoLocation($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        // Determine the user's IP address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        // Get the address
        if (!isset($ip_address)) {
            throw new GernzyException(
                'IP address invalid.',
                'The ip address could not be determined or the IP address is invalid'
            );
        }

        // Resolve the services
        $sessionService = App::make('Gernzy\SessionService');
        $geolocationService = App::make('Gernzy\GeolocationService');

        // Setup geolocation dependencies
        $geolocationService
            ->injectGeoRepositoryType((new Reader(config('db.maxmind_city_db'))))
            ->findandSetRecordMatchingIpAddress($ip_address);

        // Get the geolocation details
        $countryCode = $geolocationService->findCountryIsoCodeByIP($ip_address);
        $geolocationRecord = $geolocationService->getGeolocationRecord();

        // Update the session data
        $sessionService->update(['country_code' => $countryCode]);
        $sessionService->update(['geolocation_record' => $geolocationRecord]);

        // Convert the record to json for response
        $geolocationRecord = (array) $geolocationRecord;
        $geolocationRecord = json_encode($geolocationRecord);

        return ['geolocation_record' => $geolocationRecord];
    }
}
