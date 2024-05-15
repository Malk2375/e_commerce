<?php

namespace App\Form;

use App\Entity\Piece;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormInterface;

class PieceFormType extends AbstractType
{
  public function __construct(private SubCategoryRepository $subCategoryRepository){}

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
    ->add('name', TextType::class, [
      'attr' => ['class' => 'form-control mb-4'],
      'required' => true,
    ])
      ->add('category', EntityType::class, [
        'class' => Category::class,
        'placeholder' => 'Choisis une categorie',
        'choice_label' => 'name',
        'attr' => ['class' => 'form-control mb-4'],
        'query_builder' => function (CategoryRepository $categoryRepository) {
          return $categoryRepository->findAllOrderedByAscNameQueryBuilder();
        }
      ])
      // ->add('sauvegarder', SubmitType::class, [
      //   'attr' => ['class' => 'btn btn-primary mt-4 btn-block'],
      // ]);
      // ->add('subCategory', EntityType::class, [
      //   'class' => SubCategory::class,
      //   'placeholder' => 'Choisis une categorie',
      //   'choice_label' => 'name',
      //   'attr' => ['class' => 'form-control mb-4'],
      //   'required' => false,
      //   'query_builder' => function (SubCategoryRepository $subCategoryRepository) {
      //     return $subCategoryRepository->findAllOrderedByAscNameQueryBuilder();
      //   }
      // ])
      ;

    $formModifier = function (FormInterface $form, ?Category $category = null): void {
      $subCategories = null === $category ? [] : $this->subCategoryRepository->findByCategoriesOrderedByAscName($category);
      $form->add('subCategory', EntityType::class, [
        'class' => SubCategory::class,
        'placeholder' => 'Choisis une sous categorie',
        'choice_label' => 'name',
        'choices' => $subCategories,
        'required' => false,
        'attr' => ['class' => 'form-control mb-4'],
      ]);
    };

    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,
      function (FormEvent $event) use ($formModifier): void {
        // this would be your entity, i.e. SportMeetup
        $data = $event->getData();

        $formModifier($event->getForm(), $data->getCategory());
      }
    );

    $builder->get('category')->addEventListener(
      FormEvents::POST_SUBMIT,
      function (FormEvent $event) use ($formModifier): void {
        // It's important here to fetch $event->getForm()->getData(), as
        // $event->getData() will get you the client data (that is, the ID)
        $category = $event->getForm()->getData();

        // since we've added the listener to the child, we'll have to pass on
        // the parent to the callback function!
        $formModifier($event->getForm()->getParent(), $category);
      }
    );
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Piece::class,
    ]);
  }
}
