<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Akun Siswa', 'pageTitleName' => 'Akun Siswa', 'sidebarShow' => true])]
class AkunSiswa extends Component
{
    use WithPagination;

    public $search = '';
    public $studentAccounts;

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        // dd($this->studentAccounts);
        $studentAccounts = $this->studentAccounts = User::role('user')->with('siswa')->get();
        return view('livewire.dashboard.akun-siswa', [
            'studentAccounts' => $studentAccounts
        ]);
    }
}
