<?php

namespace Meride\Web;

class Embed
{
    /**
     * Gives back an URL that SHOULD be the one to use inside Meride as the main javascript.
     * However is always better to rely on the script that is visible inside Meride's CMS
     * @param string $clientID the ID of the client
     * @return void
     */
    public static function presumeScriptURL($clientID)
    {
        return 'https://media'.$clientID.'-meride-tv.akamaized.net';
    }
}