<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pengeluaran') }}
            </h2>
            <a href="{{ route('expense.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-all duration-150">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 p-8">
                <form action="{{ route('expense.update', $expense->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul/Deskripsi Pengeluaran</label>
                        <input id="title" name="title" type="text" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all font-semibold" value="{{ old('title', $expense->title) }}" required />
                        @error('title')
                            <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                            <select id="category" name="category" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all font-semibold" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $expense->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="expense_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Biaya</label>
                            <input id="expense_date" name="expense_date" type="date" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all font-semibold" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required />
                            @error('expense_date')
                                <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="amount" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Biaya (Rp)</label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm font-bold">Rp</span>
                            </div>
                            <input id="amount" name="amount" type="number" step="0.01" class="block w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all font-black" placeholder="0" value="{{ old('amount', $expense->amount) }}" required />
                        </div>
                        @error('amount')
                            <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="note" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea id="note" name="note" rows="3" class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white text-sm text-slate-800 focus:outline-none transition-all font-medium" placeholder="Tulis catatan atau rincian tambahan di sini...">{{ old('note', $expense->note) }}</textarea>
                        @error('note')
                            <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-4 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/35 transition-all duration-150">
                            Perbarui Catatan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
