<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Riwayat Absen', 'pageTitleName' => 'Riwayat Absen', 'sidebarShow' => true])]
class RiwayatAbsen extends Component
{
    public function render()
    {
        return view('livewire.dashboard.riwayat-absen');
    }
}
