<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informasi Dasar -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="name" :value="__('Nama Produk')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('name')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="sku" :value="__('SKU (Opsional)')" />
                                    <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('sku')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                                </div>

                                <div>
                                    <x-input-label for="category_id" :value="__('Kategori')" />
                                    <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                                </div>

                                <div>
                                    <x-input-label for="brand_id" :value="__('Brand/Merk')" />
                                    <select id="brand_id" name="brand_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                        <option value="">-- Pilih Brand --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('brand_id')" />
                                </div>
                            </div>

                            <!-- Harga & Stok -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="purchase_price_display" :value="__('Harga Modal (Beli)')" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input id="purchase_price_display" type="text" inputmode="numeric"
                                            class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="0"
                                            value="{{ old('purchase_price') ? number_format(old('purchase_price'), 0, ',', '.') : '' }}"
                                            data-price-input="purchase_price" />
                                        <input type="hidden" id="purchase_price" name="purchase_price"
                                            value="{{ old('purchase_price', 0) }}" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('purchase_price')" />
                                </div>

                                <div>
                                    <x-input-label for="selling_price_display" :value="__('Harga Jual')" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input id="selling_price_display" type="text" inputmode="numeric"
                                            class="block w-full rounded-md border-gray-300 pl-10 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            placeholder="0"
                                            value="{{ old('selling_price') ? number_format(old('selling_price'), 0, ',', '.') : '' }}"
                                            data-price-input="selling_price" />
                                        <input type="hidden" id="selling_price" name="selling_price"
                                            value="{{ old('selling_price', 0) }}" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('selling_price')" />
                                </div>

                                <div>
                                    <x-input-label for="stock" :value="__('Stok Awal')" />
                                    <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('stock', 0)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Gambar Produk (Opsional)')" />
                                    <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:ring-blue-500"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Produk')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:ring-blue-500">
                                {{ __('Simpan Produk') }}
                            </x-primary-button>
                            
                            <a href="{{ route('product.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>

                <script>
                    document.querySelectorAll('[data-price-input]').forEach(function(displayInput) {
                        const hiddenId = displayInput.getAttribute('data-price-input');
                        const hiddenInput = document.getElementById(hiddenId);

                        function formatNumber(val) {
                            const raw = val.replace(/\D/g, '');
                            return raw ? parseInt(raw).toLocaleString('id-ID') : '';
                        }

                        displayInput.addEventListener('input', function() {
                            const raw = this.value.replace(/\D/g, '');
                            hiddenInput.value = raw || 0;
                            const pos = this.selectionStart;
                            const prevLen = this.value.length;
                            this.value = formatNumber(this.value);
                            const newLen = this.value.length;
                            this.setSelectionRange(pos + (newLen - prevLen), pos + (newLen - prevLen));
                        });

                        displayInput.addEventListener('keydown', function(e) {
                            // Allow: backspace, delete, tab, escape, enter, arrows
                            const allowed = [8, 9, 13, 27, 35, 36, 37, 38, 39, 40, 46];
                            if (allowed.includes(e.keyCode)) return;
                            // Block non-numeric input
                            if ((e.shiftKey || e.ctrlKey || e.metaKey) && ![65, 67, 86, 88, 90].includes(e.keyCode)) return;
                            if (e.keyCode < 48 || (e.keyCode > 57 && e.keyCode < 96) || e.keyCode > 105) {
                                e.preventDefault();
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
