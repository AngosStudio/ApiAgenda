<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Calendar;
use App\Models\User;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    private $dtStart = "2024-04-01 17:00:00";
    private $dtEnd = "2024-04-01 18:00:00";

    public function test_calendars_list(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/calendars')
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
            ->get('/calendars/1098098098')
        ;

        $response->assertStatus(404);
    }

    public function test_calendars_creation(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->post('/calendars', [
                "dt_start" => $this->dtStart,
                "dt_end" => $this->dtEnd,
                "dt_period" => "",
                "status" => "aberto",
                "title" => "test_calendars_creation",
                "description" => "Teste de test_calendars_creation",
                "calendar_type_id" => 1,
                "user_id" => $user->id
            ])
        ;

        $response
            ->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.dt_start', $this->dtStart)
            ->assertJsonPath('data.dt_end', $this->dtEnd)
            ->assertJsonPath('data.user_id', $user->id)
        ;
    }

    public function test_calendars_get(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $calendar = Calendar::where('user_id', $user->id)
            ->where('dt_start', $this->dtStart)
            ->where('dt_end', $this->dtEnd)
            ->first()
        ;

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/calendars/' . $calendar->id)
        ;

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.dt_start', $calendar->dt_start)
            ->assertJsonPath('data.dt_end', $calendar->dt_end)
        ;
    }

    public function test_calendars_update(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $calendar = Calendar::where('user_id', $user->id)
            ->where('dt_start', $this->dtStart)
            ->where('dt_end', $this->dtEnd)
            ->first()
        ;

        $calendarTitle = $calendar->title . ' (edited)';

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->patch('/calendars/'.$calendar->id, [
                "title" => $calendarTitle
            ])
        ;

        $response
            ->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', $calendarTitle)
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
            ->patch('/calendars/12189729', [
                "title" => 'calendar_not_found'
            ])
        ;

        $response->assertStatus(404);
    }

    public function test_calendars_delete(): void
    {
        $user = User::where('email', 'testerphpunit@gmail.com')->first();
        $calendarToDelete = Calendar::where('user_id', $user->id)
            ->where('title', 'like', '%test_calendars_creation%')
            ->orderBy('id', 'desc')
            ->first()
        ;

        $response = $this
            ->actingAs($user, 'sanctum')
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->delete('/calendars/'.$calendarToDelete->id)
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
            ->delete('/calendars/12189729')
        ;

        $response->assertStatus(404);
    }
}
