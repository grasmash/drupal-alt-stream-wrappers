<?php

namespace Drupal\alt_stream_wrappers\StreamWrapper;

use Drupal\Core\StreamWrapper\StreamWrapperInterface;
use Drupal\Core\Site\Settings;

/**
 * Drupal alt-temp stream wrapper class.
 *
 * Provides support for alternative temp file storage locations.
 */
class AltTempStreamWrapper implements StreamWrapperInterface {

  /**
   * Returns the type of stream wrapper.
   *
   * @return int
   *   See StreamWrapperInterface for permissible values.
   */
  public static function getType() {
    return StreamWrapperInterface::LOCAL_NORMAL;
  }

  /**
   * Returns the name of the stream wrapper for use in the UI.
   *
   * @return string
   *   The stream wrapper name.
   */
  public function getName() {
    return t('Alternative Temporary files');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Alternative Temporary file storage (an alternative to the temporary:// scheme).');
  }

  /**
   * Overrides getLocalPath().
   */
  protected function getLocalPath($uri = NULL) {
    if (empty($this->uri) && !empty($uri)) {
      // sometimes necessary for the file_uri_scheme and variable_get
      //  in DrupalAltStreamWrapper's getDirectoryPath method to work
      //  when called from parent::getLocalPath
      $this->uri = $uri;
    }
    return parent::getLocalPath($uri);
  }

  /**
   * Implements abstract public function getDirectoryPath()
   */
  public function getDirectoryPath() {
    $scheme = file_uri_scheme($this->uri);
    // todo: is temp a sensible default here or should it just be FALSE?
    return variable_get('alt_stream_wrappers_' . $scheme . '_path', file_directory_temp());
  }

  /**
   * Overrides getExternalUrl().
   */
  public function getExternalUrl() {
    $scheme = file_uri_scheme($this->uri);
    // What kind of wrapper is this?
    $wrappers = file_get_stream_wrappers();
    $wrapper = isset($wrappers[$scheme]) ? $wrappers[$scheme] : FALSE;
    if ($wrapper) {
      if ($wrapper['type'] & STREAM_WRAPPERS_LOCAL_NORMAL) {
        // Same as public:// stream wrapper
        $path = str_replace('\\', '/', $this->getTarget());
        return $GLOBALS['base_url'] . '/' . self::getDirectoryPath() . '/' . drupal_encode_path($path);
      }
      else {
        // Go through the system module like temporary:// and private://
        //  In order for this file to be downloadable, a module will have to
        //  implement hook_file_download() and return headers for this scheme.
        $path = str_replace('\\', '/', $this->getTarget());
        return url('system/' . $scheme . '/' . $path, array('absolute' => TRUE));
      }
    }
  }
}
