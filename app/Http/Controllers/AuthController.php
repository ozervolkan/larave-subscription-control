<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $token_name = "mukellefcase";

    public function __construct(private UserRepository $userRepository)
    {

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function  register(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string|unique:users,email',
            'password'=> 'required|string|confirmed'
        ]);

        $user = $this->userRepository->create($fields);

        $token = $user->createToken($this->token_name)->plainTextToken;

        $response = [
            'user'=> $user,
            'token'=> $token
        ];

        return JsonResponseHelper::success($response, "Kayıt işlemi başarılı oldu.", 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function login(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'email'=> 'required|string',
            'password'=> 'required|string'
        ]);

        $user = $this->userRepository->show($fields);

        if(!$user || !Hash::check($fields['password'], $user->password) ){
            return JsonResponseHelper::error("Kullanıcı adı yada şifre yanlış.", 401);
        }

        $token = $user->createToken($this->token_name)->plainTextToken;

        $response = [
            'user'=> $user,
            'token'=> $token
        ];

        return JsonResponseHelper::success($response, "Başarıyla giriş yaptınız.", 201);
    }
}
