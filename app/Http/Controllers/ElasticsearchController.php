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

    /**
     * Create an index with its given name and properties.
     *
     * @return array
     */
    public function index()
    {
        $params = [
            'index' => 'custom-users', // The name of the index.
            'body' => [
                // Withing the body of an index we can set analyzers, filters, settings.
                // For now we are going to map properties.
                'mappings' => [
                    // In prior versions < 7.0, we would specify a type. As of 7.0 this is no longer allowed.
                    'properties' => [
                        'name' => [
                            'type' => 'keyword', // We can define what kind of type the property has, e.g. 'keyword', 'integer', 'string' *1.
                        ],
                        'email' => [
                            'type' => 'text' // This can be an integer, text, etc.
                        ]
                    ]
                ]
            ]
        ];

        return $this->elasticSearchClient->indices()->create($params);
    }

    /**
     * Fetch an index with it's mappings.
     *
     * @return array
     */
    public function getIndex()
    {
        // Set the index.
        $params = ['index' => 'custom-users'];
        // Multiple indices.
        $multiParams = [
            'index' => [
                'custom-users',
                'another-index',
                'etc'
            ]
        ];

        // Get settings of an index.
        // In Chapter: Advanced, we will talk more about settings, analyzers, filters and how we can apply them.
        $this->elasticSearchClient->indices()->getSettings($params); // We can also pass in $multiParams to fetch multiple indices at the same time.
        // Get mapping of an index.
        return $this->elasticSearchClient->indices()->getMapping($params);
    }

    /**
     * Remove a given index.
     *
     * @return array
     */
    public function deleteIndex()
    {
        $params = ['index' => 'custom-users'];

        return $this->elasticSearchClient->indices()->delete($params); // We can also pass in multiple indices into the delete function.
    }

    public function updateMappings()
    {
        $params = [
            'index' => 'custom-users',
            'body' => [
                'properties' => [
                    'age' => [ // New field will be added.
                        'type' => 'integer'
                    ]
                ]
            ]
        ];

        return $this->elasticSearchClient->indices()->putMapping($params);
    }
}
