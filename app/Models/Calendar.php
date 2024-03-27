<?php

namespace App\Models;

/**
 * Class AuthController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

use App\Models\CalendarType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 *     schema="Calendar",
 *     title="Calendar",
 *     description="Modelo de calendário",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID do calendário"
 *     ),
 *     @OA\Property(
 *         property="dt_start",
 *         type="string",
 *         format="date-time",
 *         description="Data e hora de início do evento do calendário"
 *     ),
 *     @OA\Property(
 *         property="dt_end",
 *         type="string",
 *         format="date-time",
 *         description="Data e hora de fim do evento do calendário"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Status do calendário"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Título do calendário"
 *     ),
 *     @OA\Property(
 *         property="calendar_type_id",
 *         type="integer",
 *         format="int64",
 *         description="ID do tipo de calendário associado"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descrição do calendário"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID do usuário proprietário do calendário"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de criação do calendário"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de atualização do calendário"
 *     )
 * )
 */
class Calendar extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calendar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dt_start',
        'dt_end',
        'status',
        'title',
        'calendar_type_id',
        'description',
        'user_id',
    ];

    /**
     * Define o relacionamento com o usuário proprietário do calendário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define o relacionamento com o tipo de calendário associado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendarType(): HasOne
    {
        return $this->hasOne(CalendarType::class);
    }
}
