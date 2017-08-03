<?php

namespace TimeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use TimeBundle\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use TimeBundle\constant\Roles;

class TaskSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('taskName')
                ->add('schedule', ChoiceType::class ,[
                    'placeholder' => 'schedule',
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
//                ->add('mother', EntityType::class , [
//                    'class' => 'TimeBundle:User',
//                    'query_builder' => function(UserRepository $repo){
//                        return $repo->getChildrenQueryBuilder();
//                    }
//                ]);
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

