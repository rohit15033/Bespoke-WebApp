<?php

namespace App\Console\Commands;

use App\Models\Appointments;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixAppointmentTimezones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:fix-timezones {--hours=14 : Number of hours to shift forward} {--dry-run : Only show what would be changed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrects appointment timestamps that were shifted due to timezone bugs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');

        $appointments = Appointments::all();

        if ($appointments->isEmpty()) {
            $this->info('No appointments found.');
            return;
        }

        $this->info("Found {$appointments->count()} appointments.");
        
        $count = 0;
        foreach ($appointments as $appointment) {
            $oldAt = $appointment->at;
            $newAt = $oldAt->copy()->addHours($hours);

            $this->line("Appointment #{$appointment->id}: {$oldAt->format('Y-m-d H:i:s')} -> {$newAt->format('Y-m-d H:i:s')}");

            if (!$dryRun) {
                $appointment->at = $newAt;
                
                // Also check rescheduled_to_at
                if ($appointment->rescheduled_to_at) {
                    $oldResched = $appointment->rescheduled_to_at;
                    $newResched = $oldResched->copy()->addHours($hours);
                    $appointment->rescheduled_to_at = $newResched;
                    $this->line("  Rescheduled to: {$oldResched->format('Y-m-d H:i:s')} -> {$newResched->format('Y-m-d H:i:s')}");
                }

                $appointment->save();
            }
            $count++;
        }

        if ($dryRun) {
            $this->info("Dry run complete. {$count} appointments would be updated.");
        } else {
            $this->info("Success! Corrected {$count} appointments by adding {$hours} hours.");
        }
    }
}
