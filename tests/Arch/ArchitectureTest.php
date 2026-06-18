<?php

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Livewire\Component;

// Architecture tests — these run as Pest arch() rules that statically check
// the codebase structure. They do NOT touch the database or hit the network.

arch('all Livewire components extend the base Component class')
    ->expect('App\Livewire')
    ->classes()
    ->toExtend(Component::class)
    ->ignoring('App\Livewire\Actions')
    ->ignoring('App\Livewire\Concerns');

arch('queued jobs implement ShouldQueue')
    ->expect('App\Jobs')
    ->classes()
    ->toImplement(ShouldQueue::class);

arch('mailables implement ShouldQueue')
    ->expect('App\Mail')
    ->classes()
    ->toImplement(ShouldQueue::class);

arch('notifications extend the base Notification class')
    ->expect('App\Notifications')
    ->classes()
    ->toExtend(Notification::class);

arch('no debug dd() or dump() left in production code')
    ->expect('App')
    ->not->toUse(['dd', 'dump']);

arch('models do not use raw env() calls (config() only)')
    ->expect('App\Models')
    ->not->toUse('env');

arch('services do not use raw env() calls (config() only)')
    ->expect('App\Services')
    ->not->toUse('env');

arch('controllers do not use raw env() calls (config() only)')
    ->expect('App\Http\Controllers')
    ->not->toUse('env');
