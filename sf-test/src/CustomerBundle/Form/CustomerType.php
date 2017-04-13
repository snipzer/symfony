<?php

namespace CustomerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', [
                'required' => false,
            ])
            ->add('lastName')
            ->add('birthday', 'date', [
                'widget' => 'single_text',
            ])
            ->add('balance')
            ->add('address', new AddressType());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CustomerBundle\Entity\Customer',
        ]);
    }
}







