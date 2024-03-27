<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Calendar;
use App\Models\CalendarType;
use App\Models\User;
use Tests\TestCase;

class CalendarTypeTest extends TestCase
{
    public function test_calendars_list(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/calendar-types')
        ;

        $response->assertStatus(200);
    }

    public function test_calendars_get_calendar_not_found(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/calendar-types/1098098098')
        ;

        $response->assertStatus(404);
    }

    public function test_calendars_creation(): void
    {
        $typeName = "Tipo de Evento";
        $typeDescription = "Descrição do Tipo de Evento";

        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->post('/calendar-types', [
                "name" => $typeName,
                "description" => $typeDescription,
                "user_id" => $user->id
            ])
        ;

        $response
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', $typeName)
            ->assertJsonPath('data.description', $typeDescription)
            ->assertJsonPath('data.user_id', $user->id)
        ;
    }

    public function test_calendars_get(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $calendar = CalendarType::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first()
        ;

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/calendar-types/' . $calendar->id)
        ;

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', $calendar->name)
            ->assertJsonPath('data.description', $calendar->description)
        ;
    }

    public function test_calendars_update(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $calendarType = CalendarType::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first()
        ;

        $calendarTypeTitle = $calendarType->name . ' (edited)';

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->patch('/calendar-types/'.$calendarType->id, [
                "name" => $calendarTypeTitle
            ])
        ;

        $response
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', $calendarTypeTitle)
        ;
    }

    public function test_calendars_update_calendar_not_found(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->patch('/calendar-types/12189729', [
                "name" => 'calendar_not_found'
            ])
        ;

        $response->assertStatus(404);
    }

    public function test_calendars_delete(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $calendarToDelete = CalendarType::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first()
        ;

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->delete('/calendar-types/'.$calendarToDelete->id)
        ;

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
        ;
    }

    public function test_calendars_delete_calendar_not_found(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->delete('/calendar-types/12189729')
        ;

        $response->assertStatus(404);
    }
}
