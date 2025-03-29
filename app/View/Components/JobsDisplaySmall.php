<?php

namespace App\View\Components;

use App\Models\Job;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class JobsDisplaySmall extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Job $job)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.jobs.display-small');
    }
}
