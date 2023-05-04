<?php

namespace Vanier\Api\controllers;
use Vanier\Api\Helpers\WebServiceInvoker;

class JokeController extends WebServiceInvoker
{
    public function getJokes (): array
    {
        $jokes_uri = 'https://official-joke-api.appspot.com/random_joke';
        $data = $this->invokeUri($jokes_uri);
        $facts = json_decode($data);
        $refined_facts = [];
        //var_dump($refined_facts);exit;

        foreach($facts as $key => $fact)
        {
            $refined_facts[$key]['type'] = $fact->type;
            $refined_facts[$key]['setup'] = $fact->setup;  
            $refined_facts[$key]['punchline'] = $fact->punchline;
            $refined_facts[$key]['id'] = $fact->id;
        }

        //var_dump($refined_facts);exit;
        return $refined_facts;
    }
}
