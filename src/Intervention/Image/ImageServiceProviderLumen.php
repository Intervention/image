<?php

namespace Intervention\Image;

use Illuminate\Support\ServiceProvider;

class ImageServiceProviderLumen extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
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

        // set configuration
        $app->configure('image');

        // create image
        $app->singleton('image',function ($app) {
            return new ImageManager($app['config']->get('image'));
        });

        $app->alias('image', 'Intervention\Image\ImageManager');
    }
}
