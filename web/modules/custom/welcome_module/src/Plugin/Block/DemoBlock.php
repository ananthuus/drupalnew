<?php

namespace Drupal\welcome_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "welcome_block",
 *   admin_label = @Translation("Welcome block"),
 *   category = @Translation("Custom purchace block example")
 * )
 */

class DemoBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal\node\Entity\Node::create(['type' => 'article']);
    $form = \Drupal::service('entity.form_builder')->getForm($node);
    return $form;
  }
}
