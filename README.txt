WHY MIGHT I NEED ALTERNATIVE STREAM WRAPPERS?
---------------------------------------------

Some hosting configurations with multiple webservers use a mix of 
filesystems local to each webserver and storage which is shared between the 
webs (e.g. using nfs or gluster).

It can sometimes be useful to, for example, use fast local storage for temp
files wherever possible, but shared storage where the webs might need to
share access to the same set of temporary files. 

The views_data_export module is a good example of where shared temp storage
is useful. See: https://drupal.org/node/1782038

This simple module allows Drupal to keep using the built-in stream wrappers 
(e.g. public:// and temporary://) but also have the use of one or more 
Alternative Stream Wrappers for cases like the shared temporary directory.

There may be other uses too - the module aims to be flexible.


USING ALTERNATIVE STREAM WRAPPERS
---------------------------------

The alt_stream_wrappers_stream_wrappers() function defines a default
alt-temp:// stream wrapper, and this can be used out of the box; all you
need to do is set a value for the variable alt_stream_wrappers_alt-temp_path

e.g.

drush vset alt_stream_wrappers_alt-temp_path '/mnt/nfs/tmp'

If you need more, or different wrappers, you can override the default by
setting a variable, most likely in settings.php

e.g.

$conf['alt_stream_wrappers_wrappers'] = array(
    'alt-temp' => array(
      'name' => t('Alternative temporary files'),
      'class' => 'DrupalAltStreamWrapper',
      'description' => t('Alternative temporary local files.'),
      'type' => STREAM_WRAPPERS_LOCAL_HIDDEN,
    ),
    'foobar' => array(
      'name' => t('Yet more temporary files'),
      'class' => 'DrupalAltStreamWrapper',
      'description' => t('Yet more temporary local files.'),
      'type' => STREAM_WRAPPERS_LOCAL_HIDDEN,
    ),
  );
  
Note that the class is always DrupalAltStreamWrapper if you want this module
to take care of actually providing the wrapper class.

In the example above, you'd then need to set a value for the path in
the appropriately named variable e.g.:

$conf['alt_stream_wrappers_foobar_path'] = '/mnt/foo';

Initially this is probably only useful for temporary files, as there's no
functioning implementation of the getExternalUrl method of the class. If
this would be a useful feature, suggestions or patches welcome :)
