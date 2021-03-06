<?php

namespace OAuth2\ServerBundle\Storage;

use OAuth2\Storage\ScopeInterface;
use Doctrine\ORM\EntityManager;

class Scope implements ScopeInterface
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Check if the provided scope exists.
     *
     * @param $scope
     * A space-separated string of scopes.
     * @param $client_id
     * The requesting client.
     *
     * @return
     * TRUE if it exists, FALSE otherwise.
     */
    public function scopeExists($scope, $client_id = null)
    {
        // Get Client
        $client = $this->em->getRepository('OAuth2ServerBundle:Client')->find($client_id);

        if (!$client) return FALSE;

        $scopes = explode(' ', $scope);

        foreach($scopes as $scope) {
            if (!in_array($scope, $client->getScopes())) return FALSE;
        }

        return TRUE;
    }

    /**
     * The default scope to use in the event the client
     * does not request one. By returning "false", a
     * request_error is returned by the server to force a
     * scope request by the client. By returning "null",
     * opt out of requiring scopes
     *
     * @return
     * string representation of default scope, null if
     * scopes are not defined, or false to force scope
     * request by the client
     *
     * ex:
     *     'default'
     * ex:
     *     null
     */
    public function getDefaultScope($client_id = null)
    {
        return FALSE;
    }

    /**
     * Gets the description of a given scope key, if
     * available, otherwise the key is returned.
     *
     * @return
     * string description of the scope key.
     */
    public function getDescriptionForScope($scope)
    {
        // Get Scope
        $scopeObject = $this->em->getRepository('OAuth2ServerBundle:Scope')->find($scope);

        if (!$scopeObject) return $scope;

        return $scopeObject->getDescription();
    }
}
