<?php

namespace Drupal\purchase_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides route responses for the Example module.
 */
class PurchaseForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId() {
    return 'purchase_form_api';
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
    );
    $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    );
    $form['candidate_number'] = array (
      '#type' => 'tel',
      '#title' => t('Mobile Number'),
      '#required' => TRUE,
    );
    $form['candidate_address'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Address:'),
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
    $name = $form_state->getValue('candidate_name');
    $email = $form_state->getValue('candidate_mail');
    $phone = $form_state->getValue('candidate_number');
    $db = \Drupal::database();
    $result = $db->select('purchase_form', 'pf')
    ->fields('pf', ['candidate_name'])
    ->condition('pf.candidate_name', $name, '=')
    ->execute()
    ->fetchAll();
    foreach ($result as $value) {
      $ename = $value->candidate_name;
    }
     $result1 = $db->select('purchase_form', 'pf')
    ->fields('pf', ['candidate_mail'])
    ->condition('pf.candidate_mail', $email, '=')
    ->execute()
    ->fetchAll();
    foreach ($result1 as $value) {
      $mail = $value->candidate_mail;
    }
    $result2 = $db->select('purchase_form', 'pf')
    ->fields('pf', ['candidate_number'])
    ->condition('pf.candidate_number', $phone, '=')
    ->execute()
    ->fetchAll();
    foreach ($result2 as $value) {
      $number = $value->candidate_number;
    }  
    if((!empty($ename)) && (!empty($mail)) && (!empty($number))) {
      $form_state->setErrorByName('candidate_name', 'Name already exists!');
      $form_state->setErrorByName('candidate_mail', 'Email already exists!');
      $form_state->setErrorByName('candidate_number','Phone number already exists!');
    }
    elseif(!empty($ename)) {
      $form_state->setErrorByName('candidate_name', 'Name already exists!');
    }
    elseif(!empty($mail)) {
      $form_state->setErrorByName('candidate_mail', 'Email already exists!');
    }
    elseif(!empty($number)) {
      $form_state->setErrorByName('candidate_number','Phone number already exists!');
    }
    /*if(!empty($ename)) {
      $form_state->setErrorByName('candidate_name', 'Name already exists!');
    }
    elseif(!empty($mail)) {
      $form_state->setErrorByName('candidate_mail', 'Email already exists!');
    }
    else{
      $form_state->setErrorByName('candidate_name', 'Name already exists!');
      $form_state->setErrorByName('candidate_mail', 'Email already exists!');
    }

    /*$name = $form_state->getValue('candidate_name');
    $email = $form_state->getValue('candidate_mail');
    $db = \Drupal::database();
    $result = $db->select('purchase_form', 'pf')
    ->fields('pf', ['candidate_name'])
    ->condition('pf.candidate_name', $name, '=')
    ->execute()
    ->fetchAll();
    foreach ($result as $value) {
      $ename = $value->candidate_name;
    }*/
    /*if((!empty($ename)) || (!empty($mail))) {
      $form_state->setErrorByName('candidate_name', 'Name already exists!');
      $form_state->setErrorByName('candidate_mail', 'Email already exists!');
    }*/
    /*if(($ename = $name) || ($mail = $email)) {
      $form_state->setErrorByName('candidate_name', 'Name already exists!');
      $form_state->setErrorByName('candidate_mail', 'Email already exists!');
    }*/
    /*if(($ename = $name) || ($mail = $email)) {
      if(($ename = $name) && ($mail != $email)) {
        $form_state->setErrorByName('candidate_name', 'Name already exists!');
      elseif(($ename != $name) && ($mail = $email))
        $form_state->setErrorByName('candidate_mail', 'Email already exists!');
        //$form_state->setErrorByName('candidate_name', 'Name already exists!');
      }
      else {
        $form_state->setErrorByName('candidate_name', 'Name already exists!');
        $form_state->setErrorByName('candidate_mail', 'Email already exists!');
      }
    }*/
    /*$email = trim($element['candidate_mail']);
    $form_state->setValueForElement($element, $email);

    if ($email !== '' && !\Drupal::service('email.validator')->isValid($email)) {
      $form_state->setError($element, t('The email address %mail is not valid.', array('%mail' => $email)));
    }*/
    if (!$form_state->getValue('candidate_mail') || !filter_var($form_state->getValue('candidate_mail'), FILTER_VALIDATE_EMAIL)) {
        $form_state->setErrorByName('candidate_mail', $this->t('Invalid Email Format'));
    }
    if (!preg_match('/^[0-9]{10}+$/', $phone)) {
      $form_state->setErrorByName('candidate_number', $this->t('Invalid phone number'));
    }
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $db = \Drupal::database();
    $query = $db->insert('purchase_form');
    $query->fields([
      'candidate_name' => $form_state->getValue('candidate_name'),
      'candidate_mail' => $form_state->getValue('candidate_mail'),
      'candidate_number' => $form_state->getValue('candidate_number'),
      'address' => $form_state->getValue('candidate_address'),
      ])
    ->execute();
  }
}