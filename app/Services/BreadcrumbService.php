<?php

namespace App\Services;

class BreadcrumbService
{
    private $breadcrumbs = [];

    /**
     * Add breadcrumb item
     */
    public function add($title, $url = null)
    {
        $this->breadcrumbs[] = [
            'title' => $title,
            'url' => $url
        ];
        return $this;
    }

    /**
     * Get all breadcrumbs
     */
    public function get()
    {
        return $this->breadcrumbs;
    }

    /**
     * Clear all breadcrumbs
     */
    public function clear()
    {
        $this->breadcrumbs = [];
        return $this;
    }

    /**
     * Generate breadcrumbs automatically based on current route
     */
    public function auto()
    {
        $route = request()->route();
        if (!$route) return $this;

        $routeName = $route->getName();
        $parameters = $route->parameters();

        // Clear existing breadcrumbs
        $this->clear();

        // Always start with home (except for home page)
        if ($routeName !== 'home') {
            $this->add('Trang chủ', route('home'));
        }

        // Generate breadcrumbs based on route name
        switch ($routeName) {
            case 'home':
                // Only home breadcrumb
                break;

            case 'explore':
                $this->add('Khám phá bãi biển', route('explore'));
                // Check if filtering by region
                if (request()->has('region')) {
                    $regionName = request()->get('region');
                    $this->add($regionName);
                }
                break;

            case 'beaches.show':
                $this->add('Khám phá bãi biển', route('explore'));
                if (isset($parameters['beach'])) {
                    $beach = $parameters['beach'];
                    $this->add($beach->title ?? 'Chi tiết bãi biển');
                }
                break;

            case 'tour':
                $this->add('Tour du lịch', route('tour'));
                break;

            case 'tour.show':
                $this->add('Tour du lịch', route('tour'));
                if (isset($parameters['id'])) {
                    try {
                        $tour = \App\Models\Tour::find($parameters['id']);
                        if ($tour) {
                            $this->add($tour->title ?? 'Chi tiết tour');
                        }
                    } catch (\Exception $e) {
                        $this->add('Chi tiết tour');
                    }
                }
                break;

            case 'tour.booking.form':
                $this->add('Tour du lịch', route('tour'));
                if (isset($parameters['id'])) {
                    try {
                        $tour = \App\Models\Tour::find($parameters['id']);
                        if ($tour) {
                            $this->add($tour->title, route('tour.show', $parameters['id']));
                            $this->add('Đặt tour');
                        }
                    } catch (\Exception $e) {
                        $this->add('Chi tiết tour');
                        $this->add('Đặt tour');
                    }
                }
                break;

            case 'bookings.result':
                $this->add('Kết quả đặt tour');
                break;

            case 'bookings.track':
                $this->add('Tra cứu đặt tour');
                break;

            case 'gallery':
                $this->add('Thư viện ảnh', route('gallery'));
                break;

            case 'contact':
                $this->add('Liên hệ', route('contact'));
                break;

            case 'queries':
                $this->add('Câu hỏi thường gặp', route('queries'));
                break;

            case 'about':
                $this->add('Về chúng tôi', route('about'));
                break;

            // User routes
            case 'user.dashboard':
                $this->add('Dashboard người dùng');
                break;

            case 'user.history':
                $this->add('Dashboard người dùng', route('user.dashboard'));
                $this->add('Lịch sử đặt tour');
                break;

            case 'user.profile.edit':
                $this->add('Dashboard người dùng', route('user.dashboard'));
                $this->add('Chỉnh sửa thông tin');
                break;

            case 'user.cancellation_requests':
                $this->add('Dashboard người dùng', route('user.dashboard'));
                $this->add('Yêu cầu hủy tour');
                break;

            case 'user.booking.cancel.form':
                $this->add('Dashboard người dùng', route('user.dashboard'));
                $this->add('Lịch sử đặt tour', route('user.history'));
                $this->add('Hủy đặt tour');
                break;

            // Admin routes
            case 'admin.dashboard':
                $this->add('Dashboard Admin');
                break;

            case 'admin.beaches.index':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Quản lý bãi biển');
                break;

            case 'admin.beaches.create':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Quản lý bãi biển', route('admin.beaches.index'));
                $this->add('Thêm bãi biển mới');
                break;

            case 'admin.beaches.edit':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Quản lý bãi biển', route('admin.beaches.index'));
                if (isset($parameters['beach'])) {
                    $beach = $parameters['beach'];
                    $this->add('Chỉnh sửa: ' . ($beach->title ?? 'Bãi biển'));
                } else {
                    $this->add('Chỉnh sửa bãi biển');
                }
                break;

            case 'admin.users.index':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Quản lý người dùng');
                break;

            case 'admin.users.create':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Quản lý người dùng', route('admin.users.index'));
                $this->add('Thêm người dùng mới');
                break;

            case 'admin.users.edit':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Quản lý người dùng', route('admin.users.index'));
                if (isset($parameters['user'])) {
                    $user = $parameters['user'];
                    $this->add('Chỉnh sửa: ' . ($user->name ?? 'Người dùng'));
                } else {
                    $this->add('Chỉnh sửa người dùng');
                }
                break;

            case 'admin.support.index':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Hỗ trợ khách hàng');
                break;

            case 'admin.support.show':
                $this->add('Dashboard Admin', route('admin.dashboard'));
                $this->add('Hỗ trợ khách hàng', route('admin.support.index'));
                $this->add('Chi tiết yêu cầu');
                break;

            // CEO routes
            case 'ceo.dashboard':
                $this->add('Dashboard CEO');
                break;

            case 'ceo.tours.index':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý tour');
                break;

            case 'ceo.tours.create':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý tour', route('ceo.tours.index'));
                $this->add('Thêm tour mới');
                break;

            case 'ceo.tours.edit':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý tour', route('ceo.tours.index'));
                if (isset($parameters['tour'])) {
                    $tour = $parameters['tour'];
                    $this->add('Chỉnh sửa: ' . ($tour->title ?? 'Tour'));
                } else {
                    $this->add('Chỉnh sửa tour');
                }
                break;

            case 'ceo.bookings.index':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý đặt tour');
                break;

            case 'ceo.bookings.groups':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Nhóm đặt tour');
                break;

            case 'ceo.tickets.index':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý vé');
                break;

            case 'ceo.tickets.show':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý vé', route('ceo.tickets.index'));
                $this->add('Chi tiết vé');
                break;

            case 'ceo.tickets.edit':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Quản lý vé', route('ceo.tickets.index'));
                $this->add('Chỉnh sửa vé');
                break;

            case 'ceo.reports.index':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Báo cáo');
                break;

            case 'ceo.cancellation_requests.index':
                $this->add('Dashboard CEO', route('ceo.dashboard'));
                $this->add('Yêu cầu hủy tour');
                break;

            // Auth routes
            case 'login':
                $this->add('Đăng nhập');
                break;

            case 'register':
                $this->add('Đăng ký');
                break;

            default:
                // For unknown routes, try to parse from URL path
                $segments = request()->segments();
                foreach ($segments as $segment) {
                    if (!in_array($segment, ['admin', 'user', 'ceo'])) { // Skip common prefixes
                        $this->add(ucfirst(str_replace(['-', '_'], ' ', $segment)));
                    }
                }
                break;
        }

        return $this;
    }

    /**
     * Generate breadcrumbs with custom logic for specific cases
     */
    public function custom($callback)
    {
        $callback($this);
        return $this;
    }

    /**
     * Set breadcrumbs manually (overrides auto generation)
     */
    public function manual($breadcrumbs = [])
    {
        $this->clear();
        foreach ($breadcrumbs as $breadcrumb) {
            $this->add($breadcrumb['title'], $breadcrumb['url'] ?? null);
        }
        return $this;
    }

    /**
     * Add breadcrumb at the beginning
     */
    public function prepend($title, $url = null)
    {
        array_unshift($this->breadcrumbs, [
            'title' => $title,
            'url' => $url
        ]);
        return $this;
    }

    /**
     * Remove last breadcrumb
     */
    public function pop()
    {
        array_pop($this->breadcrumbs);
        return $this;
    }

    /**
     * Check if breadcrumbs exist
     */
    public function exists()
    {
        return !empty($this->breadcrumbs);
    }

    /**
     * Get breadcrumbs count
     */
    public function count()
    {
        return count($this->breadcrumbs);
    }
}
