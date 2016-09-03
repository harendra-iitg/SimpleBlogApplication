<?php

namespace practo\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        return $this->render('practoBlogBundle:Blog:index.html.twig');
    }

    public function createAction()
    {
        return $this->render('practoBlogBundle:Blog:create.html.twig');
    }
}
