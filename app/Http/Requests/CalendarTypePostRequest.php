<?php

namespace App\Http\Requests;

/**
 * Class AuthController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CalendarTypePostRequest",
 *     title="Calendar Type Post Request",
 *     description="Schema de criação de calendar type.",
 *     required={"name", "description"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do Calendar Type"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descrição do Calendar Type"
 *     )
 * )
 */
class CalendarTypePostRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
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
