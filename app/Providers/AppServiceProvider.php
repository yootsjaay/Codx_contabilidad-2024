<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\FielRequestBuilder\FielRequestBuilder;
use PhpCfdi\SatWsDescargaMasiva\RequestBuilder\RequestBuilderInterface;
class AppServiceProvider extends ServiceProvider
    {
        public function register(): void
        {
            //instanciar el requestBuilderInterface
           $this->app->bind(RequestBuilderInterface::class, FielRequestBuilder::class);
          /* if(config('app.env')==='local'){
                $this->app['request']->server->set('HTTPS',true);
            }*/
        }

        public function boot(): void
        {
            
        }

        
        
    }
