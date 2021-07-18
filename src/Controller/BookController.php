<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/{field_name?}", name="book_index", methods={"GET"})
     */
    public function index(?string $field_name,BookRepository $bookRepository): Response
    {	if (is_null($field_name))
    	{
			return $this->render('book/index.html.twig', [
				'books' => $bookRepository->findAll()
			]);
		}
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findBy(array(), [
            	$field_name => "ASC"
			]),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"POST"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
    }

	/**
	 * @Route("/{id}/submit", name="book_submit", methods={"POST"})
	 */
    public function submit(Request $request, ValidatorInterface $validator): Response
	{
		$data = $request->request->all();

		$entityManager = $this->getDoctrine()->getManager();
		$book = $entityManager->getRepository(Book::class)->find($data["book"]["id"]);

		if (!$book) {
			throw $this->createNotFoundException(
				'No book found for id '.$data["book"]["id"]
			);
		}
		$book->setName($data["book"]["name"]);
		$book->setDescription($data["book"]["description"]);
		$book->setPublishYear($data["book"]["publish_year"]);
		$book->setCover($data["book"]["cover"]);

		$errors = $validator->validate($book);
		if (count($errors) > 0) {
			return new Response((string) $errors, 400);
		}

		$entityManager->flush();
		return $this->redirectToRoute('book_index', [], Response::HTTP_SEE_OTHER);
	}
}
