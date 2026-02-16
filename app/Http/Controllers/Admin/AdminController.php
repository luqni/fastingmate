<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $driver = \DB::connection()->getDriverName();
        
        // Helper to get date formatting syntax based on driver
        $dateFormat = function($column) use ($driver) {
             if ($driver === 'sqlite') {
                 return "strftime('%Y-%m-%d', $column)";
             }
             return "DATE($column)"; // MySQL and Postgres support DATE()
        };

        // Visit Stats - visit_date is already a DATE column, so we can just group by it (or cast to string for safety)
        // For SQLite, grouping by the raw column works if format is YYYY-MM-DD
        $visitRaw = $driver === 'sqlite' ? "visit_date" : "DATE(visit_date)";

        $visits = \App\Models\Visit::selectRaw("$visitRaw as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        // User Growth Stats (TIMESTAMP)
        $userRaw = $dateFormat('created_at');
        $userGrowth = \App\Models\User::selectRaw("$userRaw as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        // Install Growth Stats (TIMESTAMP)
        $installRaw = $dateFormat('installed_at');
        $installGrowth = \App\Models\User::whereNotNull('installed_at')
            ->selectRaw("$installRaw as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        $totalInstalls = \App\Models\User::whereNotNull('installed_at')->count();
        $totalUsers = \App\Models\User::count();
        
        $totalPushUsers = \App\Models\User::whereNotNull('push_enabled_at')->count();
        $enableRamadanSummary = \App\Models\Setting::where('key', 'enable_ramadan_summary')->value('value');

        // Article Stats
        $totalArticleViews = \App\Models\Post::sum('views_count');
        $topArticles = \App\Models\Post::orderBy('views_count', 'desc')
            ->select('title', 'views_count', 'slug')
            ->limit(5)
            ->get();

        // Share Stats
        $shareRaw = $dateFormat('created_at');
        $shareStats = \App\Models\PostShare::selectRaw("$shareRaw as date, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->reverse()
            ->values();

        $totalShares = \App\Models\PostShare::count();

        $totalShares = \App\Models\PostShare::count();

        // Users List with Search and Filter
        $query = \App\Models\User::query();

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (request('role') && request('role') !== 'all') {
            $query->where('role', request('role'));
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact('visits', 'userGrowth', 'installGrowth', 'totalInstalls', 'totalUsers', 'totalPushUsers', 'enableRamadanSummary', 'totalArticleViews', 'topArticles', 'shareStats', 'totalShares', 'users'));
    }

    public function trackInstall(Request $request)
    {
        $request->user()->update(['installed_at' => now()]);
        return response()->json(['status' => 'success']);
    }

    public function settings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'enable_ramadan_summary' => 'nullable|in:on,off,1,0',
            'ramadhan_schedule_visible' => 'nullable|in:on,off,1,0',
        ]);

        $settings = [
            'enable_ramadan_summary',
            'ramadhan_schedule_visible',
        ];

        foreach ($settings as $key) {
            $value = $request->has($key) ? '1' : '0';
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
