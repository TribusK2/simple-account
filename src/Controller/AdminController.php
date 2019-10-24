<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Admin;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class AdminController extends AbstractController
{
    /**
     * @Route("/newAdmin", name="new_admin", methods={"POST"})
     */
    public function newAdmin(ObjectManager $om, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $admin = new Admin();
        // get data from POST request
        $email = $request->request->get("email");
        $password = $request->request->get("password");

        // set data to send to database
        $admin->setEmail($email);
        $admin->setPassword($passwordEncoder->encodePassword($admin, $password));

        // send data to database
        try{
            $om->persist($admin);
            $om->flush();

            // set response message if send
            $message = "Dodano nowego administratora";

        }catch(UniqueConstraintViolationException $e){
            // set response message if error
            $message = "W naszej bazie istnieje już taki użytkownik";
        }

        // render the result
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setData(['message' => $message]);

        return new Response($response);
    }

    /**
    * @Route("/loginAdmin", name="login_admin", methods={"POST"})
    */
    public function loginAdmin()
    {
       
    }
}
