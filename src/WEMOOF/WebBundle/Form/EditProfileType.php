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
            ->add('title', 'text', array('label' => 'Titel / Berufsbezeichnung', 'required' => false, 'max_length' => 100, 'attr' => array('placeholder' => 'Mann vom Fach')))
            ->add('url', 'url', array('label' => 'Link', 'required' => false, 'attr' => array('placeholder' => 'https://www.xing.com/profile/Max_Mustermann')))
            ->add('twitter', 'text', array('label' => 'Twitter-Name', 'required' => false, 'attr' => array('placeholder' => '@max_mustermann', 'pattern' => '@[a-zA-Z0-9_]{1,15}')))
            ->add('description', 'textarea', array('label' => 'Beschreibung', 'required' => false))
            ->add('tags', 'text', array('label' => 'Max. 3 Tags, je 15 Zeichen', 'required' => false, 'attr' => array('placeholder' => '#wemoof #webmontag #Offenbach', 'pattern' => '#[a-zA-Z0-9_]{1,15}( #[a-zA-Z0-9_]{1,15}){0,2}')))
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
