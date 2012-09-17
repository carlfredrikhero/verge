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
	$app->set('message', 'Thanks for signing up ' . $app->form('name') . '!');
	$app->render('home');
});

get('/say/:message', function($app){
	$app->set('message', $app->request('message'));
	$app->render('home');
});