<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ExportButton extends Component
{
    public $route;
    public $filters;
    public $title;
    public $filename;

    public function __construct($route, $filters = [], $title = 'Export Data', $filename = null)
    {
        $this->route = $route;
        $this->filters = $filters;
        $this->title = $title;
        $this->filename = $filename;
    }

    public function render()
    {
        return view('components.export-button');
    }
}