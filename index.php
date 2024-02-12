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
if ($action == 'home')
{
	$messages = Message::getAll();
}
elseif ($action == 'form_login')
{}
elseif ($action == 'login')
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
elseif ($action == 'logout')
{
	session_destroy();
}
elseif($action = 'post_message')
{
	// creer un message en BDD avec le texte fourni
	$user = New Message();
	$user->author_id = $_SESSION['id_user'];
	$user->message = $_POST['message'];
	$user->save();
}
elseif($action = 'post_com'){
	$user = New Commentaire();
	$user->author_id = $_SESSION['id_user'];
	$user->commentaire = $_POST['commentaire'];
	$user->id_message =  $_POST['id'];
	$user->save();
}

// Vue
include('template.php');