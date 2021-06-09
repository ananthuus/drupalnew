<?php

/**
 * @file
 * Contains Drupal\new_service\Form\ModuleConfigurationForm.
 */

namespace Drupal\new_service\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\new_service\TestBadgeServices;

/**
 * Provides route responses for the Example module.
 */
class ModuleConfigurationForm extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'new_service.settings',
    ];
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('new_service.settings');
    $form['username'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email ID:'),
      '#default_value' => $config->get('email'),
    );  
    $form['password'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Password:'),
      '#default_value' => $config->get('password'),
    );
    /*$form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;*/
    return parent::buildForm($form, $form_state);
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state); 
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    /*$db = \Drupal::database();
    $query = $db->insert('purchase_form');
    $query->fields([
      'candidate_name' => $form_state->getValue('candidate_name'),
      'candidate_mail' => $form_state->getValue('candidate_mail'),
      'candidate_number' => $form_state->getValue('candidate_number'),
      'address' => $form_state->getValue('candidate_address'),
      ])
    ->execute();*/
    /*$service = \Drupal::service('new_service.demo_badge');
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    $post_data = ['username' => $username,'password' => $password];
    $result = $service->badgr_initiate($post_data);*/
    //ddl($result);
    //echo $result;
    //$form_state->setErrorByName($result);
    $this->config('new_service.settings')
      ->set('username', $form_state->getValue('username'))
      ->set('password', $form_state->getValue('password'))
      ->save();
    //ddl($Config);                                                        
    //$username = $config->get('username');
    //$password = $config->get('password');
    //ddl($username);
    //ddl($password);
    //return $username;
    //$service = \Drupal::service('new_service.demo_badge');
    //$post_data = ['username' => $username,'password' => $password];
    //$result = $service->badgr_initiate($post_data);
    //ddl($result);
    //return $result;
  }
}