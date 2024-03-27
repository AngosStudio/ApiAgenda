<?php
/**
 * Class CalendarsController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarPostRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Calendars",
 *     description="Endpoints relacionados aos calendários"
 * )
 */
class CalendarsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/calendars",
     *     summary="Listar calendários",
     *     tags={"Calendars"},
     *     @OA\Parameter(
     *         name="dt_start",
     *         in="query",
     *         description="Data de início",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2024-03-25"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="dt_end",
     *         in="query",
     *         description="Data de fim",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2024-03-31"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de calendários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Calendar")
     *         )
     *     )
     * )
     */
    public function index(Request $request) {
        $calendars = Calendar::with('user')
            ->when($request->dt_start, function($query, $dt_start) {
                return $query->where('dt_start', '>=', $dt_start);
            })
            ->when($request->dt_end, function($query, $dt_end) {
                return $query->where('dt_start', '<=', $dt_end);
            })
        ;

        return Response::api(CalendarResource::collection($calendars->paginate()));
    }

    /**
     * @OA\Post(
     *     path="/calendars",
     *     summary="Criar calendário",
     *     tags={"Calendars"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CalendarPostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Calendário criado com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Calendar"
     *         )
     *     )
     * )
     */
    public function store(CalendarPostRequest $request) {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $calendar = Calendar::create($data);

        return Response::api(new CalendarResource($calendar), true, 201);
    }

    /**
     * @OA\Get(
     *     path="/calendars/{id}",
     *     summary="Obter calendário",
     *     tags={"Calendars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do calendário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calendário obtido com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Calendar"
     *         )
     *     )
     * )
     */
    public function show(string $id) {
        $calendar = Calendar::findOrFail($id);

        return Response::api(new CalendarResource($calendar));
    }

    /**
     * @OA\Patch(
     *     path="/calendars/{id}",
     *     summary="Atualizar calendário",
     *     tags={"Calendars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do calendário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CalendarPostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calendário atualizado com sucesso",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Calendar"
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id) {
        $data = $request->all();
        $calendar = Calendar::findOrFail($id);
        $calendar->update($data);

        return Response::api(new CalendarResource($calendar));
    }

    /**
     * @OA\Delete(
     *     path="/calendars/{id}",
     *     summary="Deletar calendário",
     *     tags={"Calendars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do calendário",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calendário deletado com sucesso"
     *     )
     * )
     */
    public function destroy(string $id) {
        $calendar = Calendar::findOrFail($id);
        $calendar->delete();

        return Response::api();
    }
}
