<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'customer' => \App\Http\Middleware\CheckCustomer::class,
        'userlist' => \App\Http\Middleware\CheckUserLists::class,
        'template' => \App\Http\Middleware\TemplateValidation::class,
        'wanumber' => \App\Http\Middleware\WACheckValidation::class,
        'is_admin' => \App\Http\Middleware\AdminUser::class,
        'is_admin_woowa' => \App\Http\Middleware\AdminWoowa::class,
        'checkadditional' => \App\Http\Middleware\CheckAdditional::class,
        'cors' => \App\Http\Middleware\Cors::class,
        'checkdevicename' => \App\Http\Middleware\CheckDeviceName::class,
        'authsettings' => \App\Http\Middleware\AuthSettings::class,
        'usersettings'=>\App\Http\Middleware\CheckSettings::class,
        'checkeventduplicate'=>\App\Http\Middleware\CheckEventCampaignDuplicate::class,
        'checkresponderduplicate'=>\App\Http\Middleware\CheckAutoResponderDuplicate::class,
        'checkbroadcastduplicate'=>\App\Http\Middleware\CheckBroadcastDuplicate::class,
        'checkimportcsv'=>\App\Http\Middleware\CheckImportCSV::class,
        'checkphone'=>\App\Http\Middleware\CheckPhone::class,
        'checkcall'=>\App\Http\Middleware\CheckCallingCode::class,
        'checkformappt'=>\App\Http\Middleware\CheckFormAppointment::class,
        'checkeditformappt'=>\App\Http\Middleware\CheckEditFormAppointment::class,
        'checkeditappt'=>\App\Http\Middleware\CheckEditAppointmentTemplate::class,
        'save_apt'=>\App\Http\Middleware\CheckCreateAppointment::class,
        'check_country'=>\App\Http\Middleware\CheckCountry::class,
        'chat_auth'=>\App\Http\Middleware\AuthChats::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
