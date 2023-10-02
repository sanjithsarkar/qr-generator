
# How to use ?

- Just clone/fork this repository
- Check the package.json file
- command: `npm i`
- command: `npm run watch` for development and for production: `npm run production`

On production you only need
- assets
- includes
- wp-plugin-with-vue-tailwind.php (plugin Entry file)

## Now easy enqueue from version 1.0.6
No need to worry about the dev environment enqueue or Production level enqueue.
everything here can manage by Vite dedicated class (`includes/Classes/Vite.php`)

Just Call like this

`Vite::enqueueScript($enqueueTag, $yourAdminSourcePath, $dependency = [], $version = null, $inFooter = false)`

Note: same as `wp_enqueue_script`

### Example use case:
<p style="color: green;">
No need to enqueue production manually again, It will enqueue from manifest on production. Just call `Vite::enqueueScript()`</p>

`Vite::enqueueScript('my-plugin-script-boot', 'admin/start.js', array('jquery'), WPM_VERSION, true)`
---
###  NOT RECOMENDED
If you want to use `wp_enqueue_script` then you have to call both dev and production manually:

(Production and dev enqueue script should like this)

```
if (defined('WPM_DEVELOPMENT') && WPM_DEVELOPMENT !== 'yes') {
    wp_enqueue_script('WPWVT-script-boot', WPM_URL . 'assets/js/start.js', array('jquery'), WPM_VERSION, false);
} else {
    wp_enqueue_script('WPWVT-script-boot', 'http://localhost:8880/' . 'src/admin/start.js', array('jquery'), WPM_VERSION, true);
}


```