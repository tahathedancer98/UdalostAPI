<?php

namespace udalost\webapp\middlewares;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use udalost\webapp\utils\Writer;
use udalost\webapp\models\Utilisateur;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class Token{
    function checkToken (Request $rq, Response $rs, callable $next ) {

        // récupérer l'identifiant de cmmde dans la route et le token
        $id = $rq->getAttribute('route')->getArgument( 'id');
        $token = $rq->getQueryParam('token', null);

        // vérifier que le token correspond à la commande
        try {
            Commande::where('id', '=', $id)
                ->where('token', '=',$token)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Writer::json_error($rs, 401, 'Token Invalide');   
        }

        return $next($rq, $rs);
    }
}