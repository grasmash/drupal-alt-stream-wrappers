<?php

namespace Drupal\alt_stream_wrappers\Tests;

/**
 * @coversDefaultClass \Drupal\Core\File\FileSystem
 *
 * @group File
 */
class FileSystemTest extends \Drupal\Tests\Core\File\FileSystemTest {

    /**
     * @covers ::basename
     *
     * @dataProvider providerTestBasename
     */
    public function testBasename($uri, $expected, $suffix = NULL) {
        $this->assertSame($expected, $this->fileSystem->basename($uri, $suffix));
    }

    public function providerTestBasename() {
        $data = [];
        $data[] = [
          'alt-temporary://nested/dir',
          'dir',
        ];
        return $data;
    }


}
