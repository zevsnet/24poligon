<?php

namespace SB\Korona;

use SB\Korona\CFTLoyaltyPCPointsClient;
use SB\Korona\CFTLoyaltyPCPointsClassmap;
use Phpro\SoapClient\ClientFactory as PhproClientFactory;
use Phpro\SoapClient\ClientBuilder;

class CFTLoyaltyPCPointsClientFactory
{

    public static function factory(string $wsdl) : \SB\Korona\CFTLoyaltyPCPointsClient
    {
        $clientFactory = new PhproClientFactory(CFTLoyaltyPCPointsClient::class);
        $clientBuilder = new ClientBuilder($clientFactory, $wsdl, []);
        $clientBuilder->withClassMaps(CFTLoyaltyPCPointsClassmap::getCollection());

        return $clientBuilder->build();
    }


}

