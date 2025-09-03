<?php

namespace App\Livewire\Scanner;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Scanner', 'pageTitleName' => 'Scanner', 'sidebarShow' => true])]
class Scanner extends Component
{

    public function render()
    {
        return view('livewire.scanner.scanner');
    }
}
