<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\UserManager;

class AuthController extends AbstractController
{

    /**
     * Display login form
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Vérifier les champs reçus
            // Par exemple:
            // email, password existent dans $_POST
            // email est valide
            // password a au moins X caractères

            // 2. Chercher dans la BDD (table user) email correspondant à celui fourni
            $userManager = new UserManager();
            $user = $userManager->selectOneByEmail($_POST['email']);
            if (!$user) {
                // Si on ne récupère pas d'utilisateur -> afficher une erreur
                // TODO: à compléter
                // return $this->render('Auth/login.html.twig', [ ... ]);
            }

            // 3. Si on trouve un utilisateur correspondant, on va devoir
            // vérifier que le password correspond à celui stocké dans la BDD
            // https://www.php.net/manual/en/function.password-verify.php
            $passwordOk = password_verify($_POST['password'], $user['password']);
            if (!$passwordOk) {
                // Si les passwords ne correspondent pas, afficher une erreur
            }

            // 4. Stocker dans la session les informations sur l'utilisateur connecté
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // 5. Rediriger l'utilisateur vers la home page
            // (éventuellement avec message de succès)
            header("Location: /");
        }
        return $this->twig->render('Auth/login.html.twig');
    }

    /**
     * Display login form
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Vérifier les champs reçus
            // Par exemple:
            // email, password et password-confirm existent dans $_POST
            // email est valide
            // password a au moins X caractères
            // password et password-confirm sont identiques

            // 2. "Hacher" le password
            // https://fr.wikipedia.org/wiki/Fonction_de_hachage_cryptographique
            // https://bcrypt-generator.com/
            // https://www.php.net/manual/en/function.password-hash
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // 3. Créer un tableau associatif avec les données à stocker
            $user = [
                'email' => $_POST['email'],
                'password' => $hash,
            ];

            // 4. Instancier le UserManager et appeler sa méthode insert
            // Eventuellement entourer insert d'un try/catch pour gérer l'erreur
            // d'email en doublon
            $userManager = new UserManager();
            $userId = $userManager->insert($user);
            error_log("New user registered with id: $userId");

            // 5. Rediriger l'utilisateur vers la home page
            // (éventuellement avec message de succès)
            header("Location: /");
        }
        return $this->twig->render('Auth/register.html.twig');
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /");
    }
}
