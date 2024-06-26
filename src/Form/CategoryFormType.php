<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\TypeCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\TypeCategoryRepository;

class CategoryFormType extends AbstractType
{
  private $typeCategoryRepository;

  public function __construct(TypeCategoryRepository $typeCategoryRepository)
  {
    $this->typeCategoryRepository = $typeCategoryRepository;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'attr' => ['class' => 'form-control'],
      ])
      ->add('type', EntityType::class, [
        'class' => TypeCategory::class,
        'choices' => $this->typeCategoryRepository->findAll(),
        'choice_label' => 'name',
        'multiple' => false,
        'attr' => ['class' => 'form-select mt-3'],
      ])
      ->add('sauvegarder', SubmitType::class, [
        'attr' => ['class' => 'btn btn-primary mt-4 btn-block'],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Category::class,
    ]);
  }
}
