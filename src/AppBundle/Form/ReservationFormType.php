<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationFormType extends AbstractType
{

    /**
     * @var
     */
    private $account;

    /**
     * @var
     */
    private $event;

    /**
     * @var
     */
    private $date;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $this->account \UserBundle\Entity\User */
        $this->account = $options['account'];
        /* @var $this->account \AppBundle\Entity\Event */
        $this->event = $options['event'];

        $eventDate = new \DateTime();
        $eventDate->setTimestamp($options['date']);
        $this->date = $eventDate;

        $builder->add('attendee', EntityType::class, [
            'class' => 'UserBundle\Entity\User',
            'multiple' => false,
            'empty_data' => null,
            'data' => $this->account,
            'choice_label' => function ($user, $key, $index) {
                /* @var $user \UserBundle\Entity\User */
                return $user->getFirstName(). ' ' . $user->getLastName();
            },
        ]);

        $builder->add('event', EntityType::class, [
            'class' => 'AppBundle\Entity\Event',
            'multiple' => false,
            'empty_data' => null,
            'data' => $this->event,
            'choice_label' => function ($event, $key, $index) {
                /* @var $event \AppBundle\Entity\Event */
                return $event->getTitle();
            },
        ]);

        $builder->add('eventDate', null, [
            'data' => $this->date
        ]);
        $builder->add('checkedIn', CheckboxType::class, [
            'data' => false
        ]);
        $builder->add('paid');
        $builder->add('assignment');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reservation',
            'event' => null,
            'account' => null,
            'date' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reservation';
    }
}
