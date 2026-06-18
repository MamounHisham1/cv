<?php

namespace App\Livewire;

use App\Mail\AdminMail;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;
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

        $submission = ContactSubmission::create($validated);

        // Notify the site admin at the configured admin email address.
        // Uses raw Mail::to so this works regardless of whether the admin has
        // a local user account.
        Mail::to(
            config('services.telegram.admin_email', 'mamounprogrammer@gmail.com')
        )->send(new AdminMail(
            emailSubject: 'Contact Form: '.$submission->subject,
            emailBody: "From: {$submission->name} ({$submission->email})\n\n{$submission->message}",
            template: null,
        ));

        $this->sent = true;

        $this->reset('name', 'email', 'subject', 'message');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
