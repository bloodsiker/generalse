<?php

namespace Umbrella\app\Services\site;

use Carbon\Carbon;
use Umbrella\models\site\News;

class NewsService
{
    /**
     * @var string
     */
    private $lang;

    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
    }


    /**
     * @return array
     */
    public function getAllNews()
    {
        $news = News::getAllNews();

        $result = [];
        $i = 0;
        foreach ($news as $value){
            $result[$i]['image'] = $value['image'];
            $result[$i]['slug'] = $value['slug'];
            $result[$i]['title'] = $value[$this->lang .'_title'];
            $result[$i]['description'] = $value[$this->lang .'_description'];
            $result[$i]['text'] = $value[$this->lang .'_text'];
            $result[$i]['created_at'] = Carbon::parse($value['created_at'])->format('d.m.Y');
            $i++;
        }
        return $result;
    }

    public function findBySlug($slug)
    {
        $new = News::getNewBySlug($slug);

        $result['image'] = $new['image'];
        $result['slug'] = $new['slug'];
        $result['title'] = $new[$this->lang .'_title'];
        $result['text'] = $new[$this->lang .'_text'];
        $result['created_at'] = Carbon::parse($new['created_at'])->format('d.m.Y');

        return  $result;
    }

}