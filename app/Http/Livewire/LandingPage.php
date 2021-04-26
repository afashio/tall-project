<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LandingPage extends Component
{
    /**
     * @var string
     */
    public string $email = '';

    public function render()
    {
        return view('livewire.landing-page');
    }

    /**
     * @return void
     */
    public function subscribe(): void
    {
        Log::debug($this->email);
    }
}
