<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\Categories;
use Ecommerce\EcommerceBundle\Forms\RechercheForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitsController extends Controller
{
    
    public function produitsAction(Categories $categorie = null)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        if($categorie != null) {
            $categorie = $em->getRepository('EcommerceBundle:Categories')->find($categorie);
            if(!$categorie) throw  $this->createNotFoundException('La page n\'existe pas');
            $produits = $em->getRepository('EcommerceBundle:Produits')->byCategorie($categorie);
        } else {    $produits = $em->getRepository('EcommerceBundle:Produits')->findBy(array('disponible'=> 1));
        }
        if($session->has('panier')) {
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }
        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig',array('produits'=>$produits,'panier'=>$panier));
    }

//    public function categorieAction($categorie)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $categorie = $em->getRepository('EcommerceBundle:Categories')->find($categorie);
//        if(!$categorie) throw  $this->createNotFoundException('La page n\'existe pas');
//        
//        $produits = $em->getRepository('EcommerceBundle:Produits')->byCategorie($categorie);
//        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig',array('produits'=>$produits));
//    }

    public function produitAction($id)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('EcommerceBundle:Produits')->find($id);
        if(!$produit) throw  $this->createNotFoundException('La page n\'existe pas');
        if($session->has('panier')) {
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }
        
        return $this->render('EcommerceBundle:Default:produits/layout/produit.html.twig',array('produit'=>$produit,'panier'=>$panier));
    }

    public function rechercheAction() {
        $recherche = $this->createForm(new RechercheForm());
        return $this->render('EcommerceBundle:Default:produits/modulesUsed/recherche.html.twig',array('recherche'=>$recherche->createView()));
    }
    public function rechercheTraitementAction()
    {
        $session = $this->getRequest()->getSession();
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
        if($session->has('panier')) {
            $panier = $session->get('panier');
        } else {
            $panier = false;
        }

        return $this->render('EcommerceBundle:Default:produits/layout/produits.html.twig',array('produits'=>$produits,'panier'=>$panier));
    }
    
}
