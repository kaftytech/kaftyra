<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\AuditLog;

class Log extends Component
{
    /**
     * The audit logs to display.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $auditLogs;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // You can pass pagination here if necessary or pass all audit logs
        $this->auditLogs = AuditLog::with('user')->latest()->paginate(10);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.log');
    }
}
