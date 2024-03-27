<?php
namespace App\Http\Controllers\Api;

/**
 * Class AuthController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPostRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints relacionados à autenticação de usuários"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login de usuário",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="example@teste.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="bool", description="Status da solicitação"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", description="Token de acesso")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="bool", example="false", description="Status da solicitação"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="message", type="string", example="Unauthorized")
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return Response::api([
                'token' => $token,
            ]);
        } else {
            return Response::api([
                'message' => 'Unauthorized'
            ], false, 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Registrar novo usuário",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserPostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="bool", description="Status da solicitação"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function register(UserPostRequest $request) {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return Response::api(new UserResource($user));
    }

    /**
     * @OA\Get(
     *     path="/auth/user",
     *     summary="Obter informações do usuário autenticado",
     *     tags={"Authentication"},
     *     security={ {"bearerAuth": {} } },
     *     @OA\Response(
     *         response=200,
     *         description="Informações do usuário",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="bool", description="Status da solicitação"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User")
     *             )
     *         )
     *     )
     * )
     */
    public function user(Request $request) {
        return Response::api($request->user());
    }
}
