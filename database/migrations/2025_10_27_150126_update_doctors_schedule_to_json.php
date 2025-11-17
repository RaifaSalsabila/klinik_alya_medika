<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert existing schedule strings to JSON format
        $doctors = DB::table('doctors')->get();

        foreach ($doctors as $doctor) {
            if ($doctor->schedule) {
                // Parse existing schedule format like "Senin, Rabu, Jumat 08:00-12:00"
                $schedule = $this->parseScheduleString($doctor->schedule);
                DB::table('doctors')->where('id', $doctor->id)->update([
                    'schedule' => json_encode($schedule)
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to string format
        $doctors = DB::table('doctors')->get();

        foreach ($doctors as $doctor) {
            if ($doctor->schedule) {
                $schedule = json_decode($doctor->schedule, true);
                $scheduleString = $this->convertScheduleToString($schedule);
                DB::table('doctors')->where('id', $doctor->id)->update([
                    'schedule' => $scheduleString
                ]);
            }
        }
    }

    private function parseScheduleString($scheduleString)
    {
        // Parse "Senin, Rabu, Jumat 08:00-12:00" to JSON format
        $parts = explode(' ', $scheduleString);
        if (count($parts) < 2) {
            return [];
        }

        $daysPart = implode(' ', array_slice($parts, 0, -1)); // Handle multiple spaces
        $timeRange = end($parts);

        $days = array_map('trim', explode(',', $daysPart));
        $timeParts = explode('-', $timeRange);

        if (count($timeParts) !== 2) {
            return [];
        }

        list($startTime, $endTime) = $timeParts;

        $schedule = [];
        foreach ($days as $day) {
            $day = trim($day);
            if (!empty($day)) {
                $schedule[$day] = [
                    'start' => trim($startTime),
                    'end' => trim($endTime)
                ];
            }
        }

        return $schedule;
    }

    private function convertScheduleToString($schedule)
    {
        if (empty($schedule) || !is_array($schedule)) {
            return '';
        }

        $days = array_keys($schedule);
        $firstDay = reset($schedule);
        $timeRange = $firstDay['start'] . '-' . $firstDay['end'];

        return implode(', ', $days) . ' ' . $timeRange;
    }
};
