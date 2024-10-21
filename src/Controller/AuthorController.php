<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

   /* #[Route('/show/{name}', name: 'app_autho_show')]
    public function showAutho(string $name): Response
    {
      
        return $this->render('author/show.html.twig', [
            'variableName' => $name,
        ]);
    }*/
    #[Route('/show/{name}', name: 'app_autho_show')]
    public function showAutho(Request $req): Response
    {
      $n=$req->get('name');
        return $this->render('author/show.html.twig', [
            'variableName' => $n,
        ]);
    }


    #[Route('/button', name: 'list_button')]
    public function listButtons(): Response
    {
        // Tableau statique contenant les noms des boutons
        $buttons = [
            'Victor Hugo',
            'William Shakespeare',
            'Taha Hussein',
        ];
    
        // Rendu de la vue Twig avec la liste des boutons
        return $this->render('author/button.html.twig', [
            'buttons' => $buttons,
        ]);
    }
    
    #[Route('/author/{id}', name: 'app_author_show')]
public function showAuthor(int $id, AuthorRepository $authorRepository): Response
{
    // Récupérer l'auteur à partir du repository en utilisant l'ID
    $author = $authorRepository->find($id);

    // Vérifier si l'auteur existe, sinon afficher une erreur
    if (!$author) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    // Passer les détails de l'auteur à la vue
    return $this->render('author/showAuthor.html.twig', [
        'a' => $author,  // 'a' sera utilisé dans le fichier Twig
    ]);
}

    #[Route('/list', name: 'list_tab')]
    public function listAuthors(AuthorRepository $repo): Response
    {
        $authors=$repo->findAll();
       /* $authors = array(
            array('id' => 1, 'picture' => '/IMG/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
            
            array('id' => 2, 'picture' => '/IMG/Portrait-William-Shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/IMG/41_2023-638375497819278279-927.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
            );*/
        return $this->render('author/list.html.twig', [
            'list' => $authors,
        ]);
    }

    #[Route('/add', name: 'app_author_add')]
    public function addAuthor(Request $request, ManagerRegistry $doctrine): Response
    {
        $author = new Author();
        // Spécifier le libellé pour le bouton "Ajouter"
        $form = $this->createForm(AuthorType::class, $author, [
            'submit_label' => 'Ajouter',
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();
            
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Auteur ajouté avec succès !');
            
            return $this->redirectToRoute('app_author_add'); // Redirige vers la page d'ajout
        }
        
        return $this->render("author/add.html.twig", [
            'adem' => $form->createView(),
        ]);
    }
    
    #[Route('/update/{id}', name: 'app_author_update')]
    public function updateAuthor($id, AuthorRepository $repo, Request $request, ManagerRegistry $doctrine): Response
    {
        $author = $repo->find($id);
        $form = $this->createForm(AuthorType::class, $author, [
            'submit_label' => 'Modifier', // Spécifier le libellé pour le bouton "Modifier"
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() ) {
            $em = $doctrine->getManager();
            $em->flush();
            
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Auteur mis à jour avec succès !');
            
            return $this->redirectToRoute('app_author_update', ['id' => $author->getId()]);
        }
        
        return $this->render("author/add.html.twig", [
            'adem' => $form->createView(),
        ]);
    }
    /*
     #[Route('/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor($id, AuthorRepository $repo, ManagerRegistry $doctrine): Response
    {
        $author = $repo->find($id);
    
        if ($author) {
            $em = $doctrine->getManager();
            $em->remove($author);
            $em->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Auteur supprimé avec succès !');
        }
    
        return $this->redirectToRoute('list_tab'); // Redirige vers la liste des auteurs
    }
    
    */
    #[Route('/delete/{id}', name: 'app_author_delete')]
    public function deleteAuthor(Author $author, ManagerRegistry $doctrine): Response
    {  
        if ($author) {
            $em = $doctrine->getManager();
            $em->remove($author);
            $em->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Auteur supprimé avec succès !');
        }
    
        return $this->redirectToRoute('list_tab'); // Redirige vers la liste des auteurs
    }
    


}