<?php

namespace App\Parser;

use Symfony\Component\DomCrawler\Crawler;
//use App\ProductUser;

use Auth;

use App\Category;

class Onliner implements ParseContract
{
    private $url='';
    private $crawler;
    public function __construct()
    {
        set_time_limit(0);
        header('Content-Type: text/html; charset=utf-8');
        $file= file_get_contents('https://catalog.onliner.by');
        $this->crawler = new Crawler($file);

    }
    public function getParse(){}
    public function catalog(){
        $arr = [];
        $this-> crawler->filter('.catalog-navigation-list__dropdown-list')->each(function(Crawler $node,$i){
            $text = $node->filter('.catalog-navigation-list__dropdown-title')->text();
            $url_str = $node ->attr('href');
            $url_arr = explode('/', $url_str);
            echo $text.'-'.end($url_arr)."<hr />";
        });
    }
}