<?php

namespace App\Providers;

use App\Models\Task;
use App\Notifications\BirthdayNotification;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

use Illuminate\Support\facades\schema;
use League\CommonMark\Extension\TaskList\TaskListItemMarkerRenderer;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Task::observe(TaskObserver::class);

        $notifications = DB::table('notifications')
            ->where('type', BirthdayNotification::class)
            ->whereNull('read_at')
            ->get();

      
        view::share('notifications', $notifications);
        schema::defaultStringLength(191);
    }
}
