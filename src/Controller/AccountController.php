<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccountController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/account/{id}", name="account")
     */
    public function account(Request $request, $id)
    {
        // sesion verification
        if(!$this->session->get('login')){
            return $this->redirect('/');
            die;
        }else{
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            if (!$user) {
                throw $this->createNotFoundException(
                    'Brak użytkownika o id: '.$id
                );
            }else{
                $login = $user->getLogin();
                if($login === $this->session->get('login')){
                    return $this->render('account/account.html.twig', [
                        'controller_name' => 'AccountController',
                        'user' => $user,
                    ]);
                }else{
                    return $this->redirect('/');
                    die;
                }
            }
        }
    }
}
