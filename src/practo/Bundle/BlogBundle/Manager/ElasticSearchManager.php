<?php

namespace practo\Bundle\BlogBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use practo\Bundle\BlogBundle\Entity\Blog;
use Elasticsearch\Client;

class ElasticSearchManager
{

	protected $doctrine;

	/**
	 * Constructor
	 *
	 * @param Doctrine $doctrine - Doctrine
	 */

	public function __construct(Client $elasticsearch, Doctrine $doctrine, $indexName) 
	{
		$this->doctrine = $doctrine;
		$this->elasticsearch = $elasticsearch;
		$this->em = $this->doctrine->getManager();
		$this->indexName = $indexName;

	}

	public function createIndex() {
		$this->elasticsearch->indices()->create(array('index' => $this->indexName));
	}

	public function indexingDocuments() {
		$blogs = $this->em->getRepository('practoBlogBundle:Blog')->findAll();
		foreach($blogs as $blog) {
			$body = ['title'=>$blog->getTitle(), 'description' => $blog->getDescription()];
			$params = ['index' => $this->indexName, 'type' => 'post', 'id' => $blog->getId(),'body' => $body];
			$response = $this->elasticsearch->index($params);
		}
	}

	public function indexingSingleDocument(Blog $blog) {
		//$blog = $this->em->getRepository('practoBlogBundle:Blog')->findOneById($id);
		$body = ['id' => $blog->getId(), 'title' => $blog->getTitle(), 'description' => $blog->getDescription()];
		$params = ['index' => $this->indexName, 'type' => 'post', 'id' => $blog->getId(),'body' => $body];
		$response = $this->elasticsearch->index($params);
	}

	public function getDocument($id) {
		$params = ['index' => $this->indexName, 'type' => 'post','id' => $id];
		$response = $this->elasticsearch->get($params);
		return $response;

	}

	public function deleteIndex() {
		$params = ['index' => $this->indexName];
		$response = $this->elasticsearch->indices()->delete($params);
		return $response;
	}

	public function indexExist() {
		return $this->elasticsearch->indices()->exists(array('index' => $this->indexName));
	}

	public function deleteDocument($id) {
		// $this->elasticsearch->delete($id);
	}

	public function search($searchTerm) {
		$params = ['index' => $this->indexName,'type' => 'post','body' => ['query' => ['match' => ['title' => $searchTerm]]]];
		$results = $this->elasticsearch->search($params);
                $rstArr = $results['hits']['hits'];
                $matchArr = [];
                foreach($rstArr as $rst) {
                   $matchArr[] = $rst['_source'];
                }
                return $matchArr;
	}

}
