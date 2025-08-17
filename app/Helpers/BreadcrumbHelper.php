<?php

if (!function_exists('breadcrumb')) {
    /**
     * Get breadcrumb service instance
     *
     * @return \App\Services\BreadcrumbService
     */
    function breadcrumb()
    {
        return app(\App\Services\BreadcrumbService::class);
    }
}
