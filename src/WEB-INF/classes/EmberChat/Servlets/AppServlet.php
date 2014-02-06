<?php

namespace EmberChat\Servlets;

use TechDivision\ServletContainer\Servlets\StaticResourceServlet;
use TechDivision\ServletContainer\Interfaces\Response;
use TechDivision\ServletContainer\Interfaces\Request;

class AppServlet extends StaticResourceServlet
{

    public function doGet(Request $req, Response $res)
    {
        $req->setUri($req->getUri() . 'index.html');

        parent::doGet($req, $res);
    }
}
