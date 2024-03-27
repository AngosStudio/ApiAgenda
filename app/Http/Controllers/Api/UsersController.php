<?php

namespace App\Http\Controllers\Api;

/**
 * Class UsersController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPostRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints relacionados aos usuários"
 * )
 */
class UsersController extends Controller
{

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Listar usuários",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index() {
        $user = User::paginate();

        return Response::api(UserResource::collection($user));
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Criar usuário",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserPostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     )
     * )
     */
    public function store(UserPostRequest $request) {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return Response::api(new UserResource($user), true, 201);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Obter usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário obtido com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     )
     * )
     */
    public function show(string $id) {
        $user = User::findOrFail($id);

        return Response::api(new UserResource($user));
    }

    /**
     * @OA\Patch(
     *     path="/users/{id}",
     *     summary="Atualizar usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserPostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id) {
        $data = $request->all();
        $user = User::findOrFail($id);
        $user->update($data);

        return Response::api(new UserResource($user));
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Deletar usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deletado com sucesso"
     *     )
     * )
     */
    public function destroy(string $id) {
        $user = User::findOrFail($id);
        $user->delete();

        return Response::api();
    }

}
