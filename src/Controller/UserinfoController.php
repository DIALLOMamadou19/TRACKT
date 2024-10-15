<?php

namespace App\Controller;

use App\Entity\Userinfo;
use App\Form\UserinfoType;
use App\Repository\UserinfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/userinfo")
 */
class UserinfoController extends AbstractController
{
    /**
     * @Route("/", name="userinfo_index", methods={"GET"})
     */
    public function index(UserinfoRepository $userinfoRepository): Response
    {
        return $this->render('userinfo/index.html.twig', [
            'userinfos' => $userinfoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="userinfo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userinfo = new Userinfo();
        $form = $this->createForm(UserinfoType::class, $userinfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userinfo);
            $entityManager->flush();

            return $this->redirectToRoute('userpanel_index');
        }

        return $this->render('userinfo/new.html.twig', [
            'userinfo' => $userinfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="userinfo_show", methods={"GET"})
     */
    public function show(Userinfo $userinfo): Response
    {
        return $this->render('userinfo/show.html.twig', [
            'userinfo' => $userinfo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="userinfo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Userinfo $userinfo): Response
    {
        $form = $this->createForm(UserinfoType::class, $userinfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('userpanel_index', [
                'id' => $userinfo->getId(),
            ]);
        }

        return $this->render('userinfo/edit.html.twig', [
            'userinfo' => $userinfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="userinfo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Userinfo $userinfo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userinfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userinfo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('userpanel_index');
    }
}
