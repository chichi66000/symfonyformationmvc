<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Acteur;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/film')]
class FilmController extends AbstractController
{
    #[Route('/', name: 'app_film_index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_film_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FilmRepository $filmRepository , EntityManagerInterface $manager): Response
    {
        $film = new Film();
        // crée formulaire
        $form = $this->createForm(FilmType::class, $film);
        // récupere le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // try catch pour éviter les pieges venant du navigateur (malfaisant)
            try {
                // récuperer les donnee dans le formulaire
                $data = $form->getData();
                
                // on parcours la liste des acteurs
                foreach ($data->getActeurs() as $item) {
                    // instancier puis ajouter les valeur, puis enregistrer dans Entity Film
                    $acteur = new Acteur();
                    $acteur->setPrenom($item->getPrenom());
                    $acteur->setNom($item->getNom());
                    $film->addActeur($acteur);
                }

                // on parcours la liste des genre
                foreach ($data->getGenres() as $item) {
                    $genre = new Genre();
                    $genre->setType($item->getType());
                    $film->addGenre($genre);
                }

                $filmRepository->save($film, true);
                return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);

            }
            catch (\Exception $e) {
                $this->addFlash('error', 'Le formulaire pas valide');
            }

        }

        return $this->render('film/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_show', methods: ['GET'])]
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_film_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Film $film, FilmRepository $filmRepository): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filmRepository->save($film, true);

            return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_delete', methods: ['POST'])]
    public function delete(Request $request, Film $film, FilmRepository $filmRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $filmRepository->remove($film, true);
        }

        return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
    }
}
