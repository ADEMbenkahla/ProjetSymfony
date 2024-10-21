<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook', name: 'app_book_add')]
    public function addBook(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        // Spécifier le libellé pour le bouton "Ajouter"
        $form = $this->createForm(BookType::class, $book, [
            'submit_label' => 'Ajouter',
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();
            
            // Ajouter un message flash de succès
            $this->addFlash('success', 'livre ajouté avec succès !');
            
            return $this->redirectToRoute('app_book_add'); // Redirige vers la page d'ajout
        }
        
        return $this->render("author/add.html.twig", [
            'adem' => $form->createView(),
        ]);
    }
    
}
