<?php
 
namespace App\Providers;
 
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\Visitor;
use App\Models\Post;
use App\View\Composers\FooterComposer;
 
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ...
    }
 
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Schema::hasTable((new Setting)->getTable())) {
            $settings = Setting::pluck('value', 'key')->toArray();

            $settings['appLogoUrl'] = isset($settings['appLogo'])
                ? (Storage::disk('public')->exists($settings['appLogo'])
                    ? Storage::url($settings['appLogo'])
                    : '/assets/admin/images/logo_icon.svg')
                : '/assets/admin/images/logo_icon.svg';
    
            $settings['jdihnLogoUrl'] = isset($settings['jdihnLogo'])
                ? (Storage::disk('public')->exists($settings['jdihnLogo'])
                    ? Storage::url($settings['jdihnLogo'])
                    : '/assets/admin/images/jdihn-logo-web.png')
                : '/assets/admin/images/jdihn-logo-web.png';
    
            $settings['fullAddress'] = implode(', ', [
                $settings['address'],
                $settings['city'],
                $settings['district'],
                $settings['regency'],
                $settings['province']
            ]);
        } else {
            $settings = [];
        }

        View::share($settings);

        View::composer(
            ['jdih.layouts.footer', 'jdih.legislation.leftbar'],
            FooterComposer::class
        );

        View::composer('jdih.layouts.footer', function ($view) {
            if (\Schema::hasTable((new Visitor)->getTable())) {
                $todayVisitor = Visitor::countDaily()->get()->count();
                $yesterdayVisitor = Visitor::countDaily(1)->get()->count();
                $lastWeekVisitor = Visitor::countWeekly()->get()->count();
                $lastMonthVisitor = Visitor::countMonthly()->get()->count();
                $allVisitor = Visitor::countAll()->get()->count();
            } else {
                $todayVisitor = 0;
                $yesterdayVisitor = 0;
                $lastWeekVisitor = 0;
                $lastMonthVisitor = 0;
                $allVisitor = 0;
            }

            $welcome = (\Schema::hasTable((new Post)->getTable()))
                ? Post::whereSlug('selamat-datang')->first()
                : null;

            return $view->with('todayVisitor', $todayVisitor)
                ->with('yesterdayVisitor', $yesterdayVisitor)
                ->with('lastWeekVisitor', $lastWeekVisitor)
                ->with('lastMonthVisitor', $lastMonthVisitor)
                ->with('allVisitor', $allVisitor)
                ->with('welcome', $welcome);
        });
    }
}