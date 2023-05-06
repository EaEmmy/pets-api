<?php

namespace Vanier\Api\controllers;
use Vanier\Api\Helpers\WebServiceInvoker;

/**
 * Summary of JokeController
 */
class JokeController extends WebServiceInvoker
{
    /**
     * Summary of getJokes
     * @return mixed
     */
    public function getJokes ()
    {
        $jokes_uri = 'https://official-joke-api.appspot.com/random_joke';
        $data = $this->invokeUri($jokes_uri);
        $facts = json_decode($data);
      
        return $facts;
    }
}
