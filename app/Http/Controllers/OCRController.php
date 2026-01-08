<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OCRController extends Controller
{
    public function extract(Request $request)
    {
        $request->validate([
            'school_id_image' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:10240'
        ]);

        $file = $request->file('school_id_image');

        $response = Http::timeout(60)
            ->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )
            ->post('https://api.ocr.space/parse/image', [
                'apikey'            => config('services.ocr_space.key'),
                'language'          => 'eng',
                'scale'             => 'true',
                'detectOrientation' => 'true',
                'OCREngine'         => '2'
            ]);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'OCR service unavailable'
            ]);
        }

        $result = $response->json();

        if (!empty($result['IsErroredOnProcessing'])) {
            return response()->json([
                'success' => false,
                'message' => $result['ErrorMessage'][0] ?? 'OCR failed'
            ]);
        }

        $text = $result['ParsedResults'][0]['ParsedText'] ?? '';

        $fields = $this->extractFields($text);

        return response()->json([
            'success'   => true,
            'full_name' => $fields['full_name'],
            'school_id' => $fields['school_id'],
            'raw_text'  => $text // keep for debugging
        ]);
    }

    /* ---------------- FIELD EXTRACTION (FIXED) ---------------- */

private function extractFields($text)
{
    // Split text into lines and trim
    $lines = array_map('trim', explode("\n", $text));
    $lines = array_filter($lines, fn($line) => !empty($line));

    $fullName = 'Not Detected';
    $schoolId = 'Not Detected';

    // Find school ID
    foreach ($lines as $line) {
        if (preg_match('/ID\s*No\.?\s*[:\-]?\s*(\d{6,})/i', $line, $idMatch)) {
            $schoolId = $idMatch[1];
            break;
        }
    }

    // Find name (line before BS course)
    for ($i = 1; $i < count($lines); $i++) {
        if (preg_match('/^BS[A-Z]{2,}$/i', $lines[$i])) { // matches BSIS, BSIT, etc.
            $candidate = trim($lines[$i - 1]);

            // Validate loosely: at least 2 words, letters, maybe middle initial
            if (preg_match('/^[A-Z][A-Z]+\s+[A-Z][A-Z]+(\s+[A-Z]\.)?\s*[A-Z]*$/i', $candidate)) {
                $fullName = $candidate;
            } else {
                // fallback: just take the line above course
                $fullName = $candidate;
            }
            break;
        }
    }

    return [
        'full_name' => $fullName,
        'school_id' => $schoolId,
    ];
}

}
