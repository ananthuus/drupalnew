<?php

namespace Drupal\welcome_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'players' block.
 *
 * @Block(
 *   id = "Test_block",
 *   admin_label = @Translation("Test block"),
 *   category = @Translation("Custom Test block example")
 * )
 */

class TestBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal\node\Entity\Node::create(['type' => 'players']);
    $form = \Drupal::service('entity.form_builder')->getForm($node);
    return $form;
  }
}
