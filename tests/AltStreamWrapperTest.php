<?php

namespace Drupal\alt_stream_wrappers\Tests;

use Drupal\KernelTests\Core\File\StreamWrapperTest;

class AltStreamWrapperTest extends StreamWrapperTest {

    /**
     * Test the getViaUri() and getViaScheme() methods and target functions.
     */
    public function testUriFunctions() {
        $config = $this->config('alt_stream_wrappers.settings');

        // Test file_uri_target().
        $this->assertEqual(\Drupal::service('stream_wrapper_manager')->getViaScheme('alt-temporary')->getDirectoryPath(), $config->get('path.temporary'), 'Expected temporary directory path was returned.');

        // Test file_create_url()
        // TemporaryStream::getExternalUrl() uses Url::fromRoute(), which needs
        // route information to work.
        $this->container->get('router.builder')->rebuild();
        $this->assertTrue(strpos(file_create_url('alt-temporary://test.txt'), '/alt_stream_wrappers/temporary?file=test.txt'), 'Temporary external URL correctly built.');
    }

}
