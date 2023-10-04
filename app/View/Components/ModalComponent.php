<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalComponent extends Component
{
    public $title;
    public $content;

    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    public function render()
    {
        return view('components.modal-component');
    }
}
