<?php

namespace App\Livewire;

use App\Models\CreditTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Credit History')]
class CreditHistory extends Component
{
    use WithPagination;

    public string $filter = 'all';

    #[Computed]
    public function transactions()
    {
        $query = CreditTransaction::where('user_id', Auth::id());

        if ($this->filter === 'earned') {
            $query->where('amount', '>', 0);
        } elseif ($this->filter === 'spent') {
            $query->where('amount', '<', 0);
        }

        return $query->latest()->paginate(20);
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.credit-history');
    }
}
