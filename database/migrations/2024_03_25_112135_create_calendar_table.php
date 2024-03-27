<?php

use App\Enums\CalendarStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();

            $table->dateTime('dt_start');
            $table->dateTime('dt_end');

            // não entendi o que seria esta coluna: Data Prazo
            $table->dateTime('dt_period')->nullable();

            $table->enum('status', array_column(CalendarStatusEnum::cases(), 'name'));
            $table->string('title');
            $table->foreignIdFor(\App\Models\CalendarType::class)->constrained('calendar_type');
            $table->text('description');
            $table->foreignIdFor(\App\Models\User::class)->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar');
    }
};
