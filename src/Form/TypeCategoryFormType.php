<?php

namespace App\Form;

use App\Entity\TypeCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypeCategoryFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add(
        'name',
        TextType::class,
        [
          'attr' => [
            'class' => 'form-control',
          ],
          'label' => 'Nom du type'
        ]
      )
      ->add('sauvegarder', SubmitType::class, [
        'attr' => [
          'class' => 'btn btn-primary mt-4 btn-block',
        ]
      ]);;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => TypeCategory::class,
    ]);
  }
}
