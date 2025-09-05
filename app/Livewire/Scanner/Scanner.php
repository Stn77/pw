<?php

namespace App\Livewire\Scanner;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Scanner', 'pageTitleName' => 'Scanner', 'sidebarShow' => true])]
class Scanner extends Component
{
    use WithPagination;
    public function mount()
    {
        // $this->middleware('role:admin|scanner');
    }

    public function checkScan()
    {
        $this->dispatchBrowserEvent('scan-detected', ['message' => 'QR Code terdeteksi!']);
    }

    public function render()
    {
        return view('livewire.scanner.scanner');
    }
}
