<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * 应用服务
 *
 * @author Tongle Xu <xutongle@msn.com>
 */
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
        \Illuminate\Http\Resources\Json\JsonResource::withoutWrapping();
        \Illuminate\Support\Carbon::setLocale('zh');
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(! $this->app->isProduction());
        //
    }
}
