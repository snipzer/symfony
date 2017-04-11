<?php
namespace BlogBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'ROLE_ADMIN' => 'Administrateur',
                    'ROLE_USER' => 'Utilisateur',
                ),
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('submit', SubmitType::class, array('label' => 'Create User'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => 'BlogBundle\Entity\User',
        ]);
    }

}

?>