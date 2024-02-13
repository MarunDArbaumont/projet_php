<?php
// on lance la session
session_start();

//configuration
$base_url = 'http://localhost/ecv/nanoframework/';
$bdd_host = 'localhost';
$bdd_login = 'root';
$bdd_passwd = 'root';
$bdd_base = 'projet_php';

//on inclue les classes de l'ORM
include('table.class.php');

// Router
if (isset($_GET['action']))
{
	$action = $_GET['action'];
	$title = ucfirst($action);
}elseif(isset($_GET['action']) && isset($_GET['id_message'])){
	$action = $_GET['action'];
	$id_message = $_GET['id_message'];
}
else
{
	$action = 'home';
	$title = 'Home';
}


//initialisation du controller
if(isset($_SESSION['id_user']))
{
	$connected_user = User::getOne($_SESSION['id_user']);
	$_SESSION['user'] = serialize($connected_user);
}


// Controller
if ($action === 'home')
{
	$messages = Message::getAll();
}
elseif ($action === 'form_login')
{}
elseif ($action === 'login')
{
	$user = User::login($_POST['login'], $_POST['password']);
	if (empty($user))
		die('Echec de connexion');
	else
		$_SESSION['id_user'] = $user['id'];
}
elseif ($action == 'form_register')
{}
elseif ($action == 'register')
{
	// creer un user en BDD avec le login et password fourni
	$user = New User();
	$user->login = $_POST['login'];
	$user->passwd = $_POST['password'];
	$user->save();
}
elseif ($action === 'logout')
{
	session_destroy();
}
elseif($action === 'post_message')
{
	// creer un message en BDD avec le texte fourni
	$user = New Message();
	$user->author_id = $_SESSION['id_user'];
	$user->message = $_POST['message'];
	$user->author_name = $connected_user->login;
	$user->save();
}
elseif($action === 'form_modify_message')
{}
elseif($action === 'modify_message')
{
    $messageId = $_POST['message_id'];

    // Récupérer le message à modifier depuis la base de données
    $existingMessage = Message::find($messageId);

    // Mettre à jour le message en BDD avec le nouveau texte
    if ($existingMessage) {
        $existingMessage->message = $_POST['message'];
        $existingMessage->save();
    }else{

    }
}
// Vue
include('template.php');