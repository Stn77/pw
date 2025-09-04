<?php

namespace App\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class CustomPagination extends Component
{
    use WithPagination;

    public $data;
    public $perPage = 10;
    public $pageRange = 5; // Jumlah nomor halaman yang ditampilkan

    public function mount($data, $perPage = 10)
    {
        $this->data = $data;
        $this->perPage = $perPage;
    }

    public function render()
    {
        dd($this->data);
        $paginatedData = $this->data->paginate($this->perPage);

        return view('livewire.custom-pagination', [
            'paginatedData' => $paginatedData,
            'pages' => $this->getPages($paginatedData)
        ]);
    }

    private function getPages(LengthAwarePaginator $paginatedData)
    {
        $currentPage = $paginatedData->currentPage();
        $lastPage = $paginatedData->lastPage();

        $start = max(1, $currentPage - floor($this->pageRange / 2));
        $end = min($lastPage, $start + $this->pageRange - 1);

        // Adjust start if we're near the end
        if ($end - $start + 1 < $this->pageRange) {
            $start = max(1, $end - $this->pageRange + 1);
        }

        return range($start, $end);
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function previousPage()
    {
        if ($this->data->currentPage() > 1) {
            $this->setPage($this->data->currentPage() - 1);
        }
    }

    public function nextPage()
    {
        if ($this->data->currentPage() < $this->data->lastPage()) {
            $this->setPage($this->data->currentPage() + 1);
        }
    }
}
