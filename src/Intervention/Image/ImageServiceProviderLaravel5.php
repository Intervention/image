<?php

namespace Intervention\Image;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as IlluminateResponse;

class ImageServiceProviderLaravel5 extends ServiceProvider
{
    /**
     * Determines if Intervention Imagecache is installed
     *
     * @return boolean
     */
    private function cacheIsInstalled()
    {
        return class_exists('Intervention\\Image\\ImageCache');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(array(
            __DIR__.'/../../config/config.php' => config_path('image.php')
        ));

        // setup intervention/imagecache if package is installed
        $this->cacheIsInstalled() ? $this->bootstrapImageCache() : null;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // merge default config
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php',
            'image'
        );

        // create image
        $app['image'] = $app->share(function ($app) {
            return new ImageManager($app['config']->get('image'));
        });
    }

    /**
     * Bootstrap imagecache
     *
     * @return void
     */
    private function bootstrapImageCache()
    {
        $app = $this->app;
        $config = __DIR__.'/../../../../imagecache/src/config/config.php';

        $this->publishes(array(
            $config => config_path('imagecache.php')
        ));

        // merge default config
        $this->mergeConfigFrom(
            $config,
            'imagecache'
        );

        $config = $app['config'];

        // create dynamic manipulation route
        if (is_string($config->get('imagecache.route'))) {

            // add original to route templates
            $config->set('imagecache.templates.original', null);

            $app['router']->get($config->get('imagecache.route').'/{template}/{filename}', array('as' => 'imagecache', function ($template, $filename) use ($app, $config) {

                // find file
                foreach ($config->get('imagecache.paths') as $path) {
                    // don't allow '..' in filenames
                    $image_path = $path.'/'.str_replace('..', '', $filename);
                    if (file_exists($image_path) && is_file($image_path)) {
                        break;
                    } else {
                        $image_path = false;
                    }
                }

                // abort if file not found
                if ($image_path === false) {
                    $app->abort(404);
                }

                // define template callback
                $callback = $config->get("imagecache.templates.{$template}");

                if (is_callable($callback)) {

                    // image manipulation based on callback
                    $content = $app['image']->cache(function ($image) use ($image_path, $callback) {
                        return $callback($image->make($image_path));
                    }, $config->get('imagecache.lifetime'));

                } else {

                    // get original image file contents
                    $content = file_get_contents($image_path);
                }

                // define mime type
                $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

                // return http response
                return new IlluminateResponse($content, 200, array(
                    'Content-Type' => $mime,
                    'Cache-Control' => 'max-age='.($config->get('imagecache.lifetime')*60).', public',
                    'Etag' => md5($content)
                ));

            }))->where(array('template' => join('|', array_keys($config->get('imagecache.templates'))), 'filename' => '[ \w\\.\\/\\-]+'));
        }
    }
}
