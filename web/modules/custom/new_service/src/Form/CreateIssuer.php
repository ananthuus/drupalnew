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

    
    /*$badgr_name = $form_state->getValue('badgr_name');
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


    $post_details = ['name' => $user_name,'url' => $website_url,'email' => $mail_id,'Description' => $description];
    $result = $service->badgr_initiate($post_data);
    $issuer = $service->badgr_create_issuer($accessToken,$post_details);*/


    //ddl($result);
    //echo $result;
    //$form_state->setErrorByName($result);
  }
}