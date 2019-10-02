<?php

namespace Drupal\alt_stream_wrappers\StreamWrapper;

use Drupal\Core\StreamWrapper\StreamWrapperInterface;
use Drupal\Core\StreamWrapper\LocalStream;
use Drupal\Core\Url;

/**
 * Defines an alternative Drupal temporary (alttemporary://) stream wrapper class.
 *
 * @see \Drupal\Core\StreamWrapper\TemporaryStream
 */
class AltTempStream extends LocalStream {

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return StreamWrapperInterface::LOCAL_HIDDEN;
  }

  /**
   * {@inheritdoc}
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
   * {@inheritdoc}
   */
  public function getDirectoryPath() {
      $temporary_directory = \Drupal::config('alt_stream_wrappers.settings')->get('path.temporary');
      if (empty($temporary_directory)) {
          $temporary_directory = file_directory_temp();
      }
      return $temporary_directory;
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl() {
    $path = str_replace('\\', '/', $this->getTarget());
    return Url::fromRoute('alt_stream_wrappers.temporary', [], ['absolute' => TRUE, 'query' => ['file' => $path]])->toString();
  }

}
