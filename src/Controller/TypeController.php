<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\TypeCategory;
use App\Form\TypeCategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class TypeController extends AbstractController
{
    #[Route('/type/list', name: 'type_display', methods: ['GET', 'POST'])]
    public function typeslist(EntityManagerInterface $entityManager): Response
    {
        $types = $entityManager->getRepository(TypeCategory::class)->findAll();
        $categories = [];
        foreach ($types as $type) {
            $categories[$type->getId()] = $type->getCategories()->toArray();
        }
        return $this->render('type/list.html.twig', [
            'types' => $types,
            'categories' => $categories,
        ]);
    }
    #[Route('/type/add', name: 'type_add', methods: ['GET', 'POST'])]
    public function addType(Request $request, EntityManagerInterface $entityManager): Response
    {
        // $user = $this->getUser();
        $typecategory = new TypeCategory();
        $form = $this->createForm(TypeCategoryFormType::class, $typecategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typecategory = $form->getData();
            $entityManager->persist($typecategory);
            $entityManager->flush();
            $this->addFlash(
                'addtypesuccess',
                'Le Type a été créé avec succès'
            );
            return $this->redirectToRoute('type_display');
        }
        return $this->render('type/add.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/type/edit/{id}', name: 'type_edit', methods: ['GET', 'POST'])]
    public function editType(Request $request, EntityManagerInterface $entityManager, TypeCategory $type): Response
    {
        $form = $this->createForm(TypeCategoryFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le type a été modifié avec succès');

            return $this->redirectToRoute('app_category');
        }

        return $this->render('type/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/type/delete/{id}', name: 'type_delete', methods: ['GET', 'POST'])]
    public function deleteType(EntityManagerInterface $entityManager, int $id): Response
    {
        $type = $entityManager->getRepository(TypeCategory::class)->find($id);
        if (!$type) {
            throw $this->createNotFoundException(
                'Y a pas de type avec cet id :' . $id
            );
        }
        $entityManager->remove($type);
        $entityManager->flush();
        $this->addFlash('typesupprime', 'Le type a été supprimé avec succès');
        return $this->redirectToRoute('app_home');
    }
}
