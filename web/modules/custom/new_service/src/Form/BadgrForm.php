<?php

namespace Drupal\new_service\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\new_service\TestBadgeServices;

/**
 * Provides route responses for the Example module.
 */
class BadgrForm extends FormBase {

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

    $form['list_button'] = array(
      '#type' => 'submit',
      '#value' => t('List Issuers'),
    );

    $form['user_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#required' => TRUE,
    ); 
    $form['website_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Website URL:'),
      '#required' => TRUE,
    );  
    $form['mail_id'] = array(
      '#type' => 'email',
      '#title' => t('Contact Email:'),
      '#required' => TRUE,
    );
    $form['description'] = array(
      '#type' => 'textfield',
      '#title' => t('Description:'),
      '#required' => TRUE,
    ); 
    /*$form['issuer_option'] = array(
      '#title' => t('select option'),
      '#type' => 'select',
      '#description' => 'Select the option.',
      '#options' => array(t('--- SELECT ---'), t('Create'), t('Update')),
    );*/

    $form['type_options'] = array(
    '#type' => 'value',
    '#value' => array(
      'None' => t('--- SELECT ---'),
      'Create' => t('Create'),
      'Update' => t('Update'),
      'List' => t('List'))
    );
    $form['issuer_option'] = array(
      '#title' => t('Project Type'),
      '#type' => 'select',
      '#description' => "Select the type of operation",
      '#options' => $form['type_options']['#value'],
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
   
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*$db = \Drupal::database();
    $query = $db->insert('purchase_form');
    $query->fields([
      'candidate_name' => $form_state->getValue('candidate_name'),
      'candidate_mail' => $form_state->getValue('candidate_mail'),
      'candidate_number' => $form_state->getValue('candidate_number'),
      'address' => $form_state->getValue('candidate_address'),
      ])
    ->execute();*/
    $list_button = $form_state->getValue('list_button');
    $issuer_option = $form_state->getValue('issuer_option');
    $user_name = $form_state->getValue('user_name');
    $website_url = $form_state->getValue('website_url');
    $mail_id = $form_state->getValue('mail_id');
    $description = $form_state->getValue('description');

    $service = \Drupal::service('new_service.demo_badge');
    $config = $this->config('new_service.settings');
    $username = $config->get('username');
    $password = $config->get('password');
    $post_data = ['username' => $username,'password' => $password];
    
    $result = $service->badgr_initiate($post_data);
    //ddl($result);
    //$rt = $result['refreshtoken'];
    $accessToken = $result['accesstoken'];
    ddl($accessToken);
    $post_details = ['name' => $user_name, 'url' => $website_url, 'email' => $mail_id, 'Description' => $description];
    $update_details = ['name' => $user_name, 'url' => $website_url, 'email' => $mail_id, 'Description' => $description];
    $entity_id = 'GgYNzGRySuOsFrpPcjkXMg';
    if ($issuer_option == ('Create')) {
      $service->badgr_create_issuer($accessToken,$post_details);
     } 
    elseif ($issuer_option == ('Update')) {
      $service->badgr_update_issuer($accessToken, $entity_id, $update_details);
     } 
    elseif($list_button == ('List Issuers')) {
      dsm($service->badgr_read_issuer($accessToken));
      //$values = $result->getBody()->getContents();
      //$entityId = json_decode($result)->entityId;
      //$token = array('entityId' => $entityId);
      
      //dsm($entityId);
    } 
    //ddl($result);
    //echo $result;
    //$form_state->setErrorByName($result);
  }
}