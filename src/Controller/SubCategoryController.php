<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Piece;


class SubCategoryController extends AbstractController
{
    #[Route('/subcategory/add', name: 'add_subcategory')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subcategory = new SubCategory();
        $form = $this->createForm(SubCategoryFormType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subcategory = $form->getData();
            // Récupérer la catégorie sélectionnée dans le formulaire
            $category = $form->get('category')->getData();
            // Associer la catégorie à la sous-catégorie
            $subcategory->setCategory($category);
            $entityManager->persist($subcategory);
            $entityManager->flush();

            $this->addFlash('addedsubcategory', 'La sous catégorie a été créée avec succès');

            return $this->redirectToRoute('subcategories_display');
        }

        return $this->render('sub_category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/subcategory/{id}', name: 'subcategory', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function subcategorydisplay(EntityManagerInterface $entityManager, int $id): Response
    {
        $subCategory = $entityManager->getRepository(SubCategory::class)->find($id);
        $pieces = $entityManager->getRepository(Piece::class)->findBy(['subcategory' => $subCategory]);
        if (!$subCategory) {
            throw $this->createNotFoundException(
                'Sous category pas trouvé numéro ' . $id
            );
        }

        return $this->render('sub_category/show.html.twig', [
            'subCategory' => $subCategory,
            'pieces' => $pieces,
        ]);
    }
    #[Route('/subcategory/edit/{id}', name: 'subcategory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, SubCategory $subcategory): Response
    {
        $form = $this->createForm(SubCategoryFormType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('editedsubcategory', 'La sous catégorie a été modifiée avec succès');

            return $this->redirectToRoute('subcategories_display');
        }

        return $this->render('sub_category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/subcategory/delete/{id}', name: 'subcategory_delete', methods: ['GET', 'POST'])]
    public function deletesubcategory(EntityManagerInterface $entityManager, int $id): Response
    {
        $subcategory = $entityManager->getRepository(SubCategory::class)->find($id);
        if (!$subcategory) {
            throw $this->createNotFoundException(
                'Y a pas de categorie avec cet id :' . $id
            );
        }
        $entityManager->remove($subcategory);
        $entityManager->flush();
        $this->addFlash('deletedsubcategory', 'La sous categorie a été supprimée avec succès');
        return $this->redirectToRoute('subcategories_display');
    }
    #[Route('/subcategory/list', name: 'subcategories_display', methods: ['GET', 'POST'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $subcategories = $entityManager->getRepository(SubCategory::class)->findAll();
        $pieces = [];
        $category = [];
        foreach ($subcategories as $subcategory) {
            $pieces[$subcategory->getId()] = $subcategory->getPieces()->toArray();
            $category[$subcategory->getId()] = $subcategory->getCategory();
        }
        return $this->render('sub_category/list.html.twig', [
            'subcategories' => $subcategories,
            'pieces' => $pieces,
            'category' => $category,
        ]);
    }
}
