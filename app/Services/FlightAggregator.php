<?php 

namespace App\Services;

use App\DataTransferObj\SearchCriteria;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Exception;

class FlightAggregator
{
    protected array $providers = [];

    public function __construct()
    {
        $this->providers = config('flights.enabled_providers', []);

        if (empty($this->providers)) {
            throw new Exception("No flight providers are configured or enabled in config/flights.php");
        }
    }

   
    public function aggregate(SearchCriteria $criteria): array
    {
        $flights = [];
        $metadata = [];

        foreach ($this->providers as $providerClass) {
            $provider = app($providerClass);
            $name = $provider->getName();

            try {
                
                $fetched = $provider->fetchFlights($criteria);
                
                $flights = array_merge($flights, $fetched);
                $metadata[$name] = ['status' => 'success', 'code' => 200];
            } catch (Exception $e) {
                $metadata[$name] = ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return [
            'flights' => $flights,
            'metadata' => $metadata
        ];
    }

}