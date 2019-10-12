<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\NewAccFormType;
use Symfony\Component\Form\FormError;

class NewAccController extends AbstractController
{
    /**
     * @Route("/newAcc", name="newAcc")
     */
    public function newAcc(Request $request)
    {
        $User = new User();
        $NewAccForm = $this->createForm(NewAccFormType::class, $User, [
            'method' => 'POST',
        ]);

        $NewAccForm->handleRequest($request);
        if ($NewAccForm->isSubmitted() && $NewAccForm->isValid()) {

            // Get data from POST
            $login = $User->getLogin();
            $password = $User->getPassword();
            $name = $User->getName();
            $lastName = $User->getLastname();
            $email = $User->getEmail();
            $phone = $User->getPhone();

            // Get data from db
            $user = $this->getDoctrine()->getRepository(User::class)->findBy([
                'login' => $login,
            ]);

            // Check that user exist
            if($user){
                $NewAccForm->get('login')->addError(new FormError('W naszej bazie istnieje już użytkownik o tym logine'));
            }else{

                // Add user to db
                $em = $this->getDoctrine()->getManager();

                $addUser = new User();
                $addUser->setLogin($login);
                $addUser->setPassword(password_hash($password, PASSWORD_DEFAULT));
                $addUser->setName($name);
                $addUser->setLastname($lastName);
                $addUser->setEmail($email);
                $addUser->setPhone($phone);
                $em->persist($addUser);

                $em->flush();
                
                // Redirect to info page
                // return $this->render('account/send.html.twig');
            }
        }

        return $this->render('account/newAcc.html.twig', [
            'controller_name' => 'NewAccController',
            'NewAccForm' => $NewAccForm->createView()
        ]);
    }
}
