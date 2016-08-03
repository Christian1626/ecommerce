<?php

namespace Ecommerce\EcommerceBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TestForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email','email',array('required'=>false))
            ->add("nom")->add("prenom")
            ->add("date","datetime")
            ->add("sexe",'choice',array('choices'=>array('0'=>'homme','1'=>'femme')))
            ->add('pays','country')
            ->add('utilisateurs','entity',array('class'=>'Utilisateurs\UtilisateursBundle\Entity\Utilisateurs'))
            ->add("content","textarea")
            ->add('envoyer',"submit");
    }
    
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ecommerce_ecommercebundle_test';
    }
}
