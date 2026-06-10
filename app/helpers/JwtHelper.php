<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtHelper {
    private static $secret = "TechStore_JWT_Super_Secret_Key_2026_!@#";
    private static $algorithm = 'HS256';

    /**
     * Create a JWT token using firebase/php-jwt
     * @param array $payload Key-value pairs of token data
     * @param int $expirySeconds Expiration time relative to now in seconds
     * @return string Signed JWT
     */
    public static function encode(array $payload, int $expirySeconds = 86400): string {
        $payload['iat'] = time();
        $payload['exp'] = time() + $expirySeconds;

        return JWT::encode($payload, self::$secret, self::$algorithm);
    }

    /**
     * Decode and validate a JWT token using firebase/php-jwt
     * @param string $token Signed JWT
     * @return array|false Payload array if valid, false if invalid or expired
     */
    public static function decode(string $token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret, self::$algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            return false; // Token expired
        } catch (SignatureInvalidException $e) {
            return false; // Invalid signature
        } catch (\Exception $e) {
            return false; // Any other error (malformed, etc.)
        }
    }
}
