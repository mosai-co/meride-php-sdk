<?php
/**
 * HTML content generator for Meride and useful stuff.
 * 
 * @category Meride
 * @package  Meride\Web
 * @author   Toppi Giovanni Manuel @MerideDevTeam <giovanni.toppi@mosai.co>
 * @license  prorietary https://www.meride.tv
 * @link     https://www.meride.tv/docs
 * @todo     Write better documentation about the parameters to pass to the various methods
 */
namespace Meride\Web;
/**
 * Generate HTML content for and embed and gets useful to integrate Meride
 */
class Embed
{
    /**
     * Gives back an URL that SHOULD be the one to use inside Meride as the main javascript.
     * However is always better to rely on the one that is visible inside Meride's CMS
     * @param string $clientID the ID of the client
     * @param string $version Meride version to use (default v2)
     * @return string
     */
    public static function presumeBaseURL($clientID, $version = 'v2')
    {
        return $version === 'v1' ? 'https://media'.$clientID.'-meride-tv.akamaized.net' : 'https://data.meride.tv';
    }
    /**
     * Gives back an URL that SHOULD be the one to use as the base URL of the iframe.
     * However is always better to rely on the one that is visible inside Meride's CMS
     * @param string $clientID the ID of the embed
     * @param string $version Meride version to use (default v2)
     * @return string
     */
    public static function presumeBaseIframeURL($clientID, $version = 'v2')
    {
        return $version === 'v1' ? 'https://media'.$clientID.'-meride-tv.akamaized.net/proxy/iframe.php' : 'https://data.meride.tv/proxy/iframe.php';
    }
    /**
     * Gives back an URL that SHOULD be the one to use as iframe for the embed.
     * However is always better to rely on the one that is visible inside Meride's CMS
     * @param array $params an array composed of clientID, embedID and bulkLabel
     * @param string $version Meride version to use (default v2)
     * @return string
     */
    public static function presumeIframeURL($params, $version = 'v2')
    {
        $url = $version === 'v1' ? 'https://media'.$params['clientID'].'-meride-tv.akamaized.net/proxy/iframe.php/'.$params['embedID'].'/'.$params['clientID'] : 'https://data.meride.tv/proxy/iframe.php/'.$params['embedID'].'/'.$params['clientID'];
        if (!empty($params['bulkLabel']))
        {
            $url .= '/'.$params['bulkLabel'];
        }
        return $url;
    }
    /**
     * Gives back an URL that SHOULD be the one to use as main javascript file for Meride.
     * However is always better to rely on the one that is visible inside Meride's CMS
     * @param string $clientID the ID of the embed
     * @param string $version Meride version to use (default v2)
     * @return string
     */
    public static function presumeScriptURL($clientID, $version = 'v2')
    {
        return $version === 'v1' ? 'https://media'.$clientID.'-meride-tv.akamaized.net/scripts/latest/embed.js' : 'https://data.meride.tv/scripts/latest/embed.js';
    }
    /**
     * Gives back the HTML code of Meride's iframe
     * @param array $params an array composed of baseURL, clientID, embedID, width, height and bulkLabel. All parameters must be strings.
     * @return string
     */
    public static function iframe($params)
    {
        $client = empty($params['clientID']) ? '' : $params['clientID'];
        $baseURL = empty($params['baseURL']) ? self::presumeBaseIframeURL($client) : $params['baseURL'];
        if (substr($baseURL, -1) != '/')
        {
            $baseURL .= '/';
        }
        $id = empty($params['embedID']) ? '' : $params['embedID'];
        $bulkLabel = empty($params['bulkLabel']) ? '' : $params['bulkLabel'];
        $width = empty($params['width']) ? '540' : $params['width'];
        $height = empty($params['height']) ? '302' : $params['height'];
        $url = $baseURL.$id.'/'.$client;
        if (!empty($bulkLabel))
        {
            $url .= '/'.$bulkLabel;
        }
        return '<iframe src="'.$url.'" width="'.$width.'" height="'.$height.'" scrolling="auto" frameborder="0" allowfullscreen><p></p><p>Il tuo browser non supporta il tag iframe</p></iframe>';
    }
    /**
     * Gives back the HTML code of Meride's div
     * @param array $params an array composed of scriptURL, clientID, nfs, embedID, width, height and bulkLabel. All parameters must be strings.
     * @return string
     */
    public static function divOnly($params)
    {
        $client = empty($params['clientID']) ? '' : $params['clientID'];
        $nfs = empty($params['nfs']) ? $client : $params['nfs'];
        $id = empty($params['embedID']) ? '' : $params['embedID'];
        $bulkLabel = empty($params['bulkLabel']) ? '' : $params['bulkLabel'];
        $autoPlay = empty($params['autoPlay']) ? '' : $params['autoPlay'];
        $responsive = empty($params['responsive']) ? '' : $params['responsive'];
        $width = empty($params['width']) ? '540' : $params['width'];
        $height = empty($params['height']) ? '302' : $params['height'];
        $bulkLabelCode = empty($bulkLabel) ? '' : ' data-bulk-label="'.$bulkLabel.'"';
        $autoPlayCode = empty($autoPlay) ? '' : ' data-autoplay="'.$autoPlay.'"';
        $responsiveCode = empty($responsive) ? '' : ' data-responsive="'.$responsive.'"';
        $other_datas = $bulkLabelCode.$autoPlayCode.$responsiveCode;
        return '<div class="meride-video-container" data-embed="'.$id.'" data-customer="'.$client.'" data-nfs="'.$nfs.'" data-width="'.$width.'" data-height="'.$height.'"'.$other_datas.'></div>';
    }
    /**
     * Gives back the HTML code of Meride's div with script included do it will launch the player when included in the page
     * @param array $params an array composed of scriptURL, clientID, nfs, embedID, width, height and bulkLabel. All parameters must be strings.
     * @return string
     */
    public static function div($params)
    {
        $client = empty($params['clientID']) ? '' : $params['clientID'];
        $nfs = empty($params['nfs']) ? $client : $params['nfs'];
        $scriptURL = empty($params['scriptURL']) ? self::presumeScriptURL($client) : $params['scriptURL'];
        return self::divOnly($params).'<script src="'.$scriptURL.'"></script>';
    }
    /**
     * Gives back the HTML code of Meride for AMP implementation
     * @param array $params an array composed of baseURL, clientID, embedID, width, height, bulkLabel, imageFillURL and imageFillPlaceholder. All parameters must be strings.
     * @return string
     */
    public static function ampiframe($params)
    {
        $client = empty($params['clientID']) ? '' : $params['clientID'];
        $baseURL = empty($params['baseURL']) ? self::presumeBaseIframeURL($client) : $params['baseURL'];
        if (substr($baseURL, -1) != '/')
        {
            $baseURL .= '/';
        }
        $id = empty($params['embedID']) ? '' : $params['embedID'];
        $bulkLabel = empty($params['bulkLabel']) ? '' : $params['bulkLabel'];
        $imageFillURL = empty($params['imageFillURL']) ? '' : $params['imageFillURL'];
        $imageFillPlaceholder = empty($params['imageFillPlaceholder']) ? '' : $params['imageFillPlaceholder'];
        $width = empty($params['width']) ? '540' : $params['width'];
        $height = empty($params['height']) ? '302' : $params['height'];
        $url = $baseURL.$id.'/'.$client;
        if (!empty($bulkLabel))
        {
            $url .= '/'.$bulkLabel;
        }
        $imageFillCode = '';
        if (!empty($imageFillURL))
        {
            $imageFillCode = '<amp-img layout="fill" src="'.$imageFillURL.'" placeholder="'.$imageFillPlaceholder.'"></amp-img>';
        }
        return '<amp-iframe width="'.$width.'" height="'.$height.'" sandbox="allow-scripts allow-popups allow-same-origin" allowfullscreen frameborder="0" src="'.$url.'">'.$imageFillCode.'</amp-iframe>';
    }
    /**
     * Gives back the HTML code of Meride for Instant Article implementation
     * @param array $params an array composed of baseURL, clientID, embedID, width, height, bulkLabel, imageFillURL and imageFillPlaceholder. All parameters must be strings.
     * @return string
     */
    public static function instantarticle($params)
    {
        $client = empty($params['clientID']) ? '' : $params['clientID'];
        $baseURL = empty($params['baseURL']) ? self::presumeBaseIframeURL($client) : $params['baseURL'];
        if (substr($baseURL, -1) != '/')
        {
            $baseURL .= '/';
        }
        $id = empty($params['embedID']) ? '' : $params['embedID'];
        $bulkLabel = empty($params['bulkLabel']) ? '' : $params['bulkLabel'];
        $width = empty($params['width']) ? '540' : $params['width'];
        $height = empty($params['height']) ? '302' : $params['height'];
        $url = $baseURL.$id.'/'.$client;
        if (!empty($bulkLabel))
        {
            $url .= '/'.$bulkLabel;
        }
        return '<figure class="op-interactive"><iframe width="'.$width.'" height="'.$height.'" src="'.$url.'"></iframe></figure>';
    }
}