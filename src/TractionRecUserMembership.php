<?php

namespace Drupal\openy_traction_rec;

use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\openy_traction_rec\TractionRecClient;
use GuzzleHttp\Exception\GuzzleException;

class TractionRecUserMembership {

  /**
   * Traction Rec Client service.
   *
   * @var \Drupal\openy_traction_rec\TractionRecClient
   */
  protected $tractionRecClient;

  /**
   * Logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannel
   */
  protected $logger;

  /**
   * Constructors TractionRecFetcher.
   *
   * @param \Drupal\openy_traction_rec\TractionRecClient $traction_rec_client
   *    The TractionRec API client.
   * @param \Drupal\Core\Logger\LoggerChannelInterface $loggerChannel
   *    Logger channel.
   */
  public function __construct(TractionRecClient $traction_rec_client, LoggerChannelInterface $loggerChannel) {
    $this->tractionRecClient = $traction_rec_client;
    $this->logger = $loggerChannel;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserObjectByEmail($email) {
    try {
      $result = $this->tractionRecClient->executeQuery("SELECT Id,
          AccountId,
          Email,
          Salutation,
          Name,
          Phone,
          Fax,
          Birthdate,
          PhotoUrl,
          YN_Nickname__c,
          YN_Virtual_Y_Access__c
        FROM Contact
        WHERE Email = '" . $email . "'");

      return $this->simplify($result);
    }
    catch (\Exception | GuzzleException $e) {
      $message = 'Can\'t load the User: ' . $e->getMessage();
      $this->logger->error($message);
      return [];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getUserObjectById($id) {
    try {
      $result = $this->tractionRecClient->executeQuery("SELECT Id,
          AccountId,
          Email,
          Salutation,
          Name,
          Phone,
          Fax,
          Birthdate,
          PhotoUrl,
          YN_Nickname__c,
          YN_Virtual_Y_Access__c
        FROM Contact
        WHERE Id = '" . $id . "'");

      return $this->simplify($result);
    }
    catch (\Exception | GuzzleException $e) {
      $message = 'Can\'t load the User: ' . $e->getMessage();
      $this->logger->error($message);
      return [];
    }
  }

  /**
   * Cleans up TractionRec extra prefixes and suffixes for easier usage.
   *
   * @param array $array
   *   Response result from Traction Rec.
   *
   * @return array
   *   Results with cleaned keys.
   */
  private function simplify(array $array): array {
    $new_array = [];
    foreach ($array as $key => $value) {
      $new_key = str_replace(['TREX1__', '__c', '__r'], '', $key);
      if ($new_key === 'attributes') {
        continue;
      }
      if (is_array($value)) {
        $new_array[$new_key] = $this->simplify($value);
      }
      else {
        $new_array[$new_key] = $value;
      }
    }
    return $new_array;
  }
}
