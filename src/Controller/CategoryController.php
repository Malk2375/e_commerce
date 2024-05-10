<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    /**
     * This controller creates a new category
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @return Response
     */
    #[Route('/category/add', name: 'category_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        // $user = $this->getUser();
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'La category a été créé avec succès'
            );
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/addCategory.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/category/edit/{id}', name: 'category_edit', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, Category $category, CategoryRepository $repository, int $id): Response
    {
        $category = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash(
                'edit_success',
                'La category a été modifiée avec succès'
            );
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/editCategory.html.twig', [
            'form' => $form->CreateView(),
        ]);
    }
}
