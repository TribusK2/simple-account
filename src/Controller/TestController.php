<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    /**
     * @Route("/user/testIndex", name="index", methods={"GET"})
     */
    public function index()
    { 
        return $this->render('user/testIndex.html', [

        ]);
    }

    /**
     * @Route("/user/testNew", name="new", methods={"GET","POST"})
     */
    public function new()
    {
        return $this->render('user/testNew.html', [

        ]);
    }

    /**
     * @Route("/user/testIndex/{id}", name="user", methods={"GET"})
     */
    public function user($id)
    { 
        return $this->render('user/testUser.html', [

        ]);
    }

    /**
     * @Route("/user/testIndex/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit()
    {
        return $this->render('user/testEdit.html', [

        ]);
    }
}
