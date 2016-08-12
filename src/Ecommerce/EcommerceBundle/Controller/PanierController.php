<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Ecommerce\EcommerceBundle\Entity\UtilisateursAdresses;
use Ecommerce\EcommerceBundle\Form\UtilisateursAdressesType;
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
        $utilisateur = $this->container->get('security.context')->getToken()->getUser();
        $entity = new UtilisateursAdresses();
        $form = $this->createForm(new UtilisateursAdressesType(),$entity);
        
        if($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));
            if($form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $entity->setUtilisateur($utilisateur);
                $em->persist($entity);
                $em->flush();
                
                return $this->redirect($this->generateUrl('ecommerce_livraison'));
            }
        }
        return $this->render('EcommerceBundle:Default:panier/layout/livraison.html.twig',array('utilisateur'=>$utilisateur,'form'=>$form->createView()));
    }

    public function validationAction()
    {
        if($this->getRequest()->getMethod() == "POST") {
            $this->setLivraisonOnSession();
        }

        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $adresse = $session->get('adresse');
        $produits = $em->getRepository('EcommerceBundle:Produits')->findArray(array_keys($session->get('panier')));
        $livraison = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['livraison']);
        $facturation = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($adresse['facturation']);
        
        return $this->render('EcommerceBundle:Default:panier/layout/validation.html.twig',array('produits'=>$produits,'livraison'=>$livraison,'facturation'=>$facturation,'panier'=>$session->get('panier')));
    }
    
    public function setLivraisonOnSession() {
        $session = $this->getRequest()->getSession();
        if(!$session->has('adresse')) {
            $session->set('adresse',array());
        }
        $adresse = $session->get('adresse');
        
        if($this->get('request')->request->get('livraison') != null && $this->get('request')->request->get('facturation') !=null ) {
            $adresse['livraison'] = $this->get('request')->request->get('livraison');
            $adresse['facturation'] = $this->get('request')->request->get('facturation');
        } else {
            $this->redirect($this->generateUrl('ecommerce_validation'));
        }
        $session->set('adresse',$adresse);
        return $this->redirect($this->generateUrl('ecommerce_validation'));
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
            $this->get('session')->getFlashBag()->add('success',"Quantité ajouté avec succès");
        } else {
            if($this->getRequest()->query->get('qte') != null) {
                $panier[$id] = $this->getRequest()->query->get('qte');
            } else {
                $panier[$id] = 1;
            }
            $this->get('session')->getFlashBag()->add('success',"Article ajouté avec succès");
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
            $this->get('session')->getFlashBag()->add('success',"Article supprimé avec succès");
        }

        return $this->redirect($this->generateUrl('ecommerce_panier'));
    }
    
    public function menuAction() {
        $session = $this->getRequest()->getSession();

        if(!$session->has('panier')) {
            $article = 0;
        } else {
            $article = count($session->get(('panier')));
        }

        return $this->render('EcommerceBundle:Default:panier/modulesUsed/panier.html.twig',array('article'=>$article));
        
    }
    
    public function supprimmerAdresseLivraisonAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EcommerceBundle:UtilisateursAdresses')->find($id);
        if($this->container->get('security.context')->getToken()->getUser() != $entity->getUtilisateur() || !$entity) {
            return $this->redirect($this->generateUrl('ecommerce_livraison'));
        }
        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('ecommerce_livraison'));
    }
    
}
