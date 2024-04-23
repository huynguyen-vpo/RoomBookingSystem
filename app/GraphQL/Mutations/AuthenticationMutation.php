<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;

class AuthenticationMutation
{
    public function login(null $_, array $args)
    {
        $response = $this->getClientSecret($args['username'], $args['password']);

        $auth = new AuthPayload();
        $jsonResponse = json_decode($response->getContent(), true);
        $auth->access_token = $jsonResponse['access_token'];
        $auth->expires_in = $jsonResponse['expires_in'];
        $auth->token_type = $jsonResponse['token_type'];
        return $auth;
    }
    public function grantOauth2Token(User $user)
    {
        $request = Request::create('oauth/token', 'POST', [
            'grant_type' => 'client_credentials',
            'client_id' => env('PASSPORT_CLIENT_ID', "9bddc551-31bb-4ac9-b354-3040875ab0d4"),
            'client_secret' => env('PASSPORT_CLIENT_SECRET', "bVCYMD9AwHeBhG92ybhUgk03lYMCwlKXgfWJ9bf4"),
            // 'username' => $user->email,
            // 'password' => $user->password,
            // 'code' => '',
            // 'response_type' => 'code',
            'scope' => '',
        ], [], [], [
            'HTTP_Accept' => 'application/json',
            "Content-Type"=> "application/x-www-form-urlencoded"
        ]);
        $response = app()->handle($request);
        $decodedResponse = json_decode($response->getContent(), true);
        if($response->getStatusCode() != 200){
            return response()->json(['errors'=> 'Auth failed!!']);
        }
        logger(response()->json($decodedResponse));
        return response()->json($decodedResponse);
    }

    public function getClientSecret(string $email, string $password)
    {
        $user = User::where("email", $email)->first()->makeVisible(['role']);
        if (!empty($user)) {
            if (Hash::check($password, $user->password)) {
                $user->password = $password;
                $clientRepository = app(ClientRepository::class);

                $clients = $clientRepository->forUser($user->id);
                if (! empty($clients) || ! $clients) {
                    $client = $clientRepository->create($user->id, 'client', '', 'users', false, true);
                    return self::grantOauth2Token($user);
                }
                return self::grantOauth2Token($user);
            } else {
                return response()->json([
                    "error" => 'Password not matched',
                ], 404);
            }
        } else {
            return response()->json([
                "error" => 'Not found',
            ], 404);
        }
    }
}

class AuthPayload extends Model{
    protected $fillable = ['access_token', 'refresh_token', 'expires_in','token_type', 'user'];
}
