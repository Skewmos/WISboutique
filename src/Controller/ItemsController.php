<?php

namespace App\Controller;

use App\Entity\Items;
use App\Form\ItemsType;
use App\Repository\ItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ItemsController extends AbstractController
{   
    /**
     * @Route("/items", name="items_index")
     */
    public function index(ItemsRepository $itemsRepository): Response
    {
        return $this->render('items/index.html.twig', [
            'items' => $itemsRepository->findTheLastThree(),
        ]);
    }

    /**
     * @Route("items/new", name="items_new")
     */
    public function new(Request $request): Response
    {
        $item = new Items();
        $form = $this->createForm(ItemsType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('items_index');
        }

        return $this->render('items/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("items/{id}", name="items_show")
     */
    public function show(Items $item): Response
    {
        return $this->render('items/show.html.twig', [
            'item' => $item,
        ]);
    }

     /**
     * @Route("items/up/{id}", name="items_edit")
     */
    public function edit(Request $request, Items $item): Response
    {
        $form = $this->createForm(ItemsType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('items_index');
        }

        return $this->render('items/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("items/del/{id}", name="items_delete")
     */
    public function delete(Request $request, Items $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('items_index');
    }
}
