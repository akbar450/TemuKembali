<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Daftar laporan untuk admin
     */
    public function index(Request $request): View
    {
        $query = Report::with('category')->latest();

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('item_name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%")
                  ->orWhere('reporter_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->input('kategori'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['hilang', 'ditemukan', 'dikembalikan'])) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->paginate(15)->withQueryString();
        $categories = Category::ordered()->get();

        return view('admin.reports.index', [
            'reports' => $reports,
            'categories' => $categories,
            'activeCategory' => $request->input('kategori'),
            'activeStatus' => $request->input('status'),
            'keyword' => $request->input('q'),
        ]);
    }

    /**
     * Detail dan ubah laporan untuk admin
     */
    public function show(string $code): View
    {
        $report = Report::with('category')->where('code', $code)->firstOrFail();
        $categories = Category::ordered()->get();
        $allowedTransitions = $report->allowedStatusTransitions();

        return view('admin.reports.show', compact('report', 'categories', 'allowedTransitions'));
    }

    /**
     * Ubah status laporan (mematuhi aturan PRD Section 6)
     */
    public function updateStatus(Request $request, string $code): RedirectResponse
    {
        $report = Report::where('code', $code)->firstOrFail();

        // Aturan: status 'dikembalikan' adalah status akhir dan tidak dapat diubah lagi
        if ($report->status === 'dikembalikan') {
            return back()->with('error', 'Status sudah dikembalikan dan tidak dapat diubah lagi.');
        }

        $allowed = $report->allowedStatusTransitions();
        $request->validate([
            'status' => ['required', Rule::in($allowed)],
        ], [
            'status.required' => 'Pilih status baru.',
            'status.in' => 'Perubahan status ini tidak diizinkan oleh sistem.',
        ]);

        $newStatus = $request->input('status');
        $report->status = $newStatus;

        if ($newStatus === 'dikembalikan') {
            $report->returned_at = now();
        }

        $report->save();

        $message = $newStatus === 'dikembalikan' ? 'Ditandai dikembalikan.' : 'Status disimpan.';

        return back()->with('sukses', $message);
    }

    /**
     * Ubah isi laporan (perbaikan data / typo)
     */
    public function update(Request $request, string $code): RedirectResponse
    {
        $report = Report::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'min:3', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:10'],
            'secret_note' => ['nullable', 'string', 'max:500'],
            'location' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'before_or_equal:today'],
            'reporter_name' => ['required', 'string', 'max:100'],
            'reporter_role' => ['nullable', 'in:mahasiswa,dosen,staf,tamu'],
            'reporter_id_number' => ['nullable', 'string', 'max:50'],
            'reporter_contact' => ['required', 'string', 'max:100'],
        ], [
            'item_name.required' => 'Nama barang harus diisi.',
            'category_id.required' => 'Pilih kategori barang.',
            'description.required' => 'Ciri-ciri barang harus diisi.',
            'location.required' => 'Lokasi harus diisi.',
            'event_date.required' => 'Tanggal kejadian harus diisi.',
            'reporter_name.required' => 'Nama pelapor harus diisi.',
            'reporter_contact.required' => 'Kontak pelapor harus diisi.',
        ]);

        $report->update($validated);

        return back()->with('sukses', 'Perubahan disimpan.');
    }

    /**
     * Hapus laporan dengan konfirmasi
     */
    public function destroy(string $code): RedirectResponse
    {
        $report = Report::where('code', $code)->firstOrFail();
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('sukses', 'Laporan dihapus.');
    }
}

