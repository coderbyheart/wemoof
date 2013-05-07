<?php

namespace WEMOOF\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('public', 'checkbox', array('label' => 'Öffentliches Profil?', 'required' => false))
            ->add('hasGravatar', 'checkbox', array('label' => 'Gravatar verwenden?', 'required' => false))
            ->add('firstname', 'text', array('label' => 'Vorname', 'required' => false, 'attr' => array('placeholder' => 'Max')))
            ->add('lastname', 'text', array('label' => 'Nachname', 'required' => false, 'attr' => array('placeholder' => 'Mustermann')))
            ->add('url', 'url', array('label' => 'Link', 'required' => false, 'attr' => array('placeholder' => 'https://www.xing.com/profile/Max_Mustermann')))
            ->add('twitter', 'text', array('label' => 'Twitter-Name', 'required' => false, 'attr' => array('placeholder' => '@max_mustermann', 'pattern' => '@[a-zA-Z0-9_]{1,15}')))
            ->add('description', 'textarea', array('label' => 'Beschreibung', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEMOOF\WebBundle\Model\EditProfileModel',
        ));
    }

    public function getName()
    {
        return 'edit_profile';
    }
}
