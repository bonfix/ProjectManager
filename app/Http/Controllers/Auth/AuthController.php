<?php

namespace App\Http\Controllers\Auth;

use Config;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
//use JWTAuth;
use Input;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth as JWT;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
class AuthController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

    use AuthenticatesAndRegistersUsers;//,
    // ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    /* public function __construct() {
         $this->middleware('guest', ['except' => 'getLogout']);
     }*/
    private $JWTAuth;
    private $request;

    public function __construct(JWT $JWTAuth,Request $request)
    {
        $this->JWTAuth = $JWTAuth;
        $this->request = $request;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function postSignup() {
        $done =  false;
        $pass = $this->request->input['password'];
        if($pass==null)
        {
            return response()->json(['message' => 'User ERROR.','token'=>null], 500);
        }
        try {

            $user = new User([
                'name' => $this->request->input('name'),
                'email' => $this->request->input('email'),
                'phone' =>$this->request->input('phone'),
                'password' => bcrypt($this->request->input['password']), //Hash::make($this->request->input['password']),
                'account_type' => $this->request->input('account_type', '1'),
            ]);
            $done = $user->save();

        } catch (Exception $e) {
            return response()->json(['message' => 'User already exists.','token'=>null], HttpResponse::HTTP_CONFLICT);
            // return response()->json(['message' => 'You are not authorized to perform that action'], 401);
        }
        if (!$done) {
            return response()->json(['message' => 'User already exists.','token'=>null], HttpResponse::HTTP_CONFLICT);
        }
        $user['pass'] = $pass;
        $user['token'] = $this->JWTAuth->fromUser($user);
        return response()->json($user);
    }
    public function postLogin() {//Request $request = $this->request
        // grab credentials from the request
        $credentials = $this->getCredentials(); //$this->request->only('email', 'password');
        //$token = null;
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = $this->JWTAuth->attempt($credentials)) {
                return response()->json(['message' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
    public function postLoginOld() {//Request $request = $this->request
        $email = $this->request->input('email');
        $password = $this->request->input('password');

        $user = User::where('email', '=', $email)->first();

        if (!$user)
        {
            return response()->json(['message' => 'Wrong email and/or password.'], 401);
        }

        if (Hash::check($password,$user->password))
        {
            unset($user->password);

            return response()->json(['token' => $this->createToken($user)]);
        }
        else
        {
            return response()->json(['message' => 'Wrong email and/or password'], 401);
        }
    }
//    private function getCredentials()
//    {
//        return Input::only(['email','password']);
//    }

    public function authenticate()
    {
        //$this->authenticate();
    }
    public function createToken() {
        // grab some user
        $user = User::first();

        return $this->JWTAuth->fromUser($user);
    }

    public function getUser() {
// this will set the token on the object
        $this->JWTAuth->parseToken();

// and you can continue to chain methods
        return $this->JWTAuth->parseToken()->authenticate();
    }

    public function getTokenFromRequest() {
        return $this->JWTAuth->getToken();
    }

//Retreiving the Authenticated user from a token
// somewhere in your controller
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = $this->JWTAuth->parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    /**
     * Login with GitHub.
     */
    public function postGithub(Request $request)
    {
        $accessTokenUrl = 'https://github.com/login/oauth/access_token';
        $userApiUrl = 'https://api.github.com/user';

        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'client_secret' => Config::get('app.github_secret'),
            'redirect_uri' => $request->input('redirectUri')
        ];

        $client = new GuzzleHttp\Client();
        $client->setDefaultOption('verify', false);
        // Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->get($accessTokenUrl, ['query' => $params]);

        $accessToken = array();
        parse_str($accessTokenResponse->getBody(), $accessToken);

        $headers = array('User-Agent' => 'Satellizer');

        // Step 2. Retrieve profile information about the current user.
        $userApiResponse = $client->get($userApiUrl, [
            'headers' => $headers,
            'query' => $accessToken
        ]);
        $profile = $userApiResponse->json();

        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization'))
        {
            $user = User::where('github', '=', $profile['id']);

            if ($user->first())
            {
                return response()->json(['message' => 'There is already a GitHub account that belongs to you'], 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) $this->JWTAuth->decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->github = $profile['id'];
            $user->displayName = $user->displayName || $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
        // Step 3b. Create a new user account or return an existing one.
        else
        {
            $user = User::where('github', '=', $profile['id']);

            if ($user->first())
            {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->github = $profile['id'];
            $user->displayName = $profile['name'];
            $user->email= $profile['email'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }


}

/*
Apache users
 * RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
 * Alternatively you can include the token via a query string

http://api.mysite.com/me?token={yourtokenhere}

 * 
 *  */
/*
 If using Laravel 5 (0.5.*) then you have access to 2 included Middlewares:

GetUserFromToken
This will check the header and query string (as explained above) for the presence of a token, and attempts to decode it. The same events are fired, as above.

RefreshToken
This middleware will again try to parse the token from the request, and in turn will refresh the token (thus invalidating the old one) and return it as part of the next response. This essentially yields a single use token flow, which reduces the window of attack if a token is compromised, since it is only valid for the single request.

To use the middlewares you will have to register them in app/Http/Kernel.php under the $routeMiddleware property:

protected $routeMiddleware = [
    ...
    'jwt.auth' => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
    'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
];
 * /
 */