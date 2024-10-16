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

    #[Route('/show/{name}', name: 'app_autho_show')]
    public function showAutho(string $name): Response
    {
      
        return $this->render('author/show.html.twig', [
            'variableName' => $name,
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
        $author= new Author();
       $form=$this->createForm(AuthorType:: class,$author);
       $form->handleRequest($request);
     if($form->isSubmitted())  {
        //ajout
        $em=$doctrine->getManager();
        $em->persist($author);
        $em->flush();
       }
       return $this->render("author/add.html.twig",
       ['adem'=>$form->createView()]);
}

#[Route('/update/{id}', name: 'app_author_update')]
    public function updateAuthor( $id,AuthorRepository $repo, Request $request, ManagerRegistry $doctrine): Response
    {
        $author= $repo->find($id);
       $form=$this->createForm(AuthorType:: class,$author);
       $form->handleRequest($request);
     if($form->isSubmitted())  {
        //ajout
        $em=$doctrine->getManager();
        $em->persist($author);
        $em->flush();
       }
       return $this->render("author/add.html.twig",
       ['adem'=>$form->createView()]);
}

}