<?php

namespace practo\Bundle\BlogBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use practo\Bundle\BlogBundle\Entity\Blog;

class BlogManager
{

    protected $doctrine;

    /**
     * Constructor
     *
     * @param Doctrine $doctrine - Doctrine
     */

    public function __construct(Doctrine $doctrine) 
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();

    }

    /**
     * Delete
     *
     */

    public function remove($id)
    {
         //practo\Bundle\BlogBundle\Entity;
        $blog = $this->em->getRepository('practoBlogBundle:Blog')->findOneById($id);
        $this->em->remove($blog);
        $this->em->flush();
    }

    /**
     * Load All
     *
     * @return Array
     */

    public function loadAll()
    {
         //practo\Bundle\BlogBundle\Entity;
         $blogs = $this->em->getRepository('practoBlogBundle:Blog')->findAll(); 
         return $blogs;       
    }

    /**
     * Load
     *
     * @return Array
     */

    public function load($id)
    {
         $blog = $this->em->getRepository('practoBlogBundle:Blog')->findOneById($id);
         return $blog;
    } 

    /**
     * Add
     *
     * @param Array $requestParams - Request Parameters
     */

    public function add($requestParams)
    {
        $blog = new Blog();
    
        return $this->updateFields($blog, $requestParams);
    }

    /**
     * Update fields of Blog Manager
     *
     * @param Blog  $blog          - Blog
     * @param Array $requestParams - Request Params
     *
     * @return Blog
     */
    private function updateFields($blog, $requestParams)
    {
        if (array_key_exists('title', $requestParams)) {
            $blog->setTitle($requestParams['title']);
        }
        if (array_key_exists('description', $requestParams)) {
            $blog->setDescription($requestParams['description']);
        }
        if (array_key_exists('user_id', $requestParams)) {
            $blog->setUserId($requestParams['user_id']);
        }

        /*$errors = $this->validator->validate($subArea);
        if (count($errors) > 0) {
            throw new ValidationError($errors);
        }*/

        $this->em->persist($blog);
        //if ($flush) {
            $this->em->flush();
        //}

        return $blog;
    }

    /**
     * Add
     *
     * @param Array $requestParams - Request Parameters
     *
     * @return Blog 
     */

    public function save($blog, $flush=true)
    {    
        $this->em->persist($blog);
        if ($flush) {
            $this->em->flush();
        }

        return $blog; 
    }   

}
