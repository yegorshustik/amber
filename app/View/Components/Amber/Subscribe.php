<?php

namespace App\View\Components\Amber;

use App\Enums\Inbox\FieldType;
use App\Models\Inbox\Field;
use App\Models\Inbox\Form;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Subscribe extends Component
{
    public ?Form $form = null;

    public function __construct()
    {
        $this->form = Form::where('slug', 'subscribe')->first();
    }

    public function field(): ?Field
    {
        return $this->form?->fields()->where('type', FieldType::EMAIL)->first();
    }

    public function render(): View
    {
        return view('amber::components.subscribe');
    }
}
