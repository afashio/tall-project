<?php
declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Subscriber;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class LandingPage extends Component
{
    public null|string $email = null;
    public bool $showSubscribe = false;
    public bool $showSuccess = false;

    /**
     * @var array|string[]
     */
    protected array $rules = [
        'email' => 'required|email:filter|unique:subscribers,email',
    ];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.landing-page');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function mount(Request $request): void
    {
        if ($request->has('verified') && (int)$request->get('verified') === 1){
            $this->showSuccess = true;
        }
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
                $notification->createUrlUsing(
                    function ($notifiable) {
                        return URL::temporarySignedRoute(
                            'subscribers.verify',
                            now()->addMinutes(30),
                            [
                                'subscriber' => $notifiable->getKey(),
                            ]
                        );
                    }
                );
                $subscriber->notify($notification);
            }
            ,
            $deadlockRetries = 5
        );

        $this->showSuccess = true;
        $this->reset('email', 'showSubscribe');
    }
}
