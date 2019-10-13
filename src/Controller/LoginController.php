<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormError;

class LoginController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(Request $request)
    {
        $this->session->remove('login');

        $loginData = new User();
    
        $LoginForm = $this->createForm(LoginFormType::class, $loginData, [
            'method' => 'POST',
        ]);
        
        $LoginForm->handleRequest($request);
        if ($LoginForm->isSubmitted() && $LoginForm->isValid()) {
            // Get data from POST
            $login = $loginData->getLogin();
            $password = $loginData->getPassword();

            // Get data from db
            $user = $this->getDoctrine()->getRepository(User::class)->findBy([
                'login' => $login,
            ]);

            // User verivication
            if($user){
                $id = $user[0]->getId();
                $dbPassword = $user[0]->getPassword();

                // Password verivication
                if (password_verify($password, $dbPassword)){
                    // Add session values
                    $this->session->set('login', $login);

                    return $this->redirect('/account/'.$id);
                }else{
                    $LoginForm->get('password')->addError(new FormError('Hasło niepoprawne'));
                } 
            }else{
                $LoginForm->get('login')->addError(new FormError('Nieprawidłowy login'));
            }
            
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'LoginController',
            'LoginForm' => $LoginForm->createView()
        ]);
    }
}
