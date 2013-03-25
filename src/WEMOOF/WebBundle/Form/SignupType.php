<?php

namespace WEMOOF\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'text', array('label' => 'E-Mail-Adresse', 'attr' => array('placeholder' => 'name@domain.de')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEMOOF\BackendBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'signup';
    }
}
