<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCinemaRequest;
use App\Http\Requests\UpdateCinemaRequest;
use App\Models\Cinema;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CinemaController extends Controller
{
    public function index(): View {
        $cinemas = Cinema::latest()->paginate(10);
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function create(): View {
        return view('admin.cinemas.create');
    }

    public function store(StoreCinemaRequest $request): RedirectResponse {
        Cinema::create($request->validated());
        return redirect()
            ->route('admin.cinemas.index')
            ->with('success', 'Bioskop berhasil ditambahkan.');
    }

    public function edit(Cinema $cinema): View {
        return view('admin.cinemas.edit', compact('cinema'));
    }

    public function update(UpdateCinemaRequest $request, Cinema $cinema): RedirectResponse {
        $cinema->update($request->validated());
        return redirect()
            ->route('admin.cinemas.index')
            ->with('success', 'Bioskop berhasil diperbarui.');
    }

    public function destroy(Cinema $cinema): RedirectResponse {
        $this->authorize('delete', $cinema);

        $cinema->delete();
        
        return redirect()
            ->route('admin.cinemas.index')
            ->with('success', 'Bioskop berhasil dihapus.');
    }
}