<?php

namespace App\Controller;

use App\Entity\Research;
use App\Form\ResearchType;
use App\Repository\ResearchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/research")
 */
class ResearchController extends AbstractController
{
    /**
     * @Route("/", name="research_main", methods={"GET"})
     */
    public function main(ResearchRepository $researchRepository): Response
    {
        return $this->render('research/main.html.twig', [
            'researches' => $researchRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index", name="research_index", methods={"GET"})
     */
    public function index(ResearchRepository $researchRepository): Response
    {
        return $this->render('research/index.html.twig', [
            'researches' => $researchRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="research_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $research = new Research();
        $form = $this->createForm(ResearchType::class, $research);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($research);
            $entityManager->flush();
            $rid = $research->getId();

            return $this->render('file/new.html.twig', [
                'rid' => $rid,
            ]);
        }

        return $this->render('research/new.html.twig', [
            'research' => $research,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new2", name="research_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $research = new Research();
        $form = $this->createForm(ResearchType::class, $research);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($research);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/{id}", name="research_show", methods={"GET"})
     */
    public function show($id,Research $research): Response
    {
        $questionem= $this->getDoctrine()->getManager();
        $sqlconnect = 'SELECT * FROM questions WHERE rid = :rid and status="Reply" ORDER BY ID';
        $statementqu = $questionem->getConnection()->prepare($sqlconnect);
        $statementqu-> bindValue('rid', $id);
        $statementqu->execute();
        $question = $statementqu->fetchAll();



        return $this->render('research/show.html.twig', [
            'rs' => $research,
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="research_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Research $research): Response
    {
        $form = $this->createForm(ResearchType::class, $research);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('research_index', [
                'id' => $research->getId(),
            ]);
        }

        return $this->render('research/edit.html.twig', [
            'research' => $research,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="research_update", methods={"GET","POST"})
     */
    public function file_update($id, Request $request, Research $research):Response
    {
        $em=$this->getDoctrine()->getManager();
        $sql="UPDATE research SET view=:view WHERE id=:id";
        $statement=$em->getConnection()->prepare($sql);
        $view=$request->request->get('view');
        $statement->bindValue('view', ++$view);
        $statement->bindValue('id', $id);
        $statement->execute();

        return $this->render('research/index.html.twig', [
            'research' => $research,
        ]);
    }

    /**
     * @Route("/{id}", name="research_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Research $research): Response
    {
        if ($this->isCsrfTokenValid('delete'.$research->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($research);
            $entityManager->flush();
        }

        return $this->redirectToRoute('research_index');
    }

}
