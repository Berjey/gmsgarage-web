<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\ContactMessage;
use App\Models\EvaluationRequest;
use App\Models\Customer;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        // Araç sayaçları — tek sorguda
        $vehicleStats = Vehicle::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured
        ')->first();

        // Blog sayaçları — tek sorguda
        $blogStats = BlogPost::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN is_published = 1 THEN 1 ELSE 0 END) as published,
            SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured,
            COALESCE(SUM(views), 0) as total_views
        ')->first();

        // Mesaj sayaçları — tek sorguda
        $isMySQL = config('database.default') === 'mysql';
        $todayExpr = $isMySQL ? 'DATE(created_at) = CURDATE()' : 'DATE(created_at) = DATE("now")';

        $messageStats = ContactMessage::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
            SUM(CASE WHEN {$todayExpr} THEN 1 ELSE 0 END) as today
        ")->first();

        // Değerleme sayaçları — tek sorguda
        $evalStats = EvaluationRequest::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
            SUM(CASE WHEN {$todayExpr} THEN 1 ELSE 0 END) as today
        ")->first();

        // Müşteri sayaçları — tek sorguda
        $monthExpr = $isMySQL
            ? 'MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())'
            : 'strftime("%m", created_at) = strftime("%m", "now") AND strftime("%Y", created_at) = strftime("%Y", "now")';

        $customerStats = Customer::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN {$todayExpr} THEN 1 ELSE 0 END) as today,
            SUM(CASE WHEN created_at >= ? AND created_at <= ? THEN 1 ELSE 0 END) as this_week,
            SUM(CASE WHEN {$monthExpr} THEN 1 ELSE 0 END) as this_month
        ", [now()->startOfWeek(), now()->endOfWeek()])->first();

        $stats = [
            // Araç
            'total_vehicles'    => $vehicleStats->total ?? 0,
            'active_vehicles'   => $vehicleStats->active ?? 0,
            'featured_vehicles' => $vehicleStats->featured ?? 0,

            // Blog
            'total_blog_posts'     => $blogStats->total ?? 0,
            'published_blog_posts' => $blogStats->published ?? 0,
            'featured_blog_posts'  => $blogStats->featured ?? 0,
            'total_views'          => $blogStats->total_views ?? 0,

            // Kullanıcı
            'total_users'  => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),

            // Müşteri
            'total_customers'      => $customerStats->total ?? 0,
            'today_customers'      => $customerStats->today ?? 0,
            'this_week_customers'  => $customerStats->this_week ?? 0,
            'this_month_customers' => $customerStats->this_month ?? 0,

            // Mesajlar
            'unread_messages'           => $messageStats->unread ?? 0,
            'total_messages'            => $messageStats->total ?? 0,
            'today_messages'            => $messageStats->today ?? 0,
            'unread_evaluation_requests'=> $evalStats->unread ?? 0,
            'total_evaluation_requests' => $evalStats->total ?? 0,
            'today_evaluation_requests' => $evalStats->today ?? 0,

            // Son aktiviteler
            'recent_vehicles'    => Vehicle::latest()->limit(5)->get(),
            'recent_blog_posts'  => BlogPost::latest()->limit(5)->get(),
            'recent_messages'    => ContactMessage::latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
