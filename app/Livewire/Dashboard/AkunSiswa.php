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
    // public $studentAccounts;

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::role('user')->with('siswa');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $studentAccounts = $query->paginate(2);

        return view('livewire.dashboard.akun-siswa', [
            'studentAccounts' => $studentAccounts
        ]);
    }
}
