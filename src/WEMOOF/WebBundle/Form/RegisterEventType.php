<?php

namespace WEMOOF\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterEventType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEMOOF\BackendBundle\Command\RegisterEventCommand',
        ));
    }

    public function getName()
    {
        return 'register_event';
    }
}
