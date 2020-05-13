<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        session_start();
        $user = null;
        if (isset($_SESSION['user_id'])) {
            $user = [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
            ];
        }
        return $this->twig->render('Home/index.html.twig', [
            'user' => $user
        ]);
    }
}
