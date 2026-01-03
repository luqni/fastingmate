<?php

namespace Tests\Feature;

use App\Models\FastingDebt;
use App\Models\User;
use App\Services\FastingPlanService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FastingPlanTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_generates_schedules_on_mondays_and_thursdays()
    {
        $user = User::factory()->create();
        $debt = FastingDebt::create([
            'user_id' => $user->id,
            'year' => 2024,
            'total_days' => 5,
            'target_finish_date' => Carbon::now()->addWeeks(4),
        ]);

        $service = new FastingPlanService();
        $service->generateSchedule($user, $debt);

        $this->assertDatabaseCount('smart_schedules', 5);
        
        $schedules = \App\Models\SmartSchedule::where('fasting_debt_id', $debt->id)->get();
        foreach ($schedules as $schedule) {
            $dayOfWeek = $schedule->scheduled_date->dayOfWeekIso;
            $this->assertTrue(in_array($dayOfWeek, [1, 4]), "Date {$schedule->scheduled_date} is not Mon or Thu");
            $this->assertEquals('pending', $schedule->status);
        }
    }
}
