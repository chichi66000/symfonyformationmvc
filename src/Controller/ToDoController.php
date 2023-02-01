<?php

namespace App\Controller;

use App\data\Todo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/todo')]

class ToDoController extends AbstractController
{
    private array $todolist;

    #[Route('/', name: 'app.todo', methods:"GET")]
    public function index(Request $request): Response
    {
        // on récupère la session en cours
        $session = $request->getSession();
        // recupere todolist dans la session
        $todolist = $session->get('todolist');

        // si todolist n'existe pas , on créer dans la session
        if ($todolist == null) {
            $session->set('todolist', $this-> init());
        }
        
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'ToDoController',
            'todolist' => $session->get('todolist'),
        ]);
    }

    #[Route('/detail/{id}', name: 'app.todo.detail', methods: ['GET'])]
    public function detail (Request $request, int $id) : Response 
    {
        // on récupère la session en cours
        $session = $request->getSession();
        // recupere todolist dans la session
        $todolist = $session->get('todolist');
        $result = null; 
        foreach($todolist as $todo) {
            if ($todo->id == $id) {
                $result = $todo;
            }
        };
        if($result == null) {
            $this->addFlash('warning', 'La todo n\'existe pas');
        }
        
        return $this->render('todo/detail.html.twig', [
            'controller_name' => 'ToDoController',
            'todo' => $result
        ]);
    }


    // Route suppression
    #[Route('/delete/{id}', name: 'app.todo.delete', methods: ['GET'])]
    public function delete (Request $request, int $id) : Response 
    {
        // on récupère la session en cours
        $session = $request->getSession();
        // recupere todolist dans la session
        $todolist = $session->get('todolist');
        $result = null;

        foreach($todolist as $key => $todo) {
            if ($todo->id == $id) {
                $result = $todo;
                unset($todolist[$key]);
                $session->set('todolist', $todolist);
                $this->addFlash('warning', 'La todo a été supprimé');
                return $this->redirectToRoute('app.todo');
            }
            else {
                return $this->render('todo/delete.html.twig', [
                    "result" => $result
                ]);
            }
        }
        
    }

    // modify
    #[Route('/patch/completed/{id}',name: 'app.todo.patch.completed', methods: "GET")]
    public function patchCompleted (Request $request, int $id) : Response 
    {
        // on récupère la session en cours
        $session = $request->getSession();
        // recupere todolist dans la session
        $todolist = $session->get('todolist');
        // changer completed de todo vec id
        foreach($todolist as $key => $todo) { 
            if ($todo->id == $id) {
                $todo->completed = !$todo->completed;
            }
        }

        // reset session
        $session->set('todolist', $todolist);
        return $this->redirect('/todo');
    } 

    private function init ():array 
    {
        return [
            new Todo("Apprendre Symony", "Lorem ipsum lorem ipsum"),
            new Todo("Créer un Controller", "Lorem ipsum lorem ipsum"),
            new Todo("Manipuler des données", "Lorem ipsum lorem ipsum"),
        ];
    }
}
