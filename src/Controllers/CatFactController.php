<?php

namespace Vanier\Api\controllers;
use Vanier\Api\Helpers\WebServiceInvoker;

class CatFactController extends WebServiceInvoker
{
    public function getCatFacts (): array
    {
        $catFacts_uri = 'https://cat-fact.herokuapp.com/facts/';
        $data = $this->invokeUri($catFacts_uri);
        $facts = json_decode($data);
        $refined_facts = [];

        foreach($facts as $key => $fact)
        {
            $refined_facts[$key]['text'] = $fact->text;
            $refined_facts[$key]['type'] = $fact->type;  
            $refined_facts[$key]['createdAt'] = $fact->createdAt;
            $refined_facts[$key]['source'] = $fact->source;
        }
        //var_dump($refined_facts);exit;
        return $refined_facts;
    }

}
