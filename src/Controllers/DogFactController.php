<?php

namespace Vanier\Api\controllers;
use Vanier\Api\Helpers\WebServiceInvoker;

class DogFactController extends WebServiceInvoker
{
    public function getDogFacts (): array
    {
        $dogFacts_uri = 'https://api.api-ninjas.com/v1/animals?name=dog';
        $data = $this->invokeUri($dogFacts_uri);
        $facts = json_decode($data);
        $refined_facts = [];

        foreach($facts as $key => $fact)
        {
            $refined_facts[$key]['name'] = $fact->name;
            $refined_facts[$key]['location'] = $fact->location;  
            $refined_facts[$key]['characteristics'] = $fact->characteristics;
        }
        //var_dump($refined_facts);exit;
        return $refined_facts;
    }
}
