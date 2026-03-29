<?php

namespace App\Livewire;

use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public bool $sent = false;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $this->sent = true;

        $this->reset('name', 'email', 'subject', 'message');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
