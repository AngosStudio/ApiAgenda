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
 *     schema="UserPostRequest",
 *     title="User Post Request",
 *     description="Schema de criação de usuário.",
 *     required={"name", "email", "password"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do usuário"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="E-mail do usuário"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="Senha do usuário (mínimo de 8 caracteres)",
 *         example="12345678"
 *     )
 * )
 */
class UserPostRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => auth()->user() ? 'nullable|min:8' : 'required|min:8',
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
