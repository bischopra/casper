<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('city')
                ->add('address')
                ->add('description')
                ->add('eventDate', null, [
                    'widget' => 'choice',
                    'years' => range(date("Y"), date("Y") + 10)
                ])
                ->add('duration')
                ->add('maxGuestCount')
                ->add('applyEndDate', null, [
                    'label' => 'Can Apply Untill',
                    'widget' => 'choice',
                    'years' => range(date("Y"), date("Y") + 10)
                    ])
                ->add('isPrivate', ChoiceType::class, [
                    'label' => 'Event Type',
                    'choices' => [
                        'Public' => 0,
                        'Private' => 1,
                    ]
                ])
                ->add('latitude', HiddenType::class)
                ->add('longitude', HiddenType::class)
                ->add('save', SubmitType::class, ['label' => 'Send']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_event';
    }


}
