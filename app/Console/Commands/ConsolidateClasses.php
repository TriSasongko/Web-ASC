<?php

namespace App\Console\Commands;

use App\Models\Program;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConsolidateClasses extends Command
{
    protected $signature = 'classes:consolidate';

    protected $description = 'Menggabungkan kelas ganda per program menjadi satu kelas kanonik (5 kelas total).';

    public function handle(): int
    {
        foreach (Program::with('classes')->get() as $program) {
            if ($program->classes->count() <= 1) {
                continue;
            }

            $canonical = $program->classes
                ->sortBy(fn ($class) => [-$class->students()->count(), $class->id])
                ->first();

            foreach ($program->classes->where('id', '!=', $canonical->id) as $duplicate) {
                $this->consolidateClass($duplicate, $canonical);
                $this->info("Program {$program->name}: {$duplicate->name} -> {$canonical->name}");
            }
        }

        $this->info('Konsolidasi kelas selesai.');

        return self::SUCCESS;
    }

    private function consolidateClass($duplicate, $canonical): void
    {
        foreach (DB::table('class_student')->where('class_id', $duplicate->id)->get() as $row) {
            $conflict = DB::table('class_student')
                ->where('class_id', $canonical->id)
                ->where('student_id', $row->student_id)
                ->exists();

            if ($conflict) {
                DB::table('class_student')->where('id', $row->id)->delete();
            } else {
                DB::table('class_student')->where('id', $row->id)->update(['class_id' => $canonical->id]);
            }
        }

        DB::table('class_schedules')->where('class_id', $duplicate->id)->update(['class_id' => $canonical->id]);
        DB::table('attendances')->where('class_id', $duplicate->id)->update(['class_id' => $canonical->id]);
        DB::table('developments')->where('class_id', $duplicate->id)->update(['class_id' => $canonical->id]);
        DB::table('class_recommendations')->where('current_class_id', $duplicate->id)->update(['current_class_id' => $canonical->id]);
        DB::table('class_recommendations')->where('recommended_class_id', $duplicate->id)->update(['recommended_class_id' => $canonical->id]);

        $duplicate->delete();
    }
}
