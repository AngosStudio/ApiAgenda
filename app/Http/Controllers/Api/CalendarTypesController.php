<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarTypePostRequest;
use App\Http\Resources\CalendarTypeResource;
use App\Models\CalendarType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class User.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

/**
 * @OA\Tag(
 *     name="Calendar Types",
 *     description="Endpoints relacionados aos tipos de calendário"
 * )
 */
class CalendarTypesController extends Controller
{

    /**
     * @OA\Get(
     *     path="/calendar-types",
     *     summary="Listar tipos de calendário",
     *     tags={"Calendar Types"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tipos de calendário",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CalendarType")
     *         )
     *     )
     * )
     */
    public function index() {
        $calendarTypes = CalendarType::paginate();

        return Response::api(CalendarTypeResource::collection($calendarTypes));
    }

    /**
     * @OA\Post(
     *     path="/calendar-types",
     *     summary="Criar tipo de calendário",
     *     tags={"Calendar Types"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CalendarTypePostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tipo de calendário criado com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CalendarType"
     *         )
     *     )
     * )
     */
    public function store(CalendarTypePostRequest $request) {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $calendarType = CalendarType::create($data);

        return Response::api(new CalendarTypeResource($calendarType), true, 201);
    }

    /**
     * @OA\Get(
     *     path="/calendar-types/{id}",
     *     summary="Obter tipo de calendário",
     *     tags={"Calendar Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do tipo de calendário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de calendário obtido com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CalendarType"
     *         )
     *     )
     * )
     */
    public function show(string $id) {
        $calendarType = CalendarType::findOrFail($id);

        return Response::api(new CalendarTypeResource($calendarType));
    }

    /**
     * @OA\Patch(
     *     path="/calendar-types/{id}",
     *     summary="Atualizar tipo de calendário",
     *     tags={"Calendar Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do tipo de calendário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CalendarTypePostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de calendário atualizado com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CalendarType"
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id) {
        $data = $request->all();
        $calendarType = CalendarType::findOrFail($id);
        $calendarType->update($data);

        return Response::api(new CalendarTypeResource($calendarType));
    }

    /**
     * @OA\Delete(
     *     path="/calendar-types/{id}",
     *     summary="Deletar tipo de calendário",
     *     tags={"Calendar Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do tipo de calendário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tipo de calendário deletado com sucesso"
     *     )
     * )
     */
    public function destroy(string $id) {
        $calendarType = CalendarType::findOrFail($id);
        $calendarType->delete();

        return Response::api();
    }
}
