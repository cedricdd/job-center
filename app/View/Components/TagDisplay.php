<?php

namespace App\View\Components;

use Closure;
use App\Models\Tag;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class TagDisplay extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Tag $tag)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tag-display');
    }
}
