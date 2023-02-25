<?php
require 'bootstrap.php';
use Carbon\Carbon;
function validateToken($token) {
   
    // Set the public certificate file path
    $cert_file_path = '/Applications/XAMPP/xamppfiles/htdocs/api/dev-jau5d5r7zymxt44k.pem';
    
    // Decode the JWT token and get the signature
    $tokenParts = explode('.', $token);
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
    $jwt_signature = base64_decode(str_replace(['-', '_'], ['+', '/'], $tokenParts[2]));
    
    // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
    $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
    $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);

    // Get the public certificate content
    $cert_content = file_get_contents($cert_file_path);

    // Create a new certificate object
    $cert = openssl_x509_read($cert_content);

    // Verify the JWT signature using the certificate
    $verification_result = openssl_verify($tokenParts[0] . '.' . $tokenParts[1], $jwt_signature, $cert, OPENSSL_ALGO_SHA256);

    // Check the verification result
    if ($verification_result === 1) {
        // JWT signature is valid
        if ($tokenExpired) {
            // Token has expired
            echo "Token has expired";
            return false;
        } else {
            // Token is valid and not expired
            echo "Token is valid and not expired";
            return true;
        }
    } else {
        // JWT signature is invalid
        echo "JWT signature is invalid";
        return false;
    }
}
?>