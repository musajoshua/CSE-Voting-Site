<?php
// Routes

$app->get('/getusers', function ($request, $response) {
		$handler = new IOhandler;
        $sth = $handler->getAll('chatty');
        return $this->response->withJson($sth);
    });





