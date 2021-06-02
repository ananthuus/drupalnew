<?php

/**
 * @file providing the service of badge API.
 *
*/

namespace  Drupal\new_service;

use GuzzleHttp\ClientInterface;
use Drupal\Component\Serialization\Json;
use \Drupal\Core\Session\AccountInterface;


class TestBadgeServices {
  /**
   * current user.
   *
   * @user \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * http client.
   *
   * @user \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * construct new badgr service objects.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   */

  public function __construct(ClientInterface $http_client, AccountInterface $current_user) {
    $this->httpClient = $http_client;
    $this->currentUser = $current_user;
  }
  /**
   * initiating badgr
   *
   */
  /*public function badgr_initiate() {
    $request = $this->httpClient->request('POST', 'https://api.badgr.io/o/token', [
      'form_params' =>[
        'username' => 'ananthus4urs@gmail.com',
        'password' => 'Kochuparambilunni93@'],
      ]);*/
  public function badgr_initiate(array $post_data) {
    $request = $this->httpClient->request('POST', 'https://api.badgr.io/o/token', [
    'form_params' => $post_data
      ],
    );
    $values = $request->getBody()->getContents();
    $access_token = json_decode($values)->access_token;
    $refresh_token = json_decode($values)->refresh_token;
    $token = array('accesstoken' => $access_token, 'refreshtoken' => $refresh_token);
    return $token;
  }

  public function badgr_refresh_token($refresh_token) {
    $re_token = $this->httpClient->request('POST', 'https://api.badgr.io/o/token', [
      'form_params' =>[
        'grant_type' => 'refresh_token',
        'refresh_token' => $refresh_token],
    ])->getBody()->getContents();
    return $re_token;
  }

  /*public function badgr_user_authenticate($accessToken) {
     $getContent = $this->httpClient->request('GET', 'https://api.badgr.io/v2/users/self', [
      'headers' =>[
        'Authorization' => 'Bearer' . $accessToken],
    ]);
    $authorization = $getContent->getBody()->getContents();
    return $authorization;
  }*/

  public function badgr_user_authenticate($accessToken) {
    $authenticate = $this->httpClient->request('GET', 'https://api.badgr.io/v2/users/self', [
        'headers' => [
          'Authorization'=> 'Bearer '.$accessToken],
        ]);
    $detail  = $authenticate->getBody()->getContents();
    return $detail;
  }


 /* public function badgr_create_issuer($accessToken, array $post_details) {
    $isuerContent = $this->httpClient->request('POST', 'https://api.badgr.io/v2/issuers', [
      'form_params' => $post_details,
        'headers' => ['Authorization'=> 'Bearer '.$accessToken],
          ])->getBody()->getContents();*/

           public function badgr_create_issuer($accessToken) {
    $isuerContent = $this->httpClient->request('POST', 'https://api.badgr.io/v2/issuers', [
      'form_params' => ['name' => 'rahul',
        'email' => 'rahul@gmail.com',
        'description' => 'hiiiiiiiiiiiiiiiiiiiiiiiiiiiiii',
        'url' => 'https://www.powercms.in/'],
      'headers' => ['Authorization'=> 'Bearer '.$accessToken],
          ])->getBody()->getContents();

    //$createIssuer = $isuerContent->getBody()->getContents();
    //return $createIssuer;
    return $isuerContent;
  }

}







//$service = \Drupal::service('new_service.demo_badge');
//dsm($service->badgr_initiate());


/*$service = \Drupal::service('new_service.demo_badge');
$token = $service->badgr_initiate();
$rt = $token['refreshtoken'];
dsm($service->badgr_refresh_token($rt));*/



/*$service = \Drupal::service('new_service.demo_badge');
$uname = 'ananthus4urs@gmail.com';
$pword = 'Kochuparambilunni93@';
dsm($service->badgr_initiate($uname ,$pword));

$service = \Drupal::service('new_service.demo_badge');
$token = $service->badgr_initiate($uname ,$pword);
$rt = $token['refreshtoken'];
dsm($service->badgr_refresh_token($rt));*/


/*$service = \Drupal::service('new_service.demo_badge');
$token = $service->badgr_initiate($uname ,$pword);
$at = $token['accesstoken'];
dsm($service->badgr_user_authenticate($at));*/




/*$service = \Drupal::service('new_service.demo_badge');
$uname = 'ananthus4urs@gmail.com';
$pword = 'Kochuparambilunni93@';

$token = $service->badgr_initiate($uname ,$pword);
$rt = $token['refreshtoken'];
$accessToken = $token['accesstoken'];

dsm($service->badgr_initiate($uname ,$pword));
dsm($service->badgr_refresh_token($rt));
dsm($service->badgr_user_authenticate($accessToken));*/



/*$service = \Drupal::service('new_service.demo_badge');

$post_data = ['username' => 'ananthus4urs@gmail.com', 'password' => 'Kochuparambilunni93@'];

$token = $service->badgr_initiate($post_data);
//$rt = $token['refreshtoken'];
$accessToken = $token['accesstoken'];

dsm($service->badgr_initiate($post_data));
//dsm($service->badgr_refresh_token($rt));
dsm($service->badgr_user_authenticate($accessToken));
dsm($service->badgr_create_issuer($accessToken));*/