<?php

namespace Drupal\new_service\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\new_service\TestBadgeServices;

/**
 * Provides route responses for the Example module.
 */
class CreateIssuer extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'badgr_form_api';
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /*$form['my_file'] = array(
      '#type' => 'managed_file',
      '#title' => 'my file',
      '#name' => 'my_custom_file',
      '#description' => $this->t('my file description'),
      '#default_value' => $config->get('my_file'),
      '#upload_location' => 'public://'
    ); */
    /*$form['badgr_image'] = array(
      '#type' => 'managed_file',
      '#title' => t('Image:'),
      '#required' => TRUE,
    ); */

    $form['badgr_image'] = array(
      '#type' => 'managed_file',
      '#title' => t('Upload Zip File'),
      '#upload_validators' => array(
        'file_validate_extensions' => array('zip ZIP'),
        'file_validate_size' => array(MAX_FILE_SIZE*1024*1024),
      ),
    );

    $form['badgr_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#required' => TRUE,
    );  
    $form['badgr_description'] = array(
      '#type' => 'textfield',
      '#title' => t('Description:'),
      '#required' => TRUE,
    ); 
     $form['badgr_criteriaUrl'] = array(
      '#type' => 'textfield',
      '#title' => t('criteriaUrl:'),
      '#required' => TRUE,
    ); 
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
   
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {

    //$path = 'myfolder/myimage.png';
    /*$badgr_image = $form_state->getValue('badgr_image');
    $type = pathinfo( $badgr_image, PATHINFO_EXTENSION);
    $data = file_get_contents($badgr_image);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ddl($base64);*/

    /*$file = $form_state->getValue('badgr_image');
    ksm($file);
    ddl($file);*/

  /*  $fid = reset($form_state->getValue('badgr_image'));
    File::load($fid);*/


    //$image = base64_decode($array->'badgr_image');
    /*$image = file_get_contents('badgr_image');
    $base = base64_encode($image);
    ddl($base);*/
    /*$badgr_image = $form_state->getValue('badgr_image');
    $badgr_name = $form_state->getValue('badgr_name');
    $badgr_description = $form_state->getValue('badgr_description');
    $badgr_criteriaUrl = $form_state->getValue('badgr_criteriaUrl');
    
    $service = \Drupal::service('new_service.demo_badge');
    $config = $this->config('new_service.settings');
    $username = $config->get('username');
    $password = $config->get('password');
    $post_data = ['username' => $username,'password' => $password];
    
    $result = $service->badgr_initiate($post_data);
    $rt = $result['refreshtoken'];
    $accessToken = $result['accesstoken'];

    $badge_details = ['image' => $badgr_image, 'name' => $badgr_name, 'description' => $badgr_description, 'criteriaUrl' => $badgr_criteriaUrl];
    $service->badgr_create_issuer_badges($accessToken, $badge_details);*/

  }
}