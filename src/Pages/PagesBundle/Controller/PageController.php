<?php

namespace Pages\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction($id)
    {
        return $this->render('PagesBundle:Default:pages/layout/pages.html.twig');
    }
}
