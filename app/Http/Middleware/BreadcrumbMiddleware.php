<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\BreadcrumbService;

class BreadcrumbMiddleware
{
    protected $breadcrumb;

    public function __construct(BreadcrumbService $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Auto-generate breadcrumbs for the current request
        $this->breadcrumb->auto();
        
        return $next($request);
    }
}
