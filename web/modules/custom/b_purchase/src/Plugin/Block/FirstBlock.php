<?php

namespace Drupal\b_purchase\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'article' block.
 *
 * @Block(
 *   id = "purchase_block",
 *   admin_label = @Translation("Purchase block"),
 *   category = @Translation("Custom purchace block example")
 * )
 */

class FirstBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\purchase_form\Form\PurchaseForm');
    return $form;
  }
}

