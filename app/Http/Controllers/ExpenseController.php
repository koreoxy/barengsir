<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->input('category');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Expense::orderBy('expense_date', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        $expenses = $query->paginate(15)->withQueryString();

        return view('expense.index', compact('expenses', 'category', 'startDate', 'endDate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['Operasional', 'Gaji Karyawan', 'Sewa Gedung', 'Utilitas (Listrik/Air)', 'Inventaris/Alat', 'Lain-lain'];
        return view('expense.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        Expense::create($validated);

        return redirect()->route('expense.index')
            ->with('success', 'Catatan pengeluaran berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $categories = ['Operasional', 'Gaji Karyawan', 'Sewa Gedung', 'Utilitas (Listrik/Air)', 'Inventaris/Alat', 'Lain-lain'];
        return view('expense.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expense.index')
            ->with('success', 'Catatan pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expense.index')
            ->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }
}
