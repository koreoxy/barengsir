<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale (Kasir)') }}
        </h2>
    </x-slot>

    <!-- Alpine.js Application -->
    <div x-data="posApp({{ Js::from($products) }})" class="py-6 h-[calc(100vh-64px)] flex flex-col">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1 flex flex-col lg:flex-row gap-6 overflow-hidden pb-6">
            
            <!-- Area Kiri: Etalase Produk -->
            <div class="w-full lg:w-2/3 flex flex-col h-full bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <!-- Header & Search -->
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-800">Daftar Produk</h3>
                    <div class="w-64">
                        <x-text-input type="text" x-model="searchQuery" class="w-full text-sm placeholder-gray-400" placeholder="Cari nama produk / SKU..." />
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                        <template x-for="product in filteredProducts" :key="product.id">
                            <div @click="addToCart(product)" 
                                 class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 hover:border-blue-500 cursor-pointer transition-all duration-300 flex flex-col h-full relative group">
                                
                                <!-- Sisa Stok Badge -->
                                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-xs font-bold shadow-sm z-10 backdrop-blur-sm"
                                     :class="product.stock <= 5 ? 'bg-red-500/90 text-white' : 'bg-green-500/90 text-white'">
                                    Stok: <span x-text="product.stock"></span>
                                </div>

                                <!-- Gambar / Placeholder -->
                                <div class="h-44 bg-gray-50 flex items-center justify-center relative overflow-hidden group-hover:opacity-95 transition-opacity border-b border-gray-100">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                    </template>
                                    <template x-if="!product.image">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </template>
                                    <!-- Add to cart overlay icon -->
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-white text-blue-600 rounded-full p-3 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 shadow-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Produk -->
                                <div class="p-4 flex-1 flex flex-col justify-between bg-white z-20">
                                    <div>
                                        <div class="text-xs font-medium text-gray-500 mb-1.5" x-text="product.sku ? product.sku : '-'"></div>
                                        <h4 class="font-semibold text-gray-900 line-clamp-2 leading-snug mb-2" x-text="product.name"></h4>
                                    </div>
                                    <div class="flex justify-between items-end mt-2">
                                        <div class="font-bold text-lg text-blue-600" x-text="formatRupiah(product.selling_price)"></div>
                                    </div>
                                </div>

                            </div>
                        </template>

                        <!-- Empty State -->
                        <template x-if="filteredProducts.length === 0">
                            <div class="col-span-full py-10 text-center text-gray-500">
                                Produk tidak ditemukan.
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Area Kanan: Keranjang Belanja -->
            <div class="w-full lg:w-1/3 bg-white flex flex-col h-full rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <!-- Header Keranjang -->
                <div class="p-4 border-b border-gray-200 bg-blue-600 text-white flex justify-between items-center">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Keranjang Belanja
                    </h3>
                    <span class="bg-blue-800 py-1 px-3 rounded-full text-xs font-bold" x-text="cart.length + ' Item'"></span>
                </div>

                <!-- Daftar Item Keranjang -->
                <div class="flex-1 overflow-y-auto p-4">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <p>Keranjang masih kosong</p>
                        </div>
                    </template>

                    <div class="space-y-3">
                        <template x-for="(item, index) in cart" :key="item.product_id">
                            <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                                <div class="flex-1 pr-2">
                                    <h4 class="font-medium text-sm text-gray-900 leading-tight" x-text="item.name"></h4>
                                    <div class="text-xs text-blue-600 font-medium mt-1" x-text="formatRupiah(item.price)"></div>
                                </div>
                                <div class="flex flex-col items-end gap-2 w-28">
                                    <div class="text-sm font-bold text-gray-900" x-text="formatRupiah(item.price * item.quantity)"></div>
                                    <div class="flex items-center bg-gray-100 rounded-md">
                                        <button @click="decreaseQty(index)" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded-l-md transition-colors" :disabled="item.quantity <= 1">
                                            -
                                        </button>
                                        <span class="w-8 h-7 flex items-center justify-center text-sm font-medium bg-white" x-text="item.quantity"></span>
                                        <button @click="increaseQty(index)" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded-r-md transition-colors">
                                            +
                                        </button>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-xs text-red-500 hover:text-red-700 underline mt-1">Hapus</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Panel Checkout (Sticky Bottom) -->
                <div class="border-t border-gray-200 bg-gray-50 p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Total Tagihan:</span>
                        <span class="text-2xl font-bold text-blue-600" x-text="formatRupiah(totalAmount)"></span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs text-gray-600 mb-1">Uang Bayar (Rp):</label>
                        <input type="text" :value="displayPaidAmount" @input="updatePaidAmount" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-lg font-bold text-right py-3" placeholder="0" autocomplete="off" />
                    </div>

                    <div class="flex justify-between items-center mb-4 text-sm">
                        <span class="text-gray-600">Kembalian:</span>
                        <span class="font-bold" :class="changeAmount < 0 ? 'text-red-500' : 'text-green-600'" x-text="formatRupiah(changeAmount)"></span>
                    </div>

                    <button @click="processCheckout" 
                            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2"
                            :disabled="cart.length === 0 || paidAmount < totalAmount || isProcessing">
                        
                        <template x-if="isProcessing">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <template x-if="!isProcessing">
                            <span>PROSES PEMBAYARAN</span>
                        </template>
                    </button>
                </div>
            </div>

        </div>

        <!-- Success Modal Overlay -->
        <div x-show="showSuccessModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSuccessModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900/75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showSuccessModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                    
                    <div class="bg-white px-6 pt-8 pb-6 text-center border-b border-gray-100">
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6 ring-8 ring-green-50">
                            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl leading-6 font-extrabold text-gray-900 mb-2">Transaksi Berhasil!</h3>
                        <p class="text-sm text-gray-500">Invoice: <span class="font-bold text-gray-800" x-text="lastInvoice"></span></p>
                    </div>
                    
                    <div class="bg-gray-50 p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Total Tagihan</span>
                                <span class="font-bold text-gray-900" x-text="formatRupiah(lastTotal)"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Jumlah Bayar</span>
                                <span class="font-bold text-gray-900" x-text="formatRupiah(lastPaid)"></span>
                            </div>
                            <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between items-center">
                                <span class="text-gray-800 font-bold text-base">Kembalian</span>
                                <span class="font-bold text-green-600 text-xl" x-text="formatRupiah(lastChange)"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white px-6 py-5 flex flex-col gap-3">
                        <button type="button" @click="printReceipt" class="w-full inline-flex justify-center items-center rounded-xl border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Struk
                        </button>
                        <button type="button" @click="closeModal" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-4 py-3 bg-blue-600 text-base font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Transaksi Baru
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Alpine Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', (products) => ({
                products: products,
                searchQuery: '',
                cart: [],
                paidAmount: null,
                displayPaidAmount: '',
                isProcessing: false,
                
                // Modal state
                showSuccessModal: false,
                lastInvoice: '',
                lastChange: 0,
                lastTotal: 0,
                lastPaid: 0,

                get filteredProducts() {
                    if (this.searchQuery === '') {
                        return this.products;
                    }
                    const lowerQuery = this.searchQuery.toLowerCase();
                    return this.products.filter(p => 
                        p.name.toLowerCase().includes(lowerQuery) || 
                        (p.sku && p.sku.toLowerCase().includes(lowerQuery))
                    );
                },

                get totalAmount() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                get changeAmount() {
                    if (this.paidAmount === null || this.paidAmount === '') return 0;
                    return this.paidAmount - this.totalAmount;
                },

                addToCart(product) {
                    // Check stock limit first
                    const existingItem = this.cart.find(i => i.product_id === product.id);
                    const currentQtyInCart = existingItem ? existingItem.quantity : 0;

                    if (currentQtyInCart >= product.stock) {
                        alert('Stok barang tidak mencukupi!');
                        return;
                    }

                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.unshift({
                            product_id: product.id,
                            name: product.name,
                            price: product.selling_price,
                            quantity: 1,
                            max_stock: product.stock
                        });
                    }
                },

                increaseQty(index) {
                    const item = this.cart[index];
                    if (item.quantity < item.max_stock) {
                        item.quantity++;
                    } else {
                        alert('Kuantitas melebihi sisa stok yang tersedia!');
                    }
                },

                decreaseQty(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                updatePaidAmount(e) {
                    let val = e.target.value.replace(/\D/g, '');
                    this.paidAmount = val ? parseInt(val, 10) : null;
                    this.displayPaidAmount = val ? new Intl.NumberFormat('id-ID').format(this.paidAmount) : '';
                },

                printReceipt() {
                    window.print();
                },

                async processCheckout() {
                    if (this.cart.length === 0) return;
                    if (this.paidAmount < this.totalAmount) return;

                    this.isProcessing = true;

                    try {
                        const payload = {
                            items: this.cart.map(i => ({
                                product_id: i.product_id,
                                quantity: i.quantity
                            })),
                            paid_amount: this.paidAmount
                        };

                        const response = await fetch('{{ route("transaction.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Show success modal
                            this.lastInvoice = result.invoice;
                            this.lastChange = result.change;
                            this.lastTotal = this.totalAmount;
                            this.lastPaid = this.paidAmount;
                            this.showSuccessModal = true;
                            
                            // Subtract stock from local products array so it updates visually
                            this.cart.forEach(cartItem => {
                                const prodIndex = this.products.findIndex(p => p.id === cartItem.product_id);
                                if (prodIndex !== -1) {
                                    this.products[prodIndex].stock -= cartItem.quantity;
                                }
                            });
                            
                            // Reset cart
                            this.cart = [];
                            this.paidAmount = null;
                            this.displayPaidAmount = '';
                            this.searchQuery = '';
                        } else {
                            alert('Gagal: ' + result.message);
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan koneksi saat memproses transaksi.');
                        console.error(error);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                closeModal() {
                    this.showSuccessModal = false;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
                }
            }));
        });
    </script>
</x-app-layout>
