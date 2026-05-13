<?php

namespace Modules\Commission\Schedulers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Commission\Jobs\ProcessWeeklyCommissions;
use Modules\Commission\Jobs\ProcessMonthlyCommissions;

class CommissionScheduler
{
    public function __invoke(Schedule $schedule): void
    {
        // Les commissions Fast Start sont payées chaque semaine
        $schedule->job(new ProcessWeeklyCommissions)->weeklyOn(5, '00:00'); // Vendredi

        // La Matrice et le Matching sont mensuels
        $schedule->job(new ProcessMonthlyCommissions)->monthlyOn(1, '01:00'); // 1er du mois
    }
}
