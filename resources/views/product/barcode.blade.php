<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Barcode Generator') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="barcodeGenerator()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 print:hidden">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">Pengaturan Cetak Barcode</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label for="product_id" :value="__('Pilih Produk (dengan SKU)')" />
                            <select id="product_id" x-model="selectedProduct" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="">-- Pilih --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->sku }}" data-name="{{ $product->name }}" data-price="{{ number_format($product->selling_price, 0, ',', '.') }}">
                                        [{{ $product->sku }}] {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="print_count" :value="__('Jumlah Stiker')" />
                            <x-text-input id="print_count" type="number" min="1" max="100" x-model="printCount" class="mt-1 block w-full focus:border-blue-500 focus:ring-blue-500" />
                        </div>

                        <div class="flex items-end gap-2">
                            <x-primary-button @click="generateBarcodes" class="bg-blue-600 hover:bg-blue-700 w-full justify-center">
                                Tampilkan Barcode
                            </x-primary-button>
                            <button @click="window.print()" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 w-full flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        * Hanya produk yang memiliki SKU yang akan muncul di daftar ini. <br>
                        * Saat menekan tombol Cetak, pastikan untuk menonaktifkan "Headers and footers" pada dialog print browser.
                    </div>
                </div>
            </div>

            <!-- Print Area -->
            <div id="printArea" class="bg-white p-6 shadow-sm sm:rounded-lg print:shadow-none print:p-0 print:m-0">
                <template x-if="barcodes.length === 0">
                    <div class="text-center text-gray-500 py-10 print:hidden">
                        Silakan pilih produk dan klik "Tampilkan Barcode"
                    </div>
                </template>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 print:grid-cols-4 print:gap-2">
                    <template x-for="item in barcodes" :key="item.id">
                        <div class="border border-gray-300 rounded-md p-3 flex flex-col items-center justify-center text-center page-break-inside-avoid print:border-gray-800 print:rounded-none print:p-2">
                            <div class="font-bold text-xs mb-1 truncate w-full" x-text="item.name"></div>
                            <svg :id="'barcode-' + item.id" class="w-full h-16"></svg>
                            <div class="font-semibold text-sm mt-1">Rp <span x-text="item.price"></span></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- JsBarcode Script -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('barcodeGenerator', () => ({
                selectedProduct: '',
                printCount: 12,
                barcodes: [],
                
                generateBarcodes() {
                    if (!this.selectedProduct) {
                        alert('Pilih produk terlebih dahulu!');
                        return;
                    }
                    
                    const selectEl = document.getElementById('product_id');
                    const selectedOption = selectEl.options[selectEl.selectedIndex];
                    const name = selectedOption.getAttribute('data-name');
                    const price = selectedOption.getAttribute('data-price');
                    const sku = this.selectedProduct;
                    
                    this.barcodes = [];
                    
                    // Generate array
                    for (let i = 0; i < this.printCount; i++) {
                        this.barcodes.push({
                            id: i + '-' + Date.now(),
                            sku: sku,
                            name: name,
                            price: price
                        });
                    }
                    
                    // Need to wait for Alpine to render the DOM elements before injecting JsBarcode
                    setTimeout(() => {
                        this.barcodes.forEach(item => {
                            JsBarcode("#barcode-" + item.id, item.sku, {
                                format: "CODE128",
                                width: 1.5,
                                height: 40,
                                displayValue: true,
                                fontSize: 12,
                                margin: 0
                            });
                        });
                    }, 100);
                }
            }))
        })
    </script>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printArea, #printArea * {
                visibility: visible;
            }
            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</x-app-layout>
