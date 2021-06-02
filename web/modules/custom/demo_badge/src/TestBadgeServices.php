<?php

/**
 * @file providing the service of badge API.
 *
*/

namespace  Drupal\demo_badge;


class TestBadgeServices {

  /**
   * current user
   *
   * @user \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * An http client.
   *
   * @user \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * construct new badgr service objects.
   *
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   * @param \GuzzleHttp\ClientInterface $http_client
   */

  public function__construct(AccountInterface $currentUser ,ClientInterface $http_client) {
    $this->currentUser = $currentUser;
    $this->httpClient = $http_client;
  }

  /**
   * initiating badgr
   *
   */
  public function badgr_initiate(array $post_data) {
    $this->httpClient->request(method:'POST', uri:'https://api.badgr.io/o/token', -d $post_data) [

    //$request = $this->httpClient->request(method:'POST', uri:BADGR_TOKEN_API, [
      'verify' => true,
      'form_params' => $post_data
    ]);
    $access_token_data = json::decode($request->getBody()->getContents());
  }
  return $access_token_data;
}