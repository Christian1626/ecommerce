<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitsController extends Controller
{
    public function produitsAction()
    {
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig');
    }

    public function produitAction()
    {
        return $this->render('EcommerceBundle:Default:produits/layout/produit.html.twig');
    }
}
