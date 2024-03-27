<?php

namespace App\Models;

/**
 * Class AuthController.
 *
 * @author  Bruno Angos <brunoangos@gmail.com>
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="CalendarType",
 *     title="CalendarType",
 *     description="Modelo de tipo de calendário",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID do tipo de calendário"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do tipo de calendário"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descrição do tipo de calendário"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         format="int64",
 *         description="ID do usuário proprietário do tipo de calendário"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de criação do tipo de calendário"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Data de atualização do tipo de calendário"
 *     )
 * )
 */
class CalendarType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'calendar_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    /**
     * Define o relacionamento com o usuário proprietário do tipo de calendário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define o relacionamento com os calendários associados ao tipo de calendário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calendar(): HasMany
    {
        return $this->hasMany(Calendar::class);
    }
}
