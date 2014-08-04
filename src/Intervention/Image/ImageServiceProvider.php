<?php

namespace Intervention\Image;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as IlluminateResponse;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('intervention/image');

        // try to create imagecache route only if imagecache is present
        if (class_exists('Intervention\\Image\\ImageCache')) {

            $app = $this->app;

            // load imagecache config
            $app['config']->package('intervention/imagecache', __DIR__.'/../../../../imagecache/src/config', 'imagecache');
            $config = $app['config'];

            // create dynamic manipulation route
            if (is_string($config->get('imagecache::route'))) {

                // add original to route templates
                $config->set('imagecache::templates.original', null);

                // setup image manipulator route
                $app['router']->get($config->get('imagecache::route').'/{template}/{filename}', array('as' => 'imagecache', function ($template, $filename) use ($app, $config) {

                    // disable session cookies for image route
                    $app['config']->set('session.driver', 'array');

                    // find file
                    foreach ($config->get('imagecache::paths') as $path) {
                        $image_path = $path.'/'.$filename;
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
                    $callback = $config->get("imagecache::templates.{$template}");

                    if (is_callable($callback)) {

                        // image manipulation based on callback
                        $content = $app['image']->cache(function ($image) use ($image_path, $callback) {
                            return $callback($image->make($image_path));
                        }, $config->get('imagecache::lifetime'));

                    } else {

                        // get original image file contents
                        $content = file_get_contents($image_path);
                    }

                    // define mime type
                    $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

                    // return http response
                    return new IlluminateResponse($content, 200, array(
                        'Content-Type' => $mime,
                        'Cache-Control' => 'max-age='.($config->get('imagecache::lifetime')*60).', public',
                        'Etag' => md5($content)
                    ));

                }))->where(array('template' => join('|', array_keys($config->get('imagecache::templates'))), 'filename' => '^[\/\w.-]+$'));
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app['image'] = $app->share(function ($app) {
            return new ImageManager($app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('image');
    }

}
