<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengeluaran Operasional') }}
            </h2>
            <a href="{{ route('expense.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Pengeluaran
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-bold uppercase tracking-wider">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Filter Panel -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
                <form action="{{ route('expense.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="category" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                        <select id="category" name="category" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold">
                            <option value="">Semua Kategori</option>
                            <option value="Operasional" {{ $category === 'Operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="Gaji Karyawan" {{ $category === 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="Sewa Gedung" {{ $category === 'Sewa Gedung' ? 'selected' : '' }}>Sewa Gedung</option>
                            <option value="Utilitas (Listrik/Air)" {{ $category === 'Utilitas (Listrik/Air)' ? 'selected' : '' }}>Utilitas (Listrik/Air)</option>
                            <option value="Inventaris/Alat" {{ $category === 'Inventaris/Alat' ? 'selected' : '' }}>Inventaris/Alat</option>
                            <option value="Lain-lain" {{ $category === 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Mulai Tanggal</label>
                        <input id="start_date" name="start_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold" value="{{ $startDate }}" />
                    </div>

                    <div>
                        <label for="end_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        <input id="end_date" name="end_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-xs text-slate-800 focus:outline-none transition-all font-semibold" value="{{ $endDate }}" />
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                            Filter
                        </button>
                        @if($category || $startDate || $endDate)
                            <a href="{{ route('expense.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Expense List Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Daftar Biaya Cabang</h3>
                        <p class="text-xs text-slate-400">Total Pengeluaran Periode Ini: <span class="font-bold text-rose-600">Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</span></p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/70">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Deskripsi/Judul</th>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">Jumlah Biaya</th>
                                <th scope="col" class="px-6 py-4 class-cols text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($expenses as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                    {{ $item->expense_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-800 font-bold">
                                    {{ $item->title }}
                                    @if($item->note)
                                        <p class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $item->note }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide
                                        @if($item->category === 'Gaji Karyawan') bg-amber-50 text-amber-700 border border-amber-100
                                        @elseif($item->category === 'Sewa Gedung') bg-indigo-50 text-indigo-700 border border-indigo-100
                                        @elseif($item->category === 'Utilitas (Listrik/Air)') bg-cyan-50 text-cyan-700 border border-cyan-100
                                        @elseif($item->category === 'Inventaris/Alat') bg-purple-50 text-purple-700 border border-purple-100
                                        @else bg-slate-100 text-slate-700 border border-slate-200/50 @endif">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-black text-rose-600 text-right">
                                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('expense.edit', $item->id) }}" class="inline-flex items-center px-2.5 py-1.5 bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-slate-200 hover:border-blue-100 transition-all duration-150">
                                            Edit
                                        </a>
                                        <form action="{{ route('expense.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-700 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-slate-200 hover:border-rose-100 transition-all duration-150">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 whitespace-nowrap text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100 text-slate-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600">Tidak ada pengeluaran dicatat</p>
                                        <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Tambah Pengeluaran" di atas untuk mencatat biaya operasional baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-700">{{ $expenses->firstItem() }}</span> sampai <span class="font-bold text-slate-700">{{ $expenses->lastItem() }}</span> dari <span class="font-bold text-slate-700">{{ $expenses->total() }}</span> catatan
                    </div>
                    <div class="flex items-center gap-1.5 font-bold text-xs">
                        {{ $expenses->links() }}
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
