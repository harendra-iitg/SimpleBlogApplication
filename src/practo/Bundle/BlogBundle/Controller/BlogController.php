<?php

namespace practo\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use practo\Bundle\BlogBundle\Entity\Blog;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexAction()
    {
        return $this->render('practoBlogBundle:Blog:index.html.twig');
    }

    public function listAction()
    {
        $blogManager = $this->get('practo_blog.blog_manager');
        $arr = $blogManager->loadAll();
        //echo count($arr);die;
        return $this->render('practoBlogBundle:Blog:list.html.twig',array('blogs' => $arr));
    }

    public function deleteAction($id)
    {
        $blogManager = $this->get('practo_blog.blog_manager');
        $blogManager->remove($id);
        return $this->redirect($this->generateUrl('practo_blog_list'));
    }

    public function updateAction(Request $request, Blog $blog)
    {
        $blogManager = $this->get('practo_blog.blog_manager');
        $form = $this->createFormBuilder($blog)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $blogManager->save($task); 
            return $this->redirect($this->generateUrl('practo_blog_list'));
        }

        return $this->render('practoBlogBundle:Blog:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function createAction(Request $request)
    {
        $blogManager = $this->get('practo_blog.blog_manager');
        $form = $this->createFormBuilder(new Blog())
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $blogManager->save($task);
            return $this->redirect($this->generateUrl('practo_blog_list'));
        }

        return $this->render('practoBlogBundle:Blog:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
