<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Etudiant;
use App\Form\FormEtudType;
use App\Repository\EtudiantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
    #[Route('/affiche',name:'app_affiche')]
    public function afficher(EtudiantRepository $et):Response
    {
        $list=$et->findAll();
        return $this->render('etudiant/afficher.html.twig',['list'=>$list]);
    }	
    #[Route('/ajouter',name:'etud_app')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):response
    {
        $etu=new Etudiant();
        $form = $this->createForm(FormEtudType::class, $etu);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager(); 
        $em->persist($etu); 
        $em->flush();
        return $this->redirectToRoute('app_affiche');
        }
             return $this->render('etudiant/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[route('/delete/{id}',name:'app_delete')]
    public function delete(EtudiantRepository $er,int $id,EntityManagerInterface $entityManager):Response{
        $etu=$er->find($id);
        $entityManager->remove($etu);
         $entityManager->flush();
        return $this->redirectToRoute('app_affiche');
    
    }
     #[Route('/Update/{id}',name:'etu_update')]
        public function update(ManagerRegistry $doctrine,Request $request,$id,EtudiantRepository $etu):response
        {
            $etud=$etu->find($id);
            $form=$this->createForm(FormEtudType::class,$etud);
            $form->handleRequest($request); 
           if ($form->isSubmitted() ) 
           {
            $em=$doctrine->getManager(); 
            $em->flush();
            return $this->redirectToRoute('affiche_app');
        }
        return $this->render('etudiant/update.html.twig',['form'=>$form->createView()]) ;
        
        }
}
