<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $readStatus = 'all';
    public $perPage = 10;

    protected $updatesQueryString = ['search', 'readStatus', 'perPage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }
    }

    public function markAsUnread($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification && $notification->read_at) {
            $notification->update(['read_at' => null]);
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $query = auth()->user()->notifications()->latest();

        if ($this->search) {
            $query->where('data->message', 'like', '%' . $this->search . '%');
        }

        if ($this->readStatus === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->readStatus === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate($this->perPage);

        return view('livewire.notification-table', [
            'notifications' => $notifications,
        ]);
    }
}
