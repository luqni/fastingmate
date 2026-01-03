<?php

namespace Tests\Feature;

use App\Models\MenstrualCycle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MenstrualCycleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_updates_debt_when_cycle_ends()
    {
        $user = User::factory()->create();
        
        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2024, 1, 7);

        $cycle = MenstrualCycle::create([
            'user_id' => $user->id,
            'start_date' => $start,
            'end_date' => $end,
        ]);

        $this->assertDatabaseHas('fasting_debts', [
            'user_id' => $user->id,
            'year' => 2024,
            'total_days' => 7,
        ]);
        
        $this->assertDatabaseHas('menstrual_cycles', [
            'id' => $cycle->id,
            'converted_to_debt' => true,
        ]);
    }
}
