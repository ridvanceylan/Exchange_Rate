<?php

namespace Drupal\exchange_rates\Adapter;

use Drupal\Core\Http\Client;

class Provider2Adapter implements ExchangeRateAdapterInterface
{
  public function getExchangeRate()
  {
    $client = \Drupal::httpClient();
    try {
      $response = $client->get('https://run.mocky.io/v3/90020ec3-9420-40cc-a12a-5289fdad0951');
      // Check if the request was successful (status code 200).
      if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody(), true);
        \Drupal::logger('exchange_rates')->notice('API request succeeded: ' . $response->getStatusCode());
        $minValue = min(array_values($data)[0]);
        // Find the key associated with the minimum value
        $minKey = array_search($minValue, array_values($data)[0]);
        return array('provider' => "Provider2", 'minValue' => $minValue, 'minKey' => $minKey);
      } else {
        \Drupal::logger('exchange_rates')->error('API Request failed with status code: ' . $response->getStatusCode());
      }
    } catch (\Exception $e) {
      \Drupal::logger('exchange_rates')->error('API Request failed: ' . $e->getMessage());
    }
  }
}
