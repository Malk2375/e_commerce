<?php

namespace App\Form;

use App\Entity\SubCategory;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CategoryRepository;

class SubCategoryFormType extends AbstractType
{
  private $categoryRepository;

  public function __construct(CategoryRepository $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', TextType::class, [
        'attr' => ['class' => 'form-control'],
      ])
      ->add('category', EntityType::class, [
        'class' => Category::class,
        'choices' => $this->categoryRepository->findAll(),
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
      'data_class' => SubCategory::class,
    ]);
  }
}
