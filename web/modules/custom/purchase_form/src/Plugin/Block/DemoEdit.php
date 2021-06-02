<?php

namespace Drupal\purchase_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "purchase_block",
 *   admin_label = @Translation("New block"),
 *   category = @Translation("Custom purchace block example")
 * )
 */

class DemoEdit extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    /*$node = \Drupal\node\Entity\Node::create(['type' => 'article']);
    $form = \Drupal::service('entity.form_builder')->getForm($node);
    return $form;*/
  }
}
