<?php

namespace Drupal\welcome_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class DemoController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function demoUser() {
    $user = \Drupal::currentUser()->id();
    $result = \Drupal::entityQuery('node')
      ->condition('type', 'players')
      ->condition('uid', $user)
      ->execute();
    $nodes = \Drupal\node\Entity\Node::loadMultiple($result);
    foreach ($nodes as $node) {
      $title .= $node->getTitle();
    }
    return [
      '#markup' => $title,
    ];
  }
}
