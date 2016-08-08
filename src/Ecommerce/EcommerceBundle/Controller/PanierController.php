<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\RedirectResponse;

class PanierController extends Controller
{
    public function panierAction()
    {
       
        $session = $this->getRequest()->getSession();
//        $session->remove('panier');die('');
        if(!$session->has('panier')) {
            $session->set('panier',array());
        }
        
        
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));
        
        return $this->render('EcommerceBundle:Default:panier/layout/panier.html.twig',array('produits'=>$produits,'panier'=>$session->get('panier')));
    }

    public function livraisonAction()
    {
        return $this->render('EcommerceBundle:Default:panier/layout/livraison.html.twig');
    }

    public function validationAction()
    {
        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig');
    }

    public function ajouterAction($id)
    {
        //recupere la session
        $session = $this->getRequest()->getSession();
        //initialise
        if(!$session->has('panier')) {
            $session->set('panier',array());
        }
        $panier = $session->get('panier');
        if(array_key_exists($id,$panier)){
            if($this->getRequest()->query->get('qte') != null) {
                $panier[$id] = $this->getRequest()->query->get('qte');;
            }
        } else {
            if($this->getRequest()->query->get('qte') != null) {
                $panier[$id] = $this->getRequest()->query->get('qte');
            } else {
                $panier[$id] = 1;
            }
        }
        
        $session->set('panier',$panier);
        
        return $this->redirect($this->generateUrl('ecommerce_panier'));
    }

    public function supprimerAction($id)
    {
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier');

        if(array_key_exists($id,$panier)){
            unset($panier[$id]);
            $session->set('panier',$panier);
        }

        return $this->redirect($this->generateUrl('ecommerce_panier'));
    }
    
}
