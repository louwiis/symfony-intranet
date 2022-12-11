<?php

namespace App\Form;

use App\Entity\Grade;
use App\Entity\Classe;
use App\Entity\Student;
use App\Repository\ClasseRepository;
use App\Repository\StudentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class GradeType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['schoolId'] = $options['schoolId'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
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
            'data_class' => Grade::Class,
            'schoolId' => null,
        ]);
    }
}
