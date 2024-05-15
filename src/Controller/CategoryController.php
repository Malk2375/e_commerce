<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Form\CategoryFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/add', name: 'category_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('addedcategory', 'La catégorie a été créée avec succès');

            return $this->redirectToRoute('categories_display');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/category/list', name: 'categories_display', methods: ['GET', 'POST'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $subCategories = [];
        $type = [];
        foreach ($categories as $category) {
            $subCategories[$category->getId()] = $category->getSubCategories()->toArray();
            $type[$category->getId()] = $category->getType();
        }
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'type' => $type,
        ]);
    }
    #[Route('/category/{id}', name: 'category_display', methods: ['GET', 'POST'])]
    public function categorydisplay(EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);
        $subcategories = $entityManager->getRepository(SubCategory::class)->findBy(['category' => $category]);
        if (!$category) {
            throw $this->createNotFoundException(
                'Categorie pas trouvé numéro ' . $id
            );
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'subcategories' => $subcategories,
        ]);
    }


    #[Route('/category/edit/{id}', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('editedcategory', 'La catégorie a été modifiée avec succès');

            return $this->redirectToRoute('categories_display');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/category/delete/{id}', name: 'category_delete', methods: ['GET', 'POST'])]
    public function deletecategory(EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);
        if (!$category) {
            throw $this->createNotFoundException(
                'Y a pas de categorie avec cet id :' . $id
            );
        }
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('categoriesupprime', 'La categorie a été supprimée avec succès');
        return $this->redirectToRoute('categories_display');
    }
}
