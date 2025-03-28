<?php

namespace App\View\Components;

use App\Models\Job;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class JobsForm extends Component
{
    public string $action;
    public Job|null $job;

    /**
     * Create a new component instance.
     */
    public function __construct(string $action, Job $job = null)
    {
        $this->action = $action;
        $this->job = $job;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.jobs.form');
    }
}
