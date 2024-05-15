<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Piece;
use App\Entity\SubCategory;
use App\Entity\Part;
use App\Form\PieceFormType;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;

use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PieceController extends AbstractController
{
    #[Route('/piece/index', name: 'piece_display', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('piece/index.html.twig', compact('form'));
    }
    #[Route('/piece/add', name: 'piece_add', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addpiece(Request $request, EntityManagerInterface $entityManager): Response
    {
        $piece = new Piece();
        $form = $this->createForm(PieceFormType::class, $piece);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($piece);
            $entityManager->flush();
            $this->addFlash('addedpiece', 'La piece a été créée avec succès');
            // return $this->redirectToRoute('piece_display');
        }
        return $this->render('piece/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/piece/{id}', name: 'piece', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function piecedisplay(EntityManagerInterface $entityManager, int $id): Response
    {
        $piece = $entityManager->getRepository(Piece::class)->find($id);
        $parts = $entityManager->getRepository(Part::class)->findBy(['piece' => $piece]);
        if (!$piece) {
            throw $this->createNotFoundException(
                'Piece pas trouvé numéro ' . $id
            );
        }

        return $this->render('piece/show.html.twig', [
            'piece' => $piece,
            'parts' => $parts,
        ]);
    }
    #[Route('/piece/edit/{id}', name: 'piece_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Piece $piece): Response
    {
        $form = $this->createForm(PieceFormType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('editedpiece', 'La Piece a été modifiée avec succès');

            return $this->redirectToRoute('pieces_display');
        }

        return $this->render('piece/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/piece/delete/{id}', name: 'piece_delete', methods: ['GET', 'POST'])]
    public function deletepiece(EntityManagerInterface $entityManager, int $id): Response
    {
        $piece = $entityManager->getRepository(Piece::class)->find($id);
        if (!$piece) {
            throw $this->createNotFoundException(
                'Y a pas de categorie avec cet id :' . $id
            );
        }
        $entityManager->remove($piece);
        $entityManager->flush();
        $this->addFlash('deletedpiece', 'La piece a été supprimée avec succès');
        return $this->redirectToRoute('pieces_display');
    }
    #[Route('/piece/list', name: 'pieces_display', methods: ['GET', 'POST'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $pieces = $entityManager->getRepository(Piece::class)->findAll();
        $parts = [];
        $subcategory = [];
        foreach ($pieces as $piece) {
            $parts[$piece->getId()] = $piece->getParts()->toArray();
            $subcategory[$piece->getId()] = $piece->getSubCategory();
        }
        return $this->render('piece/list.html.twig', [
            'pieces' => $pieces,
            'parts' => $parts,
            'subcategory' => $subcategory,
        ]);
}
}