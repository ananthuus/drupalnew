<?php

namespace Drupal\ipless\Asset;

use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Asset\AttachedAssets;
use Less_Parser;
use Drupal\ipless\Event\IplessCompilationEvent;
use Drupal\ipless\Event\IplessEvents;

/**
 * Description of AssetRenderer
 */
class AssetRenderer implements AssetRendererInterface {

  use StringTranslationTrait;

  /**
   * Less Preprocessor.
   *
   * @var Less_Parser
   */
  protected $less;

  /**
   * Theme Handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * Library Discovery
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected $libraryDiscovery;

  /**
   * Theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * Config Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Less Asset Resolver.
   *
   * @var \Drupal\ipless\Asset\AssetResolverInterface
   */
  protected $assetResolver;

  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * AssetRenderer constructor.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $library_discovery
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\ipless\Asset\AssetResolverInterface $asset_resolver
   * @param $event_dispatcher
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   */
  public function __construct(ThemeHandlerInterface $theme_handler, LibraryDiscoveryInterface $library_discovery, ThemeManagerInterface $theme_manager, ConfigFactoryInterface $config_factory, AssetResolverInterface $asset_resolver, $event_dispatcher, MessengerInterface $messenger, AccountProxyInterface $currentUser, FileSystemInterface $fileSystem) {
    $this->themeHandler     = $theme_handler;
    $this->libraryDiscovery = $library_discovery;
    $this->themeManager     = $theme_manager;
    $this->configFactory    = $config_factory;
    $this->assetResolver    = $asset_resolver;
    $this->eventDispatcher  = $event_dispatcher;
    $this->messenger        = $messenger;
    $this->currentUser      = $currentUser;
    $this->fileSystem       = $fileSystem;
  }

  /**
   * {@inheritdoc}
   */
  public function render($libraries, $time = NULL) {
    $build['#attached']['library'] = $libraries;

    $assets = AttachedAssets::createFromRenderArray($build);

    $less_assets = $this->assetResolver->getLessAssets($assets);

    if ($time) {
      foreach ($less_assets as $key => $asset) {
        // Limit the list of assets to that need to by refreshed.
        if (filemtime($asset['data']) <= $time) {
          unset($less_assets[$key]);
        }
      }
    }

    foreach ($less_assets as $asset_options) {
      $this->compile($asset_options['data'], $asset_options);
    }
    return $less_assets;
  }

  /**
   * Compile Less file.
   *
   * @param string $file
   *   The less file path.
   * @param array $options
   *   File configuration.
   *
   * @return bool
   * @throws \Less_Exception_Parser
   */
  protected function compile($file, $options) {

    // Check id the file exist.
    if (!file_exists($file)) {
      if ($this->currentUser->hasPermission('administer site configuration')) {
        // Display message to the administrator.
        $this->messenger->addWarning($this->t('The less file %file_name does not exists.', ['%file_name' => $file]));
      }
      return FALSE;
    }

    $less = $this->getLess();

    $output = $options['output'];
    $path   = $options['less_path'];

    if ($less) {

      $less->reset();
      $less->parseFile($file, $path);

      $event = new IplessCompilationEvent($this);
      $this->eventDispatcher->dispatch(IplessEvents::LESS_FILE_COMPILED, $event);

      $this->preparePath($output);
      file_put_contents($output, $less->getCss());
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getLess() {
    if (!$this->less) {
      $config = $this->configFactory->get('system.performance');

      $options    = ['sourceMap' => (bool) $config->get('ipless.sourcemap')];
      $this->less = new Less_Parser($options);
    }
    return $this->less;
  }

  /**
   * Create path if not exist.
   *
   * @param $path
   */
  protected function preparePath($path) {
    $info = pathinfo($path);

    if (!empty($info['dirname']) && !file_exists($path)) {
      $this->fileSystem->prepareDirectory($info['dirname'], FileSystemInterface::CREATE_DIRECTORY);
    }
  }

}
