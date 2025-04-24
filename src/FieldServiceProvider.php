<?php

namespace Jamesil\NovaGoogleAutocomplete;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-google-autocomplete'),
            ], 'nova-google-autocomplete-lang');

            $this->publishes([
                __DIR__.'/../config' => config_path(),
            ], 'nova-google-autocomplete-config');
        }

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nova-google-autocomplete');
        $this->loadJsonTranslationsFrom(resource_path('lang/vendor/nova-google-autocomplete'));

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-google-autocomplete', __DIR__ . '/../dist/js/field.js');
            Nova::remoteScript('https://maps.googleapis.com/maps/api/js?key='.config('services.google_maps.key').'&libraries=places');

            // In Nova 5, translation handling is different
            // The JSON translations are loaded automatically from the paths registered with loadJsonTranslationsFrom
            // No need to explicitly call a translation method
        });
    }
}
