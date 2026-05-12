<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stock Opname / Penyesuaian Stok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Form Stock Opname -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4 border-b pb-2">Form Penyesuaian Stok</h3>
                            
                            <form action="{{ route('product.stock_opname.store') }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <x-input-label for="product_id" :value="__('Pilih Produk')" />
                                    <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->sku ? '['.$product->sku.'] ' : '' }}{{ $product->name }} (Sisa: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('product_id')" />
                                </div>

                                <div>
                                    <x-input-label for="type" :value="__('Jenis Pergerakan')" />
                                    <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                        <option value="in">Stok Masuk (+)</option>
                                        <option value="out">Stok Keluar (-)</option>
                                        <option value="adjustment">Penyesuaian Angka Absolut</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('type')" />
                                </div>

                                <div>
                                    <x-input-label for="quantity" :value="__('Jumlah / Angka Final')" />
                                    <x-text-input id="quantity" name="quantity" type="number" min="1" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
                                </div>

                                <div>
                                    <x-input-label for="note" :value="__('Catatan (Opsional)')" />
                                    <x-text-input id="note" name="note" type="text" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" placeholder="Misal: Barang rusak, retur, dll" />
                                    <x-input-error class="mt-2" :messages="$errors->get('note')" />
                                </div>

                                <div class="pt-2">
                                    <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:ring-blue-500">
                                        {{ __('Simpan Perubahan Stok') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pergerakan Stok -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium mb-4 border-b pb-2">10 Riwayat Terakhir</h3>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($recentMovements as $movement)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $movement->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $movement->product->name ?? 'Produk Dihapus' }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                @if($movement->type === 'in')
                                                    <span class="text-green-600 font-bold">Masuk</span>
                                                @elseif($movement->type === 'out')
                                                    <span class="text-red-600 font-bold">Keluar</span>
                                                @else
                                                    <span class="text-blue-600 font-bold">Set</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                {{ $movement->quantity }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500 truncate max-w-xs">
                                                {{ $movement->note ?? '-' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">
                                                Belum ada riwayat pergerakan stok.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
