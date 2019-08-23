<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function getCatalog()
    {
        $strs = \App::make('App\Parser\Onliner')->catalog();

    }
}
