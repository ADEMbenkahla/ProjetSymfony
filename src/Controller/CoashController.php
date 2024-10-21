<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Form\CoashType;
use App\Repository\CoachRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoashController extends AbstractController
{
    #[Route('/coash', name: 'app_coash')]
    public function index(): Response
    {
        return $this->render('coash/index.html.twig', [
            'controller_name' => 'CoashController',
        ]);
    }
    #[Route('/addCoach', name: 'app_coach_add')]
public function addCoach(Request $request, ManagerRegistry $doctrine): Response
{
    $coach = new Coach();
    
    $form = $this->createForm(CoashType::class, $coach, [
        'submit_label' => 'Ajouter',
    ]);
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        
        $em->persist($coach);
        $em->flush();
        
        
        $this->addFlash('success', 'Coach ajouté avec succès !');
        
        return $this->redirectToRoute('app_coach_add'); 
    }
    
    return $this->render("author/add.html.twig", [
        'adem' => $form->createView(),
    ]);
}

   
#[Route('/deleteCoach/{id}', name: 'app_coach_delete')]
public function deleteCoach(int $id, ManagerRegistry $doctrine): Response
{  
    $em = $doctrine->getManager();
    $coach = $em->getRepository(Coach::class)->find($id);

    if ($coach) {
        $em->remove($coach);
        $em->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Coach supprimé avec succès !');
    } else {
        // Ajouter un message flash si le coach n'existe pas
        $this->addFlash('error', 'Le coach n\'existe pas ou a déjà été supprimé.');
    }

    return $this->redirectToRoute('app_coach_add'); 
}


    /* #[Route('/listCoach', name: 'list_Coach')]
    public function listCoach(CoachRepository $repo): Response
    {
        $coach=$repo->findAll();
 
        return $this->render('author/Coach.html.twig', [
            'list' => $coach,
        ]);
    }*/
}
