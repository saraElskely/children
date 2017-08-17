<?php

namespace TimeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use TimeBundle\constant\Schedule;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('taskName')->add('schedule', ChoiceType::class,[
            'placeholder' => 'your schedule !',
            'multiple'  => true,
            'expanded' => true,
            'choices' => [
                'Daily' => Schedule::SCHEDULE_DAILY,
                'Saturday' => Schedule::SCHEDULE_SATURDAY,
                'Sunday' => Schedule::SCHEDULE_SUNDAY,
                'Monday' => Schedule::SCHEDULE_MONDAY,
                'Tuesday'=> Schedule::SCHEDULE_TUESDAY,
                'Wednesday ' => Schedule::SCHEDULE_WEDNESDAY,
                'thursday'=> Schedule::SCHEDULE_THURSDAY,
                'Friday' => Schedule::SCHEDULE_FRIDAY
            ]
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimeBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'timebundle_task';
    }


}
