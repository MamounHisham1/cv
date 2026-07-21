<?php

namespace App\Providers;

use App\Http\Middleware\SetAppLocale;
use App\Listeners\NotifyTelegramNewUserListener;
use App\Services\PendingClarifications;
use App\Services\ProposedCvChanges;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton per request/Livewire round-trip — bridges clarifying
        // questions from the AskClarifyingQuestions tool (deep inside the
        // agent loop) back to the Livewire component that invoked the agent.
        $this->app->singleton(PendingClarifications::class);

        // Singleton per request/Livewire round-trip — the AI write tools
        // stage proposed edits here instead of mutating the CV directly, so
        // nothing the model invents can land until the user reviews it.
        $this->app->singleton(ProposedCvChanges::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/components/ui'), 'ui');
        $this->configureDefaults();
        $this->registerLocaleBladeHelpers();

        Event::listen(Registered::class, NotifyTelegramNewUserListener::class);
    }

    /**
     * Register Blade directives/components used to drive RTL + i18n in views.
     */
    protected function registerLocaleBladeHelpers(): void
    {
        // `@dir` resolves to the current text direction ("ltr" or "rtl"),
        // and `@dirRtl` / `@dirLtr` emit boolean helpers for conditional markup.
        Blade::directive('dir', function () {
            return '<?php echo (\App\Http\Middleware\SetAppLocale::SUPPORTED_LOCALES[app()->getLocale()] ?? ["dir" => "ltr"])["dir"]; ?>';
        });

        Blade::if('rtl', function () {
            return (SetAppLocale::SUPPORTED_LOCALES[app()->getLocale()] ?? ['dir' => 'ltr'])['dir'] === 'rtl';
        });

        Blade::if('ltr', function () {
            return (SetAppLocale::SUPPORTED_LOCALES[app()->getLocale()] ?? ['dir' => 'ltr'])['dir'] !== 'rtl';
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
