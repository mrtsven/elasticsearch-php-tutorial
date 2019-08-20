<?php

namespace App\Http\Controllers;

use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class ElasticsearchController extends Controller
{
    /**
     * Our PHP Elasticsearch client
     *
     * @var Elasticsearch\ClientBuilder
     */
    protected $elasticSearchClient;
    protected $hosts = ['localhost:9200'];

    /**
     * Let's construct our ClientBuilder beforehand so that we can easily call it in the future.
     */
    public function __construct()
    {
        $this->elasticSearchClient = ClientBuilder::create()->setHosts($this->hosts)->build();
    }
}
