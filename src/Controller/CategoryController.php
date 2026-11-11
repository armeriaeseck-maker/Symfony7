<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/category')]
final class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

$path = $this->getParameter('kernel.project_dir') . '/public/uploads/';

        if ($form->isSubmitted() && $form->isValid()) {
            //$file = $form['image']->getData();
           // dd($file); 
            $file = $form->get('image')->getData();

if ($file) {

    $originalName = pathinfo(
        $file->getClientOriginalName(),
        PATHINFO_FILENAME
    );

    $newName = 'uploads/' . $originalName . '-' . uniqid() . '.' . $file->guessExtension();

    $category->setImage($newName);

    try {
        $file->move($path, $newName);
    } catch (FileException $e) {
        dd($e->getMessage());
    }
}

            $entityManager->persist($category);
            $entityManager->flush();
            //ajout de flash message
            $this->addFlash('success','Catégorie ajoutée avec succès'); 
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success','Mise à jour réussie');
            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }
         $this->addFlash('success','Suppression réussie');
        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
