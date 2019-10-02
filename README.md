This module creates a second, alternative stream wrapper for temporary files.

After installing this module, you will have both `temporary://` and `alttemporary://` streams
available.

# Why might I need Alternative Stream Wrappers?

Some hosting configurations with multiple webservers use a mix of
filesystems local to each webserver and storage which is shared between the 
webs (e.g. using nfs or gluster).

It can sometimes be useful to, for example, use fast local storage for temp
files wherever possible, but shared storage where the webs might need to
share access to the same set of temporary files. 

The views_data_export module is a good example of where shared temp storage
is useful. See: <https://drupal.org/node/1782038>

This simple module allows Drupal to keep using the built-in stream wrappers 
 but also have the use of an alternative, shared temporary directory.

Using Alternative Stream Wrappers
=================================

1. Set a path for the `alttemporary://` stream wrapper.

There are a few ways to do this:

a. Via the UI at /admin/config/media/file-system.
b. Via `drush config:set alt_stream_wrappers.settings path.temporary '/mnt/nfs/tmp' -y`
c. Via settings.php `$config['alt_stream_wrappers.settings']['path']['temporary'] = '/tmp';`

2. Take advantage of this new stream wrapper, you'd need to use `alttemporary://` as a filepath. E.g., configure DropzoneJS to use `alttemporary://` for temporary storage via `drush config:set dropzonejs.settings tmp_upload_scheme alttemporary`.