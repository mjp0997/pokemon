<?php

namespace App\View\Components\Pokedle;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AutofillOption extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $sprite, public string $name, public array $pokemon)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pokedle.autofill-option');
    }
}
