<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Event;

class EventFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title');
        $builder->add('startDate', DateType::class, [
            'label' => 'When Does This Event Become Available',
            'widget' => 'choice',
            'required' => true,
        ]);
        $builder->add('endDate', DateType::class, [
            'label' => 'When Does This Event Stop Showing Up',
            'widget' => 'choice',
            'required' => false,
        ]);
        $builder->add('eventStartTime', TimeType::class, [
            'widget' => 'single_text',
            'with_minutes' => true,
        ]);
        $builder->add('eventEndTime', TimeType::class, [
            'widget' => 'single_text',
            'with_minutes' => true,
        ]);
        $builder->add('dayOfWeek', ChoiceType::class, [
            'label' => 'Days of the week',
            'choices' => [
                'Monday' => 'mon',
                'Tuesday' => 'tue',
                'Wednesday' => 'wed',
                'Thursday' => 'thur',
                'Friday' => 'fri',
                'Saturday' => 'sat',
                'Sunday' => 'sun'
            ],
            'empty_data' => [],
            'expanded' => true,
            'multiple' => true
        ]);
        $builder->add('registrationType', ChoiceType::class, [
            'choices' => [
                'Standard' => 'standard',
                'Assigned Seating' => 'assigned_seating',
                'Capacity' => 'capacity'
            ],
            'placeholder' => '- Select registration type -',
            'empty_data' => 'standard',
        ]);
        $builder->add('status', ChoiceType::class, [
            'choices' => [
                'Active' => 'active',
                'Paused' => 'paused',
                'Cancelled' => 'cancelled'
            ],
            'placeholder' => '- Select status -',
            'empty_data' => null,
        ]);
        $builder->add('primaryInstructor', EntityType::class, [
            'class' => 'UserBundle\Entity\User',
            'multiple' => false,
            'empty_data' => null,
            'choice_label' => function ($user, $key, $index) {
                /* @var User $user */
                return $user->getFirstName() . ' ' . $user->getLastName();
            },
        ]);

        //Pseudo fields for editing.
        $builder->add('editEventOptions', ChoiceType::class, [
            'mapped' => false,
            'label' => 'Would you like to change only this event or all events',
            'choices' => [
                'All events' => 'all_events',
                'Only this event' => 'only_this_event'
            ],
            'expanded' => true,
            'multiple' => false,
            'data' => 'all_events'
        ]);
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
