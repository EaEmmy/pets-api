<?php

namespace Vanier\Api\controllers;
use Vanier\Api\Helpers\WebServiceInvoker;

/**
 * Summary of JokeController
 */
class JokeController extends WebServiceInvoker
{
<<<<<<< HEAD
    /**
     * Summary of getJokes
     * @return mixed
     */
=======
>>>>>>> 8ad8cc0cfca5444ed2136984858bc0e990e2e674
    public function getJokes ()
    {
        $jokes_uri = 'https://official-joke-api.appspot.com/random_joke';
        $data = $this->invokeUri($jokes_uri);
        $facts = json_decode($data);
      
        return $facts;
    }
}
