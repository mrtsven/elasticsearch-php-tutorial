<?php

namespace App\Http\Controllers;

use App\User;
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

    /**
     * Index a single document.
     *
     * @return array
     */
    public function saveSingleDocument()
    {
        // Given object, e.g. a User
        $user = User::first();
        // Given parameters for the document.
        $params = [
            'index' => 'custom-users', // Define to which index the document has to be saved.
            'id' => $user->id, // If this field is omitted, elasticsearch will auto generate an id.
            'body' => [ // The body is the primary source of information. This will be mainly used to search on.
                // field => 'your_data'
                'email' => $user->email,
            ]
        ];

        return $this->elasticSearchClient->index($params);
    }

    /**
     * Index a bulk of documents.
     *
     * @return array
     */
    public function saveBulkDocuments()
    {
        $users = User::get();

        foreach ($users as $user) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'custom-users',
                    '_type' => '_doc',
                    '_id' => $user->id
                ]
            ];

            $params['body'][] = [
                'email' => $user->email,
            ];
        }

        return $this->elasticSearchClient->bulk($params);
    }


    /**
     * Fetch a document from the given index by their id.
     *
     * @return array
     */
    public function getDocument()
    {
        $user = User::first();

        $params = [
            'index' => 'custom-users',
            'id'    => $user->id
        ];

        return $this->elasticSearchClient->get($params);
    }

    /**
     * Update a document.
     *
     * @return array
     */
    public function updateDocument()
    {
        $user = User::first();

        $params = [
            'index' => 'custom-users',
            'id'    => $user->id,
            'body'  => [
                'doc' => [
                    'email' => 'new@email.com', // We update an existing field.
                    'name' => $user->name // New field, this will be merged with the existing document.
                ]
            ]
        ];

        return $this->elasticSearchClient->update($params);
    }

    /**
     * Delete a document from the given index by the id of that document.
     *
     * @return array
     */
    public function deleteDocument()
    {
        $user = User::first();

        $params = [
            'index' => 'custom-users',
            'id'    => $user->id
        ];

        return $this->elasticSearchClient->delete($params);
    }
}
