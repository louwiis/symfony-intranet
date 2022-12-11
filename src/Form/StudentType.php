<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\AddressType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class StudentType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['schoolId'] = $options['schoolId'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('address', AddressType::class)
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
                'choice_label' => 'name',
                'query_builder' => function (ClasseRepository $classeRepository) use ($options) {
                    return $classeRepository->createQueryBuilder('c')
                        ->where('c.school = :schoolId')
                        ->setParameter('schoolId', $options['schoolId']);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
            'schoolId' => null,
        ]);
    }
}