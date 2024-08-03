<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\CsvExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $query = Link::where('user_id', auth()->id());

        if ($request->has('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        $links = $query->latest()->paginate(20);

        return view('links.index', compact('links'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1',
        ]);

        $count = $request->input('count');
        $generatedLinks = [];

        for ($i = 0; $i < $count; $i++) {
            do {
                $code = $this->generateLetterCode(6);
            } while (Link::where('code', $code)->exists());

            $link = Link::create([
                'code' => $code,
                'user_id' => auth()->id(),
            ]);

            $generatedLinks[] = $link;
        }

        // Generate CSV
        $csv = $this->generateCsv($generatedLinks);

        // Store CSV
        $fileName = 'links_' . now()->format('Y-m-d_His') . '.csv';
        Storage::put('csv_exports/' . $fileName, $csv);

        // Create CSV export record
        CsvExport::create([
            'user_id' => auth()->id(),
            'file_name' => $fileName,
            'link_count' => $count,
        ]);

        return response()->json([
            'message' => "{$count} links generated successfully",
            'download_url' => route('links.download-csv', ['fileName' => $fileName]),
        ]);
    }

    private function generateCsv($links)
    {
        $csv = "Code,URL\n";
        foreach ($links as $link) {
            $csv .= "{$link->code}," . route('qr.redirect', ['code' => $link->code]) . "\n";
        }
        return $csv;
    }

    private function generateLetterCode($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }


    public function updateRedirectUrl(Request $request, Link $link)
    {
        $validator = Validator::make($request->json()->all(), [
            'redirect_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $link->update(['redirect_url' => $request->json('redirect_url')]);

        return response()->json([
            'message' => 'Redirect URL updated successfully',
            'redirect_url' => $link->redirect_url
        ]);
    }


    public function downloadCsv($fileName)
    {
        return Storage::download('csv_exports/' . $fileName);
    }




    public function csvIndex()
    {
        $csvExports = CsvExport::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('csv.index', compact('csvExports'));
    }
}