<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Forms\RechercheForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitsController extends Controller
{
    public function produitsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findBy(array('disponible'=> 1));
        
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig',array('produits'=>$produits));
    }

    public function categorieAction($categorie)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('EcommerceBundle:Categories')->find($categorie);
        if(!$categorie) throw  $this->createNotFoundException('La page n\'existe pas');
        
        $produits = $em->getRepository('EcommerceBundle:Produits')->byCategorie($categorie);
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig',array('produits'=>$produits));
    }

    public function produitAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);
        if(!$produit) throw  $this->createNotFoundException('La page n\'existe pas');
        
        return $this->render('EcommerceBundle:Default:produits/layout/produit.html.twig',array('produit'=>$produit));
    }

    public function rechercheAction() {
        $recherche = $this->createForm(new RechercheForm());
        return $this->render('EcommerceBundle:Default:produits/modulesUsed/recherche.html.twig',array('recherche'=>$recherche->createView()));
    }
    public function rechercheTraitementAction()
    {
        $form = $this->createForm(new RechercheForm());
        $produits = null;
        if($this->get('request')->getMethod() == "POST") {
            $form->bind($this->get('request'));
            $em = $this->getDoctrine()->getManager();
            if($form['recherche']->getData()) {
                $produits = $em->getRepository('EcommerceBundle:Produits')->recherche($form['recherche']->getData());
            } else {
                $produits = $em->getRepository('EcommerceBundle:Produits')->findAll();
            }
        } else throw $this->createNotFoundException("La page n\'existe pas.");

        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig',array('produits'=>$produits));
    }
    
}
