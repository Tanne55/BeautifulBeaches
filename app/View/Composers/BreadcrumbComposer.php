<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\BreadcrumbService;

class BreadcrumbComposer
{
    protected $breadcrumb;

    public function __construct(BreadcrumbService $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    public function compose(View $view)
    {
        $view->with('breadcrumb', $this->breadcrumb);
    }
}
