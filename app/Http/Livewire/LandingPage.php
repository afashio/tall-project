<?php

namespace App\Http\Livewire;

use App\Models\Subscriber;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class LandingPage extends Component
{
    /**
     * @var string
     */
    public string $email = '';
    public bool $showSubscribe = false;
    public bool $showSuccess = false;

    protected array $rules = [
        'email' => 'required|email:filter|unique:subscribers,email',
    ];

    public function render()
    {
        return view('livewire.landing-page');
    }

    /**
     * @return void
     */
    public function subscribe(): void
    {
        $this->validate();
        DB::transaction(
            function () {
                /** @var Subscriber $subscriber */
                $subscriber = Subscriber::create(['email' => $this->email]);
                $notification = new VerifyEmail();
                $notification->createUrlUsing(function ($notifiable){
                    return URL::temporarySignedRoute(
                        'subscribers.verify',
                        now()->addMinutes(30),
                        [
                            'subscriber' => $notifiable->getKey(),
                        ]
                    );
                });
                $subscriber->notify($notification);
            }
            ,
            $deadlockRetries = 5
        );

        $this->showSuccess = true;
        $this->reset('email', 'showSubscribe');
    }
}
