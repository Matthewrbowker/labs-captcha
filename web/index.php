<?php
/**
 * MIT License
 *
 * Copyright (c) 2016 tom29739
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Created by PhpStorm.
 * User: Tom D
 * Date: 24/05/2016
 * Time: 21:55
 */

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

$app->before(function (Request $request, Application $app) {
    // check if authenticated

    // get headers
    if( !function_exists('apache_request_headers') ) {
        function apache_request_headers() {
            $arh = array();
            $rx_http = '/\AHTTP_/';
            foreach($_SERVER as $key => $val) {
                if( preg_match($rx_http, $key) ) {
                    $arh_key = preg_replace($rx_http, '', $key);
                    $rx_matches = array();
                    // do some nasty string manipulations to restore the original letter case
                    // this should work in most cases
                    $rx_matches = explode('_', $arh_key);
                    if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                        $arh_key = implode('-', $rx_matches);
                    }
                    $arh[ucfirst(strtolower($arh_key))] = $val;
                }
            }
            return( $arh );
        }
    }

    // check for authorization header
    $requestHeaders = apache_request_headers();
    $authorizationHeader = $requestHeaders['Authorization'];

    // decline request in no auth header
    if ($authorizationHeader == null) {
        header('HTTP/1.0 401 Unauthorized');
        echo "No authorization header sent";
        exit();
    }

    // validate the token
    $token = str_replace('Bearer ', '', $authorizationHeader);
    $secret = '4hV_YUs76fwNxaIW2ttVSlAt10t-tsKGLhpwka_xikAvEQR7TWE_kEEbEkGvj8nJ';
    $client_id = '7EBDeddYjIGL8a1fSkWbJM6cw3TSRWqu';
    $decoded_token = null;
    try {
        $decoded_token = \Auth0\SDK\Auth0JWT::decode($token,$client_id,$secret );
    } catch(\Auth0\SDK\Exception\CoreException $e) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Invalid token";
        exit();
    }
});

// ... definitions
$app->get('/captcha-dev/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app['debug'] = true;
$app->run();