<?php
include 'lib/bones.php';

get('/', function($app){
	$app->set('message', 'Welcome back!');
	$app->render('home');
});

get('/signup', function($app){
	$app->render('signup');
});

post('/signup', function($app){
	$user = new stdClass();
	$user->type = 'user';
	
	$user->name = $app->form('name');
	$user->email = $app->form('email');
	
	echo json_encode($user);
	
	$app->set('Message', 'Thanks for signing up ' . $app->form('name' . '!'));
	$app->render('home');
});

get('/say/:message', function($app){
	$app->set('message', $app->request('message'));
	$app->render('home');
});