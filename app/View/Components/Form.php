<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Form extends Component
{
    public string $method;

    public function __construct($method = 'GET')
    {
        $this->method = Str::upper($method);
    }

    // ---- ------ -- --- -------- ---------- --- ------- ------- ------
    // This Method Is For Getting, Validating And Setting Request Method
    // ---- ------ -- --- -------- ---------- --- ------- ------- ------

    public function setMethod ()
    {
        if ($this->method == 'POST' || $this->method == 'GET')
        {
            return 'method=' . $this->method;
        }
        else if ($this->method == "PUT" || $this->method == "PATCH" || $this->method == "DELETE")
        {
            return 'method=POST';
        }
        else
        {
            abort(405, "Invalid Method '{$this->method}'");
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.form');
    }

}