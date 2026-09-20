<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ProgramController extends Controller
{
    // Static marketing content — these six pages existed as standalone
    // prog-*.html files with bespoke copy predating the CMS; there is no
    // backing model for "programs", so the slug just selects which static
    // Blade view to render inside the shared public layout.
    private const SLUGS = ['rehab', 'education', 'vocational', 'social', 'homecare', 'devices'];

    public function show(string $program)
    {
        abort_unless(in_array($program, self::SLUGS), 404);

        return view("public.programs.{$program}");
    }
}
