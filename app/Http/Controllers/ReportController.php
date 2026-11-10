<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilan Beranda
     */
    public function home(): View
    {
        $latestReports = Report::with('category')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('publik.home', compact('latestReports', 'categories'));
    }

    /**
     * Daftar Laporan Publik dengan Pencarian dan Filter
     */
    public function index(Request $request): View
    {
        $query = Report::with('category')->latest();

        // Pencarian kata kunci pada nama dan deskripsi
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('item_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('category_id', $request->input('kategori'));
        }

        // Filter Status
        if ($request->filled('status') && in_array($request->input('status'), ['hilang', 'ditemukan', 'dikembalikan'])) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('publik.index', [
            'reports' => $reports,
            'categories' => $categories,
            'activeCategory' => $request->input('kategori'),
            'activeStatus' => $request->input('status'),
            'keyword' => $request->input('q'),
        ]);
    }

    /**
     * Form Lapor Barang (Pengunjung)
     */
    public function create(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $defaultType = in_array($request->query('type'), ['lost', 'found']) ? $request->query('type') : 'lost';

        return view('publik.create', compact('categories', 'defaultType'));
    }

    /**
     * Simpan Laporan Baru
     */
    public function store(Request $request): RedirectResponse
    {
        // Pencegahan spam honeypot
        if ($request->filled('telepon_kantor')) {
            return redirect()->route('home')->with('error', 'Permintaan tidak valid.');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:lost,found'],
            'item_name' => ['required', 'string', 'min:3', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:10'],
            'secret_note' => ['nullable', 'string', 'max:500'],
            'location' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'before_or_equal:today'],
            'reporter_name' => ['required', 'string', 'max:100'],
            'reporter_contact' => ['required', 'string', 'max:100'],
        ], [
            'type.required' => 'Pilih jenis laporan.',
            'type.in' => 'Pilihan jenis laporan tidak valid.',
            'item_name.required' => 'Nama barang harus diisi.',
            'item_name.min' => 'Nama barang minimal 3 karakter.',
            'item_name.max' => 'Nama barang maksimal 100 karakter.',
            'category_id.required' => 'Pilih kategori barang.',
            'category_id.exists' => 'Kategori yang dipilih tidak terdaftar.',
            'description.required' => 'Ciri-ciri barang harus diisi.',
            'description.min' => 'Ciri-ciri barang minimal 10 karakter.',
            'secret_note.max' => 'Ciri khusus rahasia maksimal 500 karakter.',
            'location.required' => 'Lokasi kejadian atau temuan harus diisi.',
            'event_date.required' => 'Tanggal kejadian harus diisi.',
            'event_date.before_or_equal' => 'Tanggal tidak boleh setelah hari ini. Pilih hari ini atau sebelumnya.',
            'reporter_name.required' => 'Nama pelapor harus diisi.',
            'reporter_contact.required' => 'Kontak pelapor harus diisi.',
        ]);

        $status = $validated['type'] === 'lost' ? 'hilang' : 'ditemukan';
        $code = Report::generateUniqueCode();
        $claimPin = Report::generateClaimPin();

        $report = Report::create([
            'code' => $code,
            'claim_pin' => $claimPin,
            'type' => $validated['type'],
            'status' => $status,
            'item_name' => $validated['item_name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'secret_note' => $validated['secret_note'] ?? null,
            'location' => $validated['location'],
            'event_date' => $validated['event_date'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_contact' => $validated['reporter_contact'],
        ]);

        return redirect()->route('report.success', $report->code)
            ->with('sukses', 'Laporan terkirim.');
    }

    /**
     * Detail Laporan Publik
     */
    public function show(string $code): View
    {
        $report = Report::with('category')->where('code', $code)->firstOrFail();
        $campusPostLocation = config('app.campus_post_location');

        return view('publik.show', compact('report', 'campusPostLocation'));
    }

    /**
     * Halaman Sukses Setelah Pengiriman Laporan
     */
    public function success(string $code): View
    {
        $report = Report::where('code', $code)->firstOrFail();

        return view('publik.success', compact('report'));
    }
}

