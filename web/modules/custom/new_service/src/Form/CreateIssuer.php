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
    $form['badgr_type_options'] = array(
    '#type' => 'value',
    '#value' => array(
      'None' => t('--- SELECT ---'),
      'Create' => t('Create'),
      'Update' => t('Update'),
      'List' => t('List')),
    );
    $form['badgr_option'] = array(
      '#title' => t('Badger options'),
      '#type' => 'select',
      '#description' => "Select the type of operation",
      '#options' => $form['badgr_type_options']['#value'],
    );
    $form['badgr_entity_id'] = [
      '#type' => 'textfield',
      '#title' => t('Issuer entity ID'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
        'invisible' => [
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
      ],
    ];
    $form['badge_entity_id'] = [
      '#type' => 'textfield',
      '#title' => t('Badger entity ID'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
        'invisible' => [
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
      ],
    ];    
    $form['badgr_image'] = [
      '#type' => 'managed_file',
      '#title' => t('Image:'),
      '#upload_location' => 'public://badgr_image',
      //'#required' => TRUE,
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
        'invisible' => [
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
      ],
    ];
    $form['badgr_name'] = [
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
        'invisible' => [
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
      ],
    ];
    $form['badgr_description'] = [
      '#type' => 'textfield',
      '#title' => t('Description:'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
        'invisible' => [
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
      ],
    ];
    $form['badgr_criteriaUrl'] = [
      '#type' => 'textfield',
      '#title' => t('criteriaUrl:'),
      '#states' => [
        'optional' => [
          // 'select[name="action"]' => ['value' => 'update'],
          ['select[name="badgr_option"]' => ['value' => 'Create']],
          ['select[name="badgr_option"]' => ['value' => 'Update']],
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
        'invisible' => [
          ['select[name="badgr_option"]' => ['value' => 'List']],
          ['select[name="badgr_option"]' => ['value' => 'None']],
        ],
      ],
    ];
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
    $badgr_entity_id = $form_state->getValue('badgr_entity_id');
    $badge_entity_id = $form_state->getValue('badge_entity_id');
    $badgr_option = $form_state->getValue('badgr_option');
    $badgr_name = $form_state->getValue('badgr_name');
    $badgr_description = $form_state->getValue('badgr_description');
    $badgr_criteriaUrl = $form_state->getValue('badgr_criteriaUrl');
    if(($badgr_option == ('Create')) || ($badgr_option == ('Update'))) {
      $badgr_image = $form_state->getValue('badgr_image');
      foreach ($badgr_image as $value) {
        //ddl($value);
        }
      $fid = $value;//The file ID
      //ddl($fid);
      $file = \Drupal\file\Entity\File::load($fid);
      $path = $file->getFileUri();
      $img_file = file_get_contents($path);
      $ext = pathinfo($path, PATHINFO_EXTENSION);
      $base64img = base64_encode($img_file);
      $base64img = "data:image/{$ext};base64,{$base64img}";
      }   
    $service = \Drupal::service('new_service.demo_badge');
    $config = $this->config('new_service.settings');
    $username = $config->get('username');
    $password = $config->get('password');
    $post_data = ['username' => $username,'password' => $password];    
    $result = $service->badgr_initiate($post_data);
    $rt = $result['refreshtoken'];
    $accessToken = $result['accesstoken'];
    $badge_details = ['image' => $base64img, 'name' => $badgr_name, 'description' => $badgr_description, 'criteriaUrl' => $badgr_criteriaUrl];
    $update_badge_details = ['image' => $base64img, 'name' => $badgr_name, 'description' => $badgr_description, 'criteriaUrl' => $badgr_criteriaUrl];
    //$service->badgr_create_issuer_badges($accessToken, $badge_details);

    if ($badgr_option == ('List')) {
     $ands = $service->badgr_list_all_badges($accessToken);
     dsm($ands);
    }
    elseif ($badgr_option == ('Create')) {
      $service->badgr_create_issuer_badges($accessToken, $badgr_entity_id, $badge_details);
    }
    elseif ($badgr_option == ('Update')) {
      $service->badgr_update_badges($accessToken, $update_badge_details, $badge_entity_id);
    }
    /*elseif ($badgr_option == ('Delete')) {
      $service->badgr_delete_issuer($accessToken,$badge_entity_id);
    }*/
  }
}