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
                                    <x-input-label for="purchase_price" :value="__('Harga Modal (Beli)')" />
                                    <x-text-input id="purchase_price" name="purchase_price" type="number" step="0.01" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('purchase_price', 0)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('purchase_price')" />
                                </div>

                                <div>
                                    <x-input-label for="selling_price" :value="__('Harga Jual')" />
                                    <x-text-input id="selling_price" name="selling_price" type="number" step="0.01" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" :value="old('selling_price', 0)" required />
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
            </div>
        </div>
    </div>
</x-app-layout>
