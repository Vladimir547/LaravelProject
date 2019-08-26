<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function getCatalog()
    {
        $strs = \App::make('App\Parser\Onliner')->catalog();

    }
    public function addCatalog($slug = null){
        $strs = \App::make('App\Parser\Onliner')->catalogOne($slug);
        $cat = Category::where('slug', $slug)->first();
        if(!$cat){
            $obj = new Category;
            $obj ->name = $strs;
            $obj -> slug = $slug;
            $obj -> order = '1';
            $obj -> save();
        }
    }
    public function getParse($slug = null){
        $one = \App::make('App\Parser\Onliner')->getParse($slug);
    }

}
