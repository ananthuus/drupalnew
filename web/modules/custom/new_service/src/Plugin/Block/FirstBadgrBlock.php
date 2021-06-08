<?php

namespace Drupal\new_service\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "badgr_block",
 *   admin_label = @Translation("Badger block"),
 *   category = @Translation("Custom badgr block example")
 * )
 */

class FirstBadgrBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\new_service\Form\BadgrForm');
    return $form;
  }
}

