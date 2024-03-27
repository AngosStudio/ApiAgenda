<?php

namespace App\Http\Requests;

/**
 * Class AuthController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

use App\Enums\CalendarStatusEnum;
use App\Rules\CalendarConflict;
use App\Rules\WeekDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="CalendarPostRequest",
 *     title="Calendar Post Request",
 *     description="Schema de criação de Calendar.",
 *     required={"dt_start", "dt_end", "status", "title", "calendar_type_id", "description"},
 *     @OA\Property(
 *         property="dt_start",
 *         type="string",
 *         format="date-time",
 *         description="Data e hora inicial do Calendar (Formato: YYYY-MM-DD HH:MM:SS)"
 *     ),
 *     @OA\Property(
 *         property="dt_end",
 *         type="string",
 *         format="date-time",
 *         description="Data e hora final do Calendar (Formato: YYYY-MM-DD HH:MM:SS)"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Status do Calendar"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Título do Calendar"
 *     ),
 *     @OA\Property(
 *         property="calendar_type_id",
 *         type="integer",
 *         description="Id do Calendar Type"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descrição do Calendar"
 *     )
 * )
 */
class CalendarPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dt_start' => [
                'required',
                'date_format:Y-m-d H:i:s',
                new CalendarConflict,
                new WeekDay
            ],
            'dt_end' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:dt_start',
                new WeekDay
            ],
            // 'dt_period' => 'required|string',
            'status' => [
                'required',
                Rule::enum(CalendarStatusEnum::class),
            ],
            'title' => 'required|string',
            'calendar_type_id' => 'required|integer',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'dt_end.after' => 'O campo "dt_end" deve ser uma data posterior a "dt_end".',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 400));
    }
}
