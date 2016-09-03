<?php

namespace practo\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('practoBlogBundle:Default:index.html.twig');
    }
}
