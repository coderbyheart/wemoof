<?php

namespace WEMOOF\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnregisterEventType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEMOOF\BackendBundle\Command\UnregisterEventCommand',
        ));
    }

    public function getName()
    {
        return 'unregister_event';
    }
}
