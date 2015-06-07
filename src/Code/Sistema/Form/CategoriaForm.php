<?php

namespace Code\Sistema\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class CategoriaForm extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder->add('nome', "text", array(
                        'constraints' => array(new NotBlank(), new Length(array('max' => 10))),
                            )
                    )
                    ->add('cadastrar', 'submit', array('label' => 'Confirmar'));
    }

    public function getName()
    {
        return 'CategoriaForm';
    }
}
