<?php

require_once('inc/Request.php');
require_once('inc/Db.php');
require_once('inc/HTTPCodes.php');

$method = 0;
$data = null;
$url = '';

function exitBadRequest() {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit;
}

function exitNotFound() {
    header("HTTP/1.0 404 Not Found", true, 404);
    exit;
}

switch($_SERVER['REQUEST_METHOD']) {
    case 'PUT': {
        $method = Request::METHOD_PUT;
        break;
    }
    case 'GET': {
        $method = Request::METHOD_GET;
        break;
    }
    case 'POST': {
        $method = Request::METHOD_POST;
        break;
    }
    case 'DELETE': { 
        $method = Request::METHOD_DELETE;
        break;
    }
}

switch($method) {
    case Request::METHOD_GET:
        break;
    default:
        $data = file_get_contents('php://input'); 
        break;
}

$args = explode('/', $_SERVER['REQUEST_URI']);
if (count($args) < 3) {
    exitBadRequest();
}

if ($args[1] != 'api') {
    exitBadRequest();
}

$prefix = $args[2];

Db::connect();
$q = Db::select("users", array('prefix' => $prefix));
$user = Db::row($q);

if (!$user) {
    exitBadRequest();
}

if (count($args) > 3) {
    unset($args[0]);
    unset($args[1]);
    unset($args[2]);
    $url = implode('/', $args);
}

$q = Db::select('api', array('path' => $url, 'user_id' => $user['id'], 'request_method' => $method));
$api = Db::row($q);
if (!$api) {
    exitNotFound();
}

Db::insert('log', array('user_id' => $user['id'], 'api_id' => $api['id'], 'request_data' => $data));

$httpCodes = new HTTPCodes();
$code = $api['response_code'];

header('HTTP/1.0 ' . $code . ' ' . $httpCodes->getDescriptionForCode($code), true, $code);
header('Content-type: ' . $api['content_type']);

echo $api['data'];
