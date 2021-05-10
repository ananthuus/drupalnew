<?php

namespace Drupal\custom_slider\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class SliderController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function mySlider() {
    return [
      '#markup' => 'Hello, this is a slider',
    ];
  }

}
