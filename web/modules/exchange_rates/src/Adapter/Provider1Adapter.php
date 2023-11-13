<?php

namespace Drupal\exchange_rates\Adapter;

use Drupal\Core\Http\Client;


class Provider1Adapter implements ExchangeRateAdapterInterface
{
  public function getExchangeRate()
  {
    $client = \Drupal::httpClient();
    try {
      $response = $client->get('https://run.mocky.io/v3/50a30c28-89a5-463e-83c6-13420ebac4ef');

      // Check if the request was successful (status code 200).
      if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody(), true);
        \Drupal::logger('exchange_rates')->notice('API request succeeded: ' . $response->getStatusCode());
        $minValue = min($data);
        $minKey = array_search($minValue, $data);
        return array('provider' => 'Provider1', 'minValue' => $minValue, 'minKey' => $minKey);

      } else {
        \Drupal::logger('exchange_rates')->error('API Request failed with status code: ' . $response->getStatusCode());
      }
    } catch (\Exception $e) {
      \Drupal::logger('exchange_rates')->error('API Request failed: ' . $e->getMessage());
    }
  }
}
