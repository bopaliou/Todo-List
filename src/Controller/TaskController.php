<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(TaskRepository $repository): Response
    {
        $tasks=$repository -> findAll();
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
     /**
     * @Route("/create", name="task_create")
     */
    public function create(Request $request){
        $task=new Task();
        $form=$this->createForm(TaskType::class,$task);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task');
        }
        return $this->render('/task/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}", name="task_edit")
     */
    public function edit(Task $task,Request $request):Response{
        $form=$this->createForm(TaskType::class,$task);
        $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('task');
       }
      
        return $this->render('/task/edit.html.twig',[
            'task' => $task,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function delete(Task $task){
        $em=$this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('task');
    }

}
