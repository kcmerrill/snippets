<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/* All of our routes for snippet.kcmerrill.com */
$app->get('/', function() use ($app) {
    return include 'views/' . $app['theme'] . '.html';
});

$app->get('/{id}/raw', function($id) use ($app) {
    $fetch = $app['db']->fetch($id, true);
    return $fetch ? new Response($fetch, Response::HTTP_OK, array('content-type' => 'application/json')) : new Response('Unable to find ' . $id, 500);
});

$app->get('/{id}/download', function($id) use ($app) {
    $fetch = $app['db']->fetch($id);
    return $fetch ? new Response($fetch['snippet'], Response::HTTP_OK, array('content-type' => 'application/octet-stream','content-disposition'=>'attachment; filename="'. $id  .'"')) : new Response('Unable to find ' . $id, 500);
});

$app->get('/{id}', function($id) use ($app) {
    $snippet = $app['db']->fetch($id);
    if($snippet) {
        return include 'views/' . $app['theme'] . '.html';
    } else {
        return $app->redirect('/');
    }
});

$app->get('/{id}/{filename}', function($id, $filename) use ($app) {
    $fetch = $app['db']->fetch($id);
    return $fetch ? new Response($fetch['snippet'], Response::HTTP_OK, array('content-type' => 'text/plain')) : new Response('Unable to find ' . $id, 500);
});

$app->post('/save', function(Request $request) use ($app) {
    $data = json_decode($request->getContent(), true);
    $saved = $app['db']->save($data);
    return $saved ? new Response($saved, 200) : new Response('Unable to save', 500);
});
