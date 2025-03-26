<?php

namespace App\View\Components;

use App\Models\Employer;
use App\Models\Job;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class jobsForm extends Component
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
        $employers = Employer::all();

        return view('components.jobs.form', compact('employers'));
    }
}
