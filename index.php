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
	
	$curl = curl_init();
	
	// curl options
	$options = array(
		CURLOPT_URL => 'localhost:5984/verge',
		CURLOPT_POSTFIELDS => json_encode($user),
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_ENCODING => 'utf-8',
		CURLOPT_HEADER => false,
		CURLOPT_AUTOREFERER => true,
	);
	
	curl_setopt_array($curl, $options);
	
	curl_exec($curl);
	curl_close($curl);
	
	$app->set('message', 'Thanks for signing up ' . $app->form('name' . '!'));
	$app->render('home');
});

get('/say/:message', function($app){
	$app->set('message', $app->request('message'));
	$app->render('home');
});