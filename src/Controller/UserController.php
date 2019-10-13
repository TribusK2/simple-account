<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new ArrayDenormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $users = $userRepository->findAll();
        $jsonContent = $serializer->serialize($users, 'json');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($jsonContent);

        // $json = $serializer->deserialize($jsonContent, 'App\Entity\User[]', 'json');
// var_dump($jsonContent);
        // return $this->render('user/index.html.twig', [
            // 'users' => json_decode($jsonContent),
            // 'users' => $jsonContent,
        // ]);    
        return new Response($response);
        
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {      
        
        $content = $request->getContent();
        $data = json_decode($content);

        if(!empty($data)){
                        
            // Check if POST data exist
            $reqiredParameters = [$data->login, $data->password, $data->name, $data->lastname, $data->email];
            
            if (in_array(null, $reqiredParameters)) {
                $message = 'Podane dane są błędne lub niekompletne';
            }else{               
                
                // Get data from POST
                $addUser = new User();
                $addUser->setLogin($data->login);
                $addUser->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
                $addUser->setName($data->name);
                $addUser->setLastname($data->lastname);
                $addUser->setEmail($data->email);
                if($data->phone){$addUser->setPhone($data->phone);};           

                // Get data from db
                $user = $this->getDoctrine()->getRepository(User::class)->findBy([
                    'login' => $data->login,
                ]);

                // Check that user exist
                if($user){
                    $message = 'W naszej bazie istnieje już użytkownik o tym logine';
                }else{

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($addUser);
                    $em->flush();

                    $message = "Dane przesłane";
                }
            }
            $response = new JsonResponse();
            $response->setData(['message' => $message]);
        };


        // $user = new User();
        // $form = $this->createForm(UserType::class, $user);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
        //     $entityManager->persist($user);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('user_index');
        // }

        // return $this->render('user/new.html.twig', [
        //     'user' => $user,
        //     'form' => $form->createView(),
        // ]);
        return new Response($response);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, $id)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(), new ArrayDenormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $jsonContent = $serializer->serialize($user, 'json');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($jsonContent);

        // return $this->render('user/show.html.twig', [
        //     'user' => json_decode($jsonContent),
        // ]);
        return new Response($response);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, $id)
    {

        $message = "";
        $content = $request->getContent();
        $data = json_decode($content);

        if(!empty($data)){
                        
            // Check if POST data exist
            $reqiredParameters = [$data->login, $data->password, $data->name, $data->lastname, $data->email];
            
            if (in_array(null, $reqiredParameters)) {
                $message = 'Podane dane są błędne lub niekompletne';
            }else{                       

                // Get data from db
                $user = $this->getDoctrine()->getRepository(User::class)->find($id);

                // Check that user exist
                if(!$user){
                    $message = 'Nie ma w bazie takiego użytkownika';
                }else{                       
                    
                    // Set data from POST to update in database
                    $user->setLogin($data->login);
                    $user->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
                    $user->setName($data->name);
                    $user->setLastname($data->lastname);
                    $user->setEmail($data->email);
                    if($data->phone){$user->setPhone($data->phone);};

                    $em = $this->getDoctrine()->getManager();
                    $em->flush();

                    $message = "Dane zostały zaktualizowane";
                }
            }
            $response = new JsonResponse();
            $response->setData(['message' => $message]);
        };

        // $form = $this->createForm(UserType::class, $user);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
        //     $this->getDoctrine()->getManager()->flush();

        //     return $this->redirectToRoute('user_index');
        // }

        return new Response($response);

        // return $this->render('user/edit.html.twig', [
        //     'user' => $user,
        //     'form' => $form->createView(),
        // ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $message = 'Użytkownik został usunięty z bazy dabych';

        // if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->remove($user);
        //     $entityManager->flush();
        // }
        $response = new JsonResponse();
        $response->setData(['message' => $message]);

        return new Response($response);
        // return $this->redirectToRoute('user_index');
    }
}
