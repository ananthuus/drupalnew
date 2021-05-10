<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides route responses for the Example module.
 */
class SimpleForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId()
  {
    return 'codimth_simple_form_api';
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

   $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Customer details'),
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#size' => 60,
      '#maxlength' => 128,
    ];
    
    $form['test_checkboxes'] = [
      '#type' => 'checkboxes',
      '#options' => ['drupal' => $this->t('Drupal'), 'wordpress' => $this->t('Wordpress')],
      '#title' => $this->t('Are you developer :'),
    ];
    return $form;
  }
   public function validateForm(array &$form, FormStateInterface $form_state)
  {

  }
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }
}
