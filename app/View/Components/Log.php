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

    public function __construct($auditLogs = null)
    {
        $this->auditLogs = $auditLogs;
        // dd($this->auditLogs);

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
