<?php

namespace Drupal\ipless;

use Drupal\Core\Asset\AttachedAssets;
use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Render\HtmlResponse;
use Drupal\Core\State\StateInterface;
use Drupal\ipless\Asset\AssetRendererInterface;


/**
 * Description of Ipless.
 */
class Ipless implements IplessInterface {

  use MessengerTrait;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * @var \Drupal\ipless\Asset\AssetRendererInterface
   */
  protected $assetRenderer;

  /**
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Ipless constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\ipless\Asset\AssetRendererInterface $assetRenderer
   */
  public function __construct(ConfigFactoryInterface $configFactory, MessengerInterface $messenger, AssetRendererInterface $assetRenderer, LibraryDiscoveryInterface $libraryDiscovery, ModuleHandlerInterface $moduleHandler, ThemeHandlerInterface $themeHandler, FileSystemInterface $fileSystem, StateInterface $state) {
    $this->configFactory    = $configFactory;
    $this->messenger        = $messenger;
    $this->assetRenderer    = $assetRenderer;
    $this->libraryDiscovery = $libraryDiscovery;
    $this->moduleHandler    = $moduleHandler;
    $this->themeHandler     = $themeHandler;
    $this->fileSystem       = $fileSystem;
    $this->state            = $state;

    $this->config = $this->configFactory->get('system.performance');
  }

  /**
   * {@inheritdoc}
   */
  public function processOnResponse(HtmlResponse $response) {
    $assets = $this->getResponseAssets($response);
    $this->generate($assets->getLibraries());
  }

  public function getResponseAssets(HtmlResponse $response) {
    $attached = $response->getAttachments();

    unset($attached['html_response_attachment_placeholders']);

    return AttachedAssets::createFromRenderArray(['#attached' => $attached]);
  }

  /**
   * {@inheritdoc}
   */
  public function generate($libraries, $time = NULL) {
    if (!$this->checkLib()) {
      return;
    }
    return $this->generateCss($libraries, $time);
  }

  /**
   * Check that the library Less php is installed.
   *
   * @return bool
   */
  protected function checkLib() {
    if (!class_exists('Less_Parser')) {
      $this->messenger()->addWarning('The class lessc is not installed.');
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function mustRebuildAll() {
    return $this->state->get('ipless.force_rebuild');
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    return (bool) $this->config->get('ipless.enabled');
  }

  /**
   * {@inheritdoc}
   */
  public function isWatchModeEnable() {
    return (bool) $this->isModeDevEnabled() && $this->config->get('ipless.watch_mode');
  }

  /**
   * {@inheritdoc}
   */
  public function isModeDevEnabled() {
    return (bool) $this->config->get('ipless.modedev');
  }

  /**
   * Generate Less files.
   *
   * @param array $libraries
   *   Array of libraries to compile.
   * @param int $time
   *   Timestamp, is set, only file edited after this date is generated.
   *
   * @return array
   *   The list of libraries generated.
   */
  protected function generateCss($libraries, $time = NULL) {
    return $this->assetRenderer->render($libraries, $time);
  }

  /**
   * {@inheritdoc}
   */
  public function generateAllLibraries() {

    $modules = $this->moduleHandler->getModuleList();
    $themes  = $this->themeHandler->rebuildThemeData();

    $extensions = array_merge($modules, $themes);

    $libraries = [];
    foreach (array_keys($extensions) as $extension_name) {
      $ext_libs = $this->libraryDiscovery->getLibrariesByExtension($extension_name);
      foreach ($ext_libs as $library_name => $lib_info) {
        if ($library_name != 'drupalSettings') {
          $libraries[] = "$extension_name/$library_name";
        }
      }
    }

    $this->generate($libraries);
    // Disable the rebuild.
    $this->askForRebuild(FALSE);
  }

  /**
   * {@inheritdoc}
   */
  public function askForRebuild($rebuild_need = TRUE) {
    $this->state->set('ipless.force_rebuild', $rebuild_need);
  }

  /**
   * {@inheritdoc}
   */
  public function flushFiles() {
    if (method_exists($this->fileSystem, 'deleteRecursive')) {
      $this->fileSystem->deleteRecursive('public://ipless/');
    }
    else {
      // This is to keep compatibility with Drupal <= 8.7.x.
      file_unmanaged_delete_recursive('public://ipless/');
    }
  }

}
