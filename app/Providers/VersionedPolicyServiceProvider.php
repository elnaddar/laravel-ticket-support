<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class VersionedPolicyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            // Determine the API version from the request
            $version = $this->getApiVersion();

            // Construct the policy class name based on the version
            $policyNamespace = 'App\\Policies\\V' . $version;
            $policyClass = $policyNamespace . '\\' . class_basename($modelClass) . 'Policy';

            // Check if the policy class exists
            if (class_exists($policyClass)) {
                return $policyClass;
            }

            // Fallback to the default policy resolution
            return $modelClass;
        });
    }

    protected function getApiVersion()
    {
        // Extract the version from the request URI
        $request = request();
        $segments = $request->segments();

        // Assuming the version is the second segment in the URI
        if (isset($segments[1]) && is_numeric($segments[1])) {
            return $segments[1];
        }

        // Default to version 1 if no version is specified
        return 1;
    }
}
