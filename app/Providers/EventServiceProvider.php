<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'user.created' => [
            'App\Events\UserEvent@itemCreated',
        ],
        'user.updated' => [
            'App\Events\UserEvent@itemUpdated',
        ],
        'user.deleted' => [
            'App\Events\UserEvent@itemDeleted',
        ],

        'adduserdata.created' => [
            'App\Events\UserAditionalDataEvent@itemCreated',
        ],
        'adduserdata.updated' => [
            'App\Events\UserAditionalDataEvent@itemUpdated',
        ],
        'adduserdata.deleted' => [
            'App\Events\UserAditionalDataEvent@itemDeleted',
        ],

        'expediente.created' => [
            'App\Events\ExpedienteEvent@itemCreated',
        ],
        'expediente.updated' => [
            'App\Events\ExpedienteEvent@itemUpdated',
        ],
        'expediente.deleted' => [
            'App\Events\ExpedienteEvent@itemDeleted',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
