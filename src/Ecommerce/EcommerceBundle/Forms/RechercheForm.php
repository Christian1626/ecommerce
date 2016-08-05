<?php
namespace Ecommerce\EcommerceBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RechercheForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recherche','text', array('label' => false,
            'attr' => array('class' => 'input-medium search-query'),'required'=>false));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ecommerce_ecommercebundle_recherche';
    }
}
