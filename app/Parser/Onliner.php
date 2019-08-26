<?php

namespace App\Parser;

use Symfony\Component\DomCrawler\Crawler;
//use App\ProductUser;

use Carbon\Carbon;

use Auth;

use App\Product;

use App\Category;

class Onliner implements ParseContract
{
    private $url = '';
    private $crawler;

    public function __construct()
    {
        set_time_limit(0);
        header('Content-Type: text/html; charset=utf-8');
        $file = file_get_contents('https://catalog.onliner.by');
        $this->crawler = new Crawler($file);

    }

    public function getParse($slug = null)
    {
        $url = 'https://catalog.api.onliner.by/search/'.$slug.'?page=1';
        $pos = str_ireplace('http://catalog.onliner.by/', '', $slug);
        $file = file_get_contents($url);
        $crawler = new Crawler($file);
        $ask = strpos($pos, '?');
        if ($ask === false) {
            $vopros = '?';
        } else {
            $vopros = '&';
        }
        $http = 'https://catalog.api.onliner.by/search/' . $slug . $vopros . 'page=1';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $http);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $ke = 0;
        $data_arr = (array)json_decode($result);
        foreach ($data_arr as $key => $value) {
            if (is_array($value)) {

                foreach ($value as $val_key => $data_value) {
                    $val_value = (array)$data_value;
                    $site_id = (isset($val_value['id'])) ? $val_value['id'] : '';
                    $site_key = (isset($val_value['key'])) ? $val_value['key'] : '';
                    $name = (isset($val_value['full_name'])) ? $val_value['full_name'] : '';
                    $pic_value = (array)$val_value['images'];
                    $header_picture = (isset($pic_value['header'])) ? $pic_value['header'] : '';
                    $icon_picture = (isset($pic_value['icon'])) ? $pic_value['icon'] : '';
                    $description = (isset($val_value['description'])) ? $val_value['description'] : '';
                    $site_product_url = (isset($val_value['html_url'])) ? $val_value['html_url'] : '';
                    $rev_value = (array)$val_value['reviews'];
                    $rating = (isset($rev_value['rating'])) ? (integer)$rev_value['rating'] : '';
                    $site_reviews_url = (isset($rev_value['html_url'])) ? $rev_value['html_url'] : '';
                    $price_value = (array)$val_value['prices'];
                    $price_min = (isset($price_value['min'])) ? $price_value['min'] : '';
                    $price_max = (isset($price_value['max'])) ? $price_value['max'] : '';
                    $site_prices_url = (isset($price_value['html_url'])) ? $price_value['html_url'] : '';
                    $currency = (isset($price_value['currency_sign'])) ? $price_value['currency_sign'] : '';
                    $forum_value = (array)$val_value['forum'];
                    $site_forum_url = (isset($forum_value['topic_url'])) ? $forum_value['topic_url'] : '';
                    $site_api_url = (isset($val_value['url'])) ? $val_value['url'] : '';
                    //добавляем товар в базу данных
                    $prod = new Product();
                    $prod->category_id = (isset($cat->id)) ? $cat->id : 0;
                    $prod-> url = 'onliner.by';
                    $prod->name = $name;
                    $prod->img = $header_picture;
                    $prod->description = $description;
                    //$prod->site_product_url = $site_product_url;
                    //$prod->site_reviews_url = $site_reviews_url;
                    //$prod->rating = $rating;
                    //$prod->count = 1;
                    $prod ->price = $price_min;
                    //$prod->price_min = $price_min;
                    //$prod->price_max = $price_max;
                    //$prod->site_prices_url = $site_prices_url;
                    //$prod->currency = $currency;
                    //$prod->site_forum_url = $site_forum_url;
                    //$prod->site_api_url = $site_api_url;
                    $prod->user_id = Auth::user()->id;
                    $prod->save();
                    echo $name.'<br>';
                    sleep(1);
                    $ke++;
                }
            }
        }
    }

    public function catalog()
    {
        $arr = [];
        $this->crawler->filter('.catalog-navigation-list__dropdown-list')->each(function (crawler $node, $i) {
            $text = $node->filter('.catalog-navigation-list__dropdown-title')->text();
            $url_str = $node->filter('.catalog-navigation-list__dropdown-item')->attr('href');
            $url_arr = explode('/', $url_str);
            echo "<a href='/parse/add/" . end($url_arr) . "'>" . $text . '-' . end($url_arr) . "</a><hr />";
        });
    }

    public function catalogOne($slug = null)
    {
        $url = 'https://catalog.onliner.by/' . $slug;
        $file = file_get_contents($url);
        $crawler = new Crawler($file);
        $name = $crawler->filter('h1.schema-header__title')->text();
        $url_arr = explode('/', $url);
        echo "<a href='/parse/onliner_product/" .end($url_arr). "'>" . $name . '-' . end($url_arr) . "</a><hr />";
        return $name;
    }
}