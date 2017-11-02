<?php
namespace App\Services\Auth;
use Firebase\JWT\JWT;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;
use Illuminate\Contracts\Events\Dispatcher;
/**
 * Author:     Rojer
 * Mail:       rojerchen@qq.com
 */

class JwtGuard implements Guard
{

    use GuardHelpers;
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The name of the query string item from the request containing the API token.
     *
     * @var string
     */
    protected $inputKey;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->inputKey = config('auth.jwt.inputKey','token');

    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return;
        }

        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            try{
                $key = config('auth.jwt.publicKey','');
                $sign_type = config('auth.jwt.type','RS256');
                if(stripos($sign_type,'RS') !== false){
                    $str        = chunk_split($key, 64, "\n");
                    $key = "-----BEGIN PUBLIC KEY-----\n$str-----END PUBLIC KEY-----\n";
                }

                $decoded = JWT::decode($token,
                    $key,
                    array($sign_type)
                );
            }catch (\Exception $e){
                return null;
            }


            if (! empty($token)) {

                $user = $this->provider->retrieveByCredentials(
                    [$this->provider->createModel()->getAuthIdentifierName() => $decoded->id]
                );
                if($user){
                    if($user->{$user->getRememberTokenName()}){
                        if($user->getRememberToken() == $decoded->remember_token){
                            return $this->user = $user;
                        }
                    }else{
                        return $this->user = $user;
                    }
                }

            }
        }

        return $this->user = $user;

    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        if ($this->loggedOut) {
            return;
        }

        if (is_null($this->user)) {
            $this->user();
        }

        return is_null($this->user) ? null : $this->user->getAuthIdentifier();
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->header('Authorization', '');
        }

        if ($token == 'null') $token = '';
        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }


        $user = null;

        $token = $credentials[$this->inputKey];

        if (! empty($token)) {

            $key = config('auth.jwt.publicKey','');
            $sign_type = config('auth.jwt.type','RS256');
            if(stripos($sign_type,'RS') !== false){
                $str        = chunk_split($key, 64, "\n");
                $key = "-----BEGIN PUBLIC KEY-----\n$str-----END PUBLIC KEY-----\n";
            }

            $decoded = JWT::decode($token,
                $key,
                array($sign_type)
            );

            if (! empty($token)) {
                $user = $this->provider->retrieveByCredentials(
                    [$this->provider->createModel()->getAuthIdentifierName() => $decoded->id]
                );

                if($user){
                    if($user->{$user->getRememberTokenName()}){
                        if($user->getRememberToken() != $decoded->remember_token){
                            $user = null;
                        }
                    }
                }
            }
        }

        return !is_null($user);
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return string
     */
    public function login(Authenticatable $user){
        $id = $user->getAuthIdentifier();
        if($user->{$user->getRememberTokenName()}){
            $this->ensureRememberTokenIsSet($user);
            $remember_token = $user->getRememberToken();
        }else{
            $remember_token = 0;
        }

        $payload = array(
            "remember_token" => $remember_token,
            "id" => $id,
            "iss" => config('auth.jwt.iss')
        );

        //expire 指定token的生命周期。单位秒 0 标识永久有效
        if(config('auth.jwt.exp',0) != 0){
            $payload['exp'] = time() + config('auth.jwt.exp',0);
        }

        //not before。多少秒之后token才有效。单位秒
        if(config('auth.jwt.nbf',0) != 0){
            $payload['nbf'] = time() + config('auth.jwt.nbf',0);
        }
        $sign_type = config('auth.jwt.type','RS256');
        if(stripos($sign_type,'RS') !== false){
            $key = config('auth.jwt.privateKey','');
            $str        = chunk_split($key, 64, "\n");
            $key = "-----BEGIN RSA PRIVATE KEY-----\n$str-----END RSA PRIVATE KEY-----\n";
        }else{
            $key = config('auth.jwt.publicKey','');
        }

        return JWT::encode($payload,
            $key
            ,$sign_type
        );
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @return string|false
     */
    public function loginUsingId($id)
    {
        if (! is_null($user = $this->provider->retrieveById($id))) {
            return $this->login($user);
        }

        return false;
    }


    /**
     * Create a new "remember me" token for the user if one doesn't already exist.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function ensureRememberTokenIsSet(Authenticatable $user)
    {
        if (empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }
    }

    /**
     * Refresh the "remember me" token for the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function cycleRememberToken(Authenticatable $user)
    {
        $user->setRememberToken($token = Str::random(60));

        $this->provider->updateRememberToken($user, $token);
    }



    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        if (! is_null($this->user)) {
            $this->cycleRememberToken($user);
        }

        if (isset($this->events)) {
            $this->events->dispatch(new Logout($user));
        }

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
        $this->user = null;

        $this->loggedOut = true;
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getDispatcher()
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }
}