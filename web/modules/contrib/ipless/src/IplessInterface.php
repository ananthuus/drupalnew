<?php

namespace Drupal\ipless;

use Drupal\Core\Render\HtmlResponse;

/**
 * IplessInterface.
 */
interface IplessInterface {

  /**
   * Check configuration and generate Less files.
   *
   * @param array $libraries
   *   Array of libraries to generate. [0 => foo/bar, 1 => example/example]
   * @param int $time
   *   If set only library edited after this time was generated.
   *
   * @return array
   *   The list of libraries compiled.
   */
  public function generate($libraries, $time = NULL);

  /**
   * Flush all compiled files.
   */
  public function flushFiles();

  /**
   * Compile all Less files on HTML response.
   *
   * @param HtmlResponse $response
   */
  public function processOnResponse(HtmlResponse $response);

  /**
   * Ask for rebuild all libraries.
   */
  public function askForRebuild();

  /**
   * Compile Less file present on all libraries.
   */
  public function generateAllLibraries();

  /**
   * Return true if the libraries must be rebuild.
   *
   * @return bool
   */
  public function mustRebuildAll();

  /**
   * Indicate if the LESS compilation is enabled.
   *
   * @return bool
   */
  public function isEnabled();

  /**
   * Indicate if the watch mode is enabled.
   *
   * @return bool
   */
  public function isWatchModeEnable();

  /**
   * Indicate if the LESS dev mode is enabled.
   *
   * @return bool
   */
  public function isModeDevEnabled();
}
