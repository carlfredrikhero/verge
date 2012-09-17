<?php
include 'lib/bones.php';

get('/', function($app){
	$app->set('message', 'Welcome back!');
	$app->render('home');
});

get('/signup', function($app){
	$app->render('signup');
});