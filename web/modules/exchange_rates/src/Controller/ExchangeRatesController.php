<?php


namespace Drupal\exchange_rates\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

//use Drupal\exchange_rates\Adapter\ExchangeRateAdapterInterface;
//use Drupal\Core\Database\Connection;


class ExchangeRatesController extends ControllerBase
{

  public function __construct(array $providerAdapters)
  {
    $this->providerAdapters = $providerAdapters;
  }


  public static function create(ContainerInterface $container)
  {
    $providerAdapters = [
      'provider1' => $container->get('exchange_rates.provider1_adapter'),
      'provider2' => $container->get('exchange_rates.provider2_adapter'),
      // Add adapters for existing providers here.
    ];

    return new static($providerAdapters);
  }


  public function content()
  {

    $minValue = PHP_FLOAT_MAX; // Set to a very high value to start the comparison
    $minCurrency = '';
    $minProvider = '';

    foreach ($this->providerAdapters as $provider => $adapter) {
      $data = $adapter->getExchangeRate();

      if ($data['minValue'] < $minValue) {
        $minValue = $data['minValue'];
        $minCurrency = $data['minKey'];
        $minProvider = $data['provider'];
      }
    }

    // Save To Database

    //$this->saveToDatabase($minProvider,$minValue,$minCurrency);

    return [
      '#theme' => 'exchange_rates',
      '#Provider' => $minProvider,
      '#Currency' => $minCurrency,
      '#Rate' => $minValue,
    ];
  }

  /*   protected function saveToDatabase($provider, $exchangeRate,$minCurrency) {
        $connection = \Drupal::service('database');
        $result= $connection->insert('exchange_rate')
            ->fields([
                'provider' => $provider,
                'currency' => $minCurrency,
                'rate'     => $exchangeRate,
            ])
            ->execute();
    } */
}
