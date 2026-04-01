<?php

namespace App\Livewire;

use Livewire\Component;

class CookiePreferences extends Component
{
    public bool $analytics = false;

    public bool $marketing = false;

    public bool $show = false;

    protected $listeners = ['openCookiePreferences' => 'open'];

    public function open(): void
    {
        $this->analytics = $this->getCookiePreference('analytics');
        $this->marketing = $this->getCookiePreference('marketing');
        $this->show = true;
    }

    public function save(): void
    {
        $this->dispatch('cookie-preferences-saved', analytics: $this->analytics, marketing: $this->marketing);
        $this->show = false;
    }

    public function close(): void
    {
        $this->show = false;
    }

    protected function getCookiePreference(string $category): bool
    {
        $cookie = request()->cookie(config('cookie-consent.cookie_name'));

        if (! $cookie) {
            return false;
        }

        if ($cookie === '1') {
            return true;
        }

        if (is_string($cookie)) {
            $decoded = json_decode($cookie, true);

            return $decoded[$category] ?? false;
        }

        return false;
    }

    public function render()
    {
        return view('livewire.cookie-preferences');
    }
}
