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

class UserSearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
                ->add('role', ChoiceType::class ,[
                    'placeholder' => 'role',
                    'choices' => [
                        'Mother' => Roles::ROLE_MOTHER,
                        'Child' => Roles::ROLE_CHILD,
                    ]
                ]);
//                ->add('children', EntityType::class , [
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
            'data_class' => 'TimeBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'timebundle_user';
    }


}
