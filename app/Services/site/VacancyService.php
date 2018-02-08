<?php

namespace Umbrella\app\Services\site;

use Umbrella\models\site\Vacancy;

class VacancyService
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
    public function getAllVacancy()
    {
        $list_vacancy = Vacancy::getAllVacancy();

        $result = [];
        $i = 0;
        foreach ($list_vacancy as $value){
            $result[$i]['slug'] = $value['slug'];
            $result[$i]['title'] = $value[$this->lang .'_title'];
            $result[$i]['department'] = $value[$this->lang .'_department'];
            $result[$i]['location'] = $value[$this->lang .'_location'];
            $result[$i]['employment'] = $value[$this->lang .'_employment'];
            $result[$i]['text'] = $value[$this->lang .'_text'];
            $i++;
        }
        return $result;
    }

    /**
     * @param $slug
     *
     * @return mixed
     */
    public function findBySlug($slug)
    {
        $vacancy = Vacancy::getVacancyBySlug($slug);

        $result['slug'] = $vacancy['slug'];
        $result['title'] = $vacancy[$this->lang .'_title'];
        $result['department'] = $vacancy[$this->lang .'_department'];
        $result['location'] = $vacancy[$this->lang .'_location'];
        $result['employment'] = $vacancy[$this->lang .'_employment'];
        $result['text'] = $vacancy[$this->lang .'_text'];

        return  $result;
    }

}