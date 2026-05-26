<x-app-layout>

{{-- Override: halaman ini pakai fixed layout sendiri, content-main harus tidak overflow --}}
<style>
    .content-main { overflow: visible !important; }
    /* Desktop: lock scroll. Mobile: let the catalog column scroll naturally */
    @media (min-width: 768px) { body { overflow: hidden !important; } }

    /* Hide scrollbar on mobile category bar (Chrome/Safari/WebKit) */
    .mob-cat-scroll::-webkit-scrollbar { display: none; }
</style>

<div x-data="posApp({{ Js::from($products) }}, {{ Js::from($categories) }})"
     class="flex flex-col md:flex-row h-screen md:h-screen overflow-y-auto md:overflow-hidden bg-slate-100">

    {{-- LEFT: Product Catalog --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden md:overflow-hidden overflow-y-auto md:overflow-y-hidden">

        {{-- ── MOBILE TOP BAR (hidden on desktop) ─────────────────── --}}
        <div class="sticky top-0 z-10 bg-white border-b border-slate-100 md:hidden shrink-0">
            <div class="flex items-center justify-between px-4 py-3">
                {{-- Back button --}}
                <a href="{{ route('dashboard') }}"
                   class="p-2 -ml-1 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                {{-- Table / Customer info --}}
                <div class="text-center">
                    <h2 class="text-sm font-extrabold text-slate-800 leading-tight" x-text="tableName"></h2>
                    <p class="text-[10px] text-slate-400" x-show="customerName" x-text="customerName"></p>
                    <p class="text-[10px] text-slate-400" x-show="!customerName">Point of Sale</p>
                </div>

                {{-- Search toggle --}}
                <button @click="mobileSearchOpen = !mobileSearchOpen"
                        class="p-2 -mr-1 rounded-xl transition-colors active:scale-95"
                        :class="mobileSearchOpen ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                </button>
            </div>

            {{-- Slide-down search input --}}
            <div x-show="mobileSearchOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="px-4 pb-3 relative">
                <svg class="absolute left-7 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" x-model="searchQuery" @input="currentPage=1"
                       x-ref="mobileSearchInput"
                       class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent placeholder-slate-400"
                       placeholder="Cari nama produk / SKU...">
            </div>

            {{-- Mobile Category Horizontal Scroll --}}
            {{-- Inline style guarantees cross-browser scroll: iOS touch momentum + Firefox/Chrome/Edge hide scrollbar --}}
            <div class="mob-cat-scroll flex items-center gap-2 px-4 pb-3 pt-0"
                 style="overflow-x: scroll; -webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none; flex-wrap: nowrap;">
                <button @click="selectedCategory=null; currentPage=1"
                        :class="selectedCategory===null
                            ? 'bg-blue-600 text-white shadow-sm shadow-blue-200'
                            : 'bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-600'"
                        class="inline-flex items-center shrink-0 whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-150">
                    Semua
                </button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="selectedCategory=cat.id; currentPage=1"
                            :class="selectedCategory===cat.id
                                ? 'bg-blue-600 text-white shadow-sm shadow-blue-200'
                                : 'bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-600'"
                            class="inline-flex items-center shrink-0 whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-150"
                            x-text="cat.name">
                    </button>
                </template>
            </div>
        </div>

        {{-- ── DESKTOP Top Bar (hidden on mobile) ──────────────────── --}}
        <div class="shrink-0 px-5 pt-4 pb-3 bg-slate-100 hidden md:block">
            <div class="flex items-center justify-between gap-4 mb-3">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Point of Sale</h2>
                    <p class="text-xs text-slate-400">Klik produk untuk menambahkan ke keranjang</p>
                </div>
                <div class="relative w-72">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" x-model="searchQuery" @input="currentPage=1"
                           class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-400 shadow-sm"
                           placeholder="Cari nama produk / SKU...">
                </div>
            </div>

            {{-- Category Tabs --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <button @click="selectedCategory=null; currentPage=1"
                        :class="selectedCategory===null
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                            : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-300 hover:text-blue-600'"
                        class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150">
                    Semua
                </button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="selectedCategory=cat.id; currentPage=1"
                            :class="selectedCategory===cat.id
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
                                : 'bg-white text-slate-600 border border-slate-200 hover:border-blue-300 hover:text-blue-600'"
                            class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150"
                            x-text="cat.name">
                    </button>
                </template>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto px-4 md:px-5 pb-2">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 md:gap-4 pb-32 md:pb-2">
                <template x-for="product in paginatedProducts" :key="product.id">
                    <div @click="handleAddToCart(product)"
                         :class="product.stock <= 0 ? 'cursor-not-allowed' : 'hover:border-blue-400 hover:shadow-xl hover:-translate-y-0.5 cursor-pointer'"
                         class="bg-white rounded-2xl overflow-hidden border border-slate-200 transition-all duration-200 flex flex-col group relative">

                        {{-- Stock Badge --}}
                        <div class="absolute top-2.5 right-2.5 z-20 text-[10px] font-bold px-2 py-0.5 rounded-full"
                             :class="product.stock <= 0 ? 'bg-red-100 text-red-600 border border-red-200'
                                   : product.stock <= 5 ? 'bg-amber-100 text-amber-700 border border-amber-200'
                                   : 'bg-emerald-100 text-emerald-700 border border-emerald-200'">
                            <span x-show="product.stock > 0">Stok: <span x-text="product.stock"></span></span>
                            <span x-show="product.stock <= 0">Habis</span>
                        </div>

                        {{-- Image area --}}
                        <div class="aspect-square md:h-36 md:aspect-auto flex items-center justify-center overflow-hidden relative border-b border-slate-100">

                            {{-- With image --}}
                            <template x-if="product.image">
                                <img :src="'/storage/' + product.image"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     :class="product.stock <= 0 ? 'opacity-60' : ''">
                            </template>

                            {{-- No image placeholder --}}
                            <template x-if="!product.image">
                                <div class="w-full h-full flex flex-col items-center justify-center"
                                     :class="product.stock <= 0 ? 'bg-slate-100' : 'bg-gradient-to-br from-slate-50 to-blue-50'">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-1"
                                         :class="product.stock <= 0 ? 'bg-slate-200' : 'bg-white shadow-sm border border-slate-100'">
                                        <svg class="w-7 h-7" :class="product.stock <= 0 ? 'text-slate-400' : 'text-blue-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-medium" :class="product.stock <= 0 ? 'text-slate-400' : 'text-slate-400'">No Image</p>
                                </div>
                            </template>

                            {{-- Stock Habis Overlay --}}
                            <div x-show="product.stock <= 0"
                                 class="absolute inset-0 bg-slate-900/40 flex items-center justify-center backdrop-blur-[2px]">
                                <div class="text-center">
                                    <span class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-lg tracking-widest uppercase block">
                                        Stok Habis
                                    </span>
                                </div>
                            </div>

                            {{-- Stock insufficient flash (saat klik tapi stok di cart sudah max) --}}
                            <div x-show="stockAlert === product.id"
                                 x-transition:enter="transition ease-out duration-150"
                                  x-transition:enter-start="opacity-0 scale-95"
                                  x-transition:enter-end="opacity-100 scale-100"
                                  x-transition:leave="transition ease-in duration-200"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 bg-orange-600/90 flex flex-col items-center justify-center backdrop-blur-[1px] z-30">
                                <svg class="w-8 h-8 text-white mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <span class="text-white text-xs font-bold tracking-wide">Stok Tidak Cukup</span>
                            </div>

                            {{-- Add overlay (hanya jika stok ada) --}}
                            <div x-show="product.stock > 0"
                                 class="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/10 flex items-center justify-center transition-all duration-200">
                                <div class="w-9 h-9 rounded-full bg-white shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-200">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-3 flex-1 flex flex-col">
                            <p class="text-[10px] text-slate-400 mb-0.5" x-text="product.category ? product.category.name : '-'"></p>
                            <h4 class="text-sm font-semibold text-slate-800 leading-tight line-clamp-2" x-text="product.name"></h4>
                            <p class="text-[11px] text-slate-400 leading-snug line-clamp-2 mt-1" x-show="product.description" x-text="product.description"></p>
                            <p class="text-sm font-bold mt-auto pt-2" :class="product.stock <= 0 ? 'text-slate-400' : 'text-blue-600'"
                               x-text="formatCurrency(product.selling_price)"></p>
                        </div>
                    </div>
                </template>

                {{-- Empty --}}
                <template x-if="filteredProducts.length === 0">
                    <div class="col-span-full py-20 flex flex-col items-center gap-3 text-slate-400">
                        <svg class="w-12 h-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        <p class="text-sm font-medium">Produk tidak ditemukan</p>
                    </div>
                </template>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="shrink-0 px-5 py-3 bg-slate-100 border-t border-slate-200 flex items-center justify-between">
            <p class="text-xs text-slate-500">
                Menampilkan <span class="font-semibold text-slate-700" x-text="pageStart"></span>–<span class="font-semibold text-slate-700" x-text="pageEnd"></span>
                dari <span class="font-semibold text-slate-700" x-text="filteredProducts.length"></span> produk
            </p>
            <div class="flex items-center gap-1">
                <button @click="currentPage > 1 && currentPage--"
                        :disabled="currentPage <= 1"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600
                               hover:border-blue-400 hover:text-blue-600 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <template x-for="page in totalPages" :key="page">
                    <button @click="currentPage = page"
                            :class="currentPage === page
                                ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-200'
                                : 'bg-white text-slate-600 border-slate-200 hover:border-blue-400 hover:text-blue-600'"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border text-xs font-semibold transition-all duration-150"
                            x-text="page">
                    </button>
                </template>

                <button @click="currentPage < totalPages && currentPage++"
                        :disabled="currentPage >= totalPages"
                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600
                               hover:border-blue-400 hover:text-blue-600 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

    </div>

    {{-- RIGHT: Cart (Desktop only – hidden on mobile) --}}
    <div class="hidden md:flex shrink-0 w-80 xl:w-96 bg-white border-l border-slate-200 flex-col shadow-xl">

        {{-- Header --}}
        <div class="shrink-0 px-5 py-4 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Keranjang Belanja</h3>
                    <p class="text-xs text-slate-400 mt-0.5"><span x-text="cart.length"></span> item dipilih</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shadow-md shadow-blue-200">
                    <span class="text-sm font-bold text-white" x-text="cart.reduce((s,i)=>s+i.quantity,0)"></span>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-slate-300 py-16">
                    <svg class="w-14 h-14 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="text-sm font-medium">Keranjang masih kosong</p>
                </div>
            </template>

            <template x-for="(item, index) in cart" :key="item.product_id">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <div class="flex items-start justify-between gap-2 mb-2.5">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 line-clamp-1" x-text="item.name"></p>
                            <p class="text-xs text-blue-600 font-medium mt-0.5" x-text="formatCurrency(item.price)"></p>
                        </div>
                        <button @click="removeFromCart(index)"
                                class="shrink-0 w-6 h-6 rounded-lg bg-slate-200 hover:bg-red-100 hover:text-red-500 flex items-center justify-center text-slate-400 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center bg-white border border-slate-200 rounded-lg overflow-hidden">
                            <button @click="decreaseQty(index)" :disabled="item.quantity<=1"
                                    class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 disabled:opacity-30 transition-colors font-bold">−</button>
                            <span class="w-9 h-8 flex items-center justify-center text-sm font-bold text-slate-800 border-x border-slate-200" x-text="item.quantity"></span>
                            <button @click="increaseQty(index)"
                                    class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors font-bold">+</button>
                        </div>
                        <p class="text-sm font-bold text-slate-800" x-text="formatCurrency(item.price * item.quantity)"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Checkout --}}
        <div class="shrink-0 border-t border-slate-100 px-5 py-4 space-y-3">
            <div class="flex justify-between text-sm text-slate-500">
                <span>Subtotal</span>
                <span class="font-medium text-slate-700" x-text="formatCurrency(subtotalAmount)"></span>
            </div>
            <template x-if="taxPercent > 0">
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Pajak (PPN <span x-text="taxPercent"></span>%)</span>
                    <span class="font-medium text-slate-700" x-text="formatCurrency(taxAmount)"></span>
                </div>
            </template>
            <div class="flex justify-between items-center py-1.5 border-t border-dashed border-slate-200">
                <span class="text-base font-bold text-slate-800">Total</span>
                <span class="text-xl font-extrabold text-blue-600" x-text="formatCurrency(totalAmount)"></span>
            </div>

            {{-- Opsi Metode Pembayaran --}}
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200/50">
                    <button type="button" @click="setPaymentMethod('cash')"
                            :class="paymentMethod === 'cash' ? 'bg-white text-blue-600 shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 font-semibold'"
                            class="py-2 text-xs rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5 border border-transparent">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Cash / Tunai
                    </button>
                    <button type="button" @click="setPaymentMethod('qris')"
                            :class="paymentMethod === 'qris' ? 'bg-white text-blue-600 shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 font-semibold'"
                            class="py-2 text-xs rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5 border border-transparent">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        QRIS
                    </button>
                </div>
            </div>

            {{-- Input Tunai / Info QRIS --}}
            <div x-show="paymentMethod === 'cash'"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Uang Diterima</label>
                <input type="text" :value="displayPaidAmount" @input="updatePaidAmount"
                       class="w-full px-4 py-3 text-right text-lg font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-300 shadow-inner"
                       placeholder="Masukkan nominal..." autocomplete="off">
            </div>

            <div x-show="paymentMethod === 'qris'"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-md shadow-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-blue-700 leading-tight">Metode Pembayaran QRIS</p>
                    <p class="text-[10px] text-blue-500 mt-0.5">Uang pembayaran otomatis pas sesuai total belanja.</p>
                </div>
            </div>

            {{-- Kembalian (hanya untuk cash) --}}
            <div x-show="paymentMethod === 'cash'" class="flex justify-between items-center text-sm px-1 py-0.5">
                <span class="text-slate-500">Kembalian</span>
                <span class="font-bold text-base" :class="changeAmount < 0 ? 'text-red-500' : 'text-emerald-600'" x-text="formatCurrency(changeAmount)"></span>
            </div>

            <button type="button" @click.prevent="processCheckout"
                    :disabled="cart.length===0 || (paymentMethod==='cash' && (paidAmount===null || paidAmount<totalAmount)) || isProcessing"
                    class="w-full py-3.5 px-4 rounded-xl font-bold text-base text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition-all duration-150 shadow-lg shadow-blue-200">
                <template x-if="isProcessing">
                    <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <template x-if="!isProcessing"><span>PROSES PEMBAYARAN</span></template>
            </button>
        </div>
    </div>

{{-- ═══════════════════════════════════════════════════════════
     MOBILE COMPONENTS (md:hidden) — FAB + Cart Drawer + Bottom Nav
     ═══════════════════════════════════════════════════════════ --}}

{{-- ── Floating Action Button (FAB) Keranjang ────────────────── --}}
<button @click="toggleCartDrawer(true)"
        class="fixed bottom-20 right-4 z-30 md:hidden w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-300 transition-transform active:scale-95 focus:outline-none">
    <div class="relative">
        {{-- Cart Icon --}}
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        {{-- Badge count --}}
        <span x-show="cart.reduce((s,i)=>s+i.quantity,0) > 0"
              x-text="cart.reduce((s,i)=>s+i.quantity,0)"
              x-transition:enter="transition ease-out duration-150"
              x-transition:enter-start="opacity-0 scale-50"
              x-transition:enter-end="opacity-100 scale-100"
              class="absolute -top-3.5 -right-3.5 bg-red-500 text-white text-[10px] font-extrabold min-w-[18px] px-1 py-0.5 rounded-full border-2 border-white text-center leading-none">
        </span>
    </div>
</button>

{{-- ── Slide-Up Cart Bottom Sheet Drawer ──────────────────────── --}}
<div x-show="cartDrawerOpen" class="fixed inset-0 z-50 md:hidden" style="display:none;">

    {{-- Backdrop --}}
    <div x-show="cartDrawerOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="toggleCartDrawer(false)"
         class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    {{-- Bottom Sheet Card --}}
    <div x-show="cartDrawerOpen"
         x-transition:enter="transition ease-out duration-300 transform-gpu"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform-gpu"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl max-h-[88vh] flex flex-col shadow-2xl pb-safe">

        {{-- Handle + Header --}}
        <div class="shrink-0 flex flex-col items-center pt-3 pb-2 border-b border-slate-100 px-5">
            <div class="w-12 h-1 bg-slate-200 rounded-full mb-3 cursor-pointer" @click="toggleCartDrawer(false)"></div>
            <div class="w-full flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800">Detail Pesanan</h3>
                    <p class="text-xs text-slate-400 mt-0.5"><span x-text="cart.reduce((s,i)=>s+i.quantity,0)"></span> item terpilih</p>
                </div>
                <button @click="toggleCartDrawer(false)"
                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Cart Items (scrollable) --}}
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3">

            {{-- Empty state --}}
            <template x-if="cart.length === 0">
                <div class="h-40 flex flex-col items-center justify-center text-slate-300">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="text-sm font-medium">Keranjang masih kosong</p>
                </div>
            </template>

            {{-- Cart item list (reuses same Alpine cart state as desktop) --}}
            <template x-for="(item, index) in cart" :key="item.product_id">
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 line-clamp-1" x-text="item.name"></p>
                            <p class="text-xs text-blue-600 font-medium mt-0.5" x-text="formatCurrency(item.price)"></p>
                        </div>
                        <button @click="removeFromCart(index)"
                                class="shrink-0 w-7 h-7 rounded-lg bg-slate-200 hover:bg-red-100 hover:text-red-500 flex items-center justify-center text-slate-400 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        {{-- Qty controls (large touch targets) --}}
                        <div class="flex items-center bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                            <button @click="decreaseQty(index)" :disabled="item.quantity<=1"
                                    class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 disabled:opacity-30 transition-colors font-bold text-lg">−</button>
                            <span class="w-10 h-10 flex items-center justify-center text-sm font-bold text-slate-800 border-x border-slate-200" x-text="item.quantity"></span>
                            <button @click="increaseQty(index)"
                                    class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors font-bold text-lg">+</button>
                        </div>
                        <p class="text-sm font-bold text-slate-800" x-text="formatCurrency(item.price * item.quantity)"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Checkout Footer --}}
        <div class="shrink-0 border-t border-slate-100 px-5 py-4 space-y-3 bg-white">
            {{-- Subtotal & tax --}}
            <div class="flex justify-between text-sm text-slate-500">
                <span>Subtotal</span>
                <span class="font-medium text-slate-700" x-text="formatCurrency(subtotalAmount)"></span>
            </div>
            <template x-if="taxPercent > 0">
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Pajak (PPN <span x-text="taxPercent"></span>%)</span>
                    <span class="font-medium text-slate-700" x-text="formatCurrency(taxAmount)"></span>
                </div>
            </template>
            <div class="flex justify-between items-center py-1.5 border-t border-dashed border-slate-200">
                <span class="text-base font-bold text-slate-800">Total</span>
                <span class="text-xl font-extrabold text-blue-600" x-text="formatCurrency(totalAmount)"></span>
            </div>

            {{-- Payment Method Toggle --}}
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200/50">
                    <button type="button" @click="setPaymentMethod('cash')"
                            :class="paymentMethod === 'cash' ? 'bg-white text-blue-600 shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 font-semibold'"
                            class="py-2.5 text-xs rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Cash / Tunai
                    </button>
                    <button type="button" @click="setPaymentMethod('qris')"
                            :class="paymentMethod === 'qris' ? 'bg-white text-blue-600 shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 font-semibold'"
                            class="py-2.5 text-xs rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        QRIS
                    </button>
                </div>
            </div>

            {{-- Cash: uang diterima --}}
            <div x-show="paymentMethod === 'cash'"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5 block">Uang Diterima</label>
                <input type="text" :value="displayPaidAmount" @input="updatePaidAmount"
                       class="w-full px-4 py-3 text-right text-lg font-bold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-300 shadow-inner"
                       placeholder="Masukkan nominal..." autocomplete="off">
            </div>

            {{-- QRIS info --}}
            <div x-show="paymentMethod === 'qris'"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-md shadow-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-blue-700">Metode Pembayaran QRIS</p>
                    <p class="text-[10px] text-blue-500 mt-0.5">Uang pembayaran otomatis pas sesuai total belanja.</p>
                </div>
            </div>

            {{-- Kembalian --}}
            <div x-show="paymentMethod === 'cash'" class="flex justify-between items-center text-sm px-1">
                <span class="text-slate-500">Kembalian</span>
                <span class="font-bold text-base" :class="changeAmount < 0 ? 'text-red-500' : 'text-emerald-600'" x-text="formatCurrency(changeAmount)"></span>
            </div>

            {{-- Process Button --}}
            <button type="button" @click.prevent="processCheckout(); if($event.target && cart.length===0) toggleCartDrawer(false);"
                    :disabled="cart.length===0 || (paymentMethod==='cash' && (paidAmount===null || paidAmount<totalAmount)) || isProcessing"
                    class="w-full py-4 px-4 rounded-xl font-bold text-base text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 transition-all duration-150 shadow-lg shadow-blue-200">
                <template x-if="isProcessing">
                    <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <template x-if="!isProcessing"><span>PROSES PEMBAYARAN</span></template>
            </button>
        </div>
    </div>
</div>

{{-- ── Bottom Sticky Navigation Bar ────────────────────────────── --}}
<div class="fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-slate-100 md:hidden pb-safe">
    <div class="flex items-center justify-around py-2.5 px-2">

        {{-- 1. POS Menu (Active) --}}
        <a href="#" class="flex flex-col items-center gap-0.5 text-blue-600 relative px-3 py-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
            </svg>
            <span class="text-[9px] font-bold">POS</span>
            {{-- Active dot --}}
            <span class="absolute -bottom-0.5 w-1 h-1 bg-blue-600 rounded-full"></span>
        </a>

        {{-- 2. Order List --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600 px-3 py-1 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="text-[9px] font-semibold">Order</span>
        </a>

        {{-- 3. Customers --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600 px-3 py-1 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-[9px] font-semibold">Pelanggan</span>
        </a>

        {{-- 4. Quick Action --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600 px-3 py-1 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span class="text-[9px] font-semibold">Aksi</span>
        </a>

        {{-- 5. Settings --}}
        <a href="{{ route('setting.index') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600 px-3 py-1 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-[9px] font-semibold">Settings</span>
        </a>

    </div>
</div>

{{-- ═══════════════════════════════════════
     SUCCESS MODAL — Struk Belanja (teleported to body)
     ═══════════════════════════════════════ --}}
<template x-teleport="body">
<div>
<div x-show="showSuccessModal"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     style="display:none;">

    {{-- Backdrop --}}
    <div x-show="showSuccessModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         @click="closeModal"
         class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm cursor-pointer"></div>

    {{-- Modal Card --}}
    <div x-show="showSuccessModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
         class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md flex flex-col overflow-hidden max-h-[90vh]">

        {{-- Header hijau --}}
        <div class="shrink-0 bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-white">Transaksi Berhasil!</h3>
                <p class="text-emerald-100 text-xs mt-0.5">Struk pembayaran telah disiapkan</p>
            </div>
        </div>

        {{-- STRUK AREA (scrollable) --}}
        <div class="flex-1 overflow-y-auto">

            {{-- Struk body (ini yang akan dicetak) --}}
            <div id="receipt-area" class="px-6 py-5">

                {{-- Nama Toko & Logo --}}
                <div class="text-center mb-4 border-b border-dashed border-slate-300 pb-3">
                    @if(setting('store_logo'))
                        <div class="flex justify-center mb-3">
                            <img src="{{ asset('storage/' . setting('store_logo')) }}" class="h-12 w-auto object-contain">
                        </div>
                    @endif
                    <h2 class="text-base font-extrabold text-slate-900 tracking-wide uppercase">
                        {{ $branch->name ?? config('app.name') }}
                    </h2>
                    @if(!empty($branch->address))
                        <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">{{ $branch->address }}</p>
                    @endif
                    @if(!empty($branch->phone))
                        <p class="text-[10px] text-slate-500">Telp: {{ $branch->phone }}</p>
                    @endif
                </div>

                {{-- Info Transaksi --}}
                <div class="space-y-1 text-xs text-slate-600 mb-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">No. Struk</span>
                        <span class="font-semibold text-slate-800 font-mono" x-text="lastInvoice"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal</span>
                        <span class="font-medium text-slate-700" x-text="lastDateTime"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kasir</span>
                        <span class="font-medium text-slate-700">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Metode Bayar</span>
                        <span class="font-bold text-slate-800 uppercase" x-text="lastPaymentMethod === 'cash' ? 'Cash / Tunai' : 'QRIS'"></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 mb-3"></div>

                {{-- Daftar Item --}}
                <div class="space-y-2 mb-3">
                    <template x-for="item in lastItems" :key="item.product_id">
                        <div>
                            <p class="text-xs font-semibold text-slate-800 leading-tight" x-text="item.name"></p>
                            <div class="flex justify-between items-center mt-0.5">
                                <span class="text-xs text-slate-500">
                                    <span x-text="item.quantity"></span> x
                                    <span x-text="formatCurrency(item.price)"></span>
                                </span>
                                <span class="text-xs font-bold text-slate-800" x-text="formatCurrency(item.price * item.quantity)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-slate-300 mb-3"></div>

                {{-- Summary --}}
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span x-text="formatCurrency(lastItems.reduce((s, i) => s + i.price * i.quantity, 0))"></span>
                    </div>
                    <template x-if="taxPercent > 0">
                        <div class="flex justify-between text-slate-600">
                            <span>Pajak (PPN <span x-text="taxPercent"></span>%)</span>
                            <span x-text="formatCurrency(lastItems.reduce((s, i) => s + i.price * i.quantity, 0) * (taxPercent / 100))"></span>
                        </div>
                    </template>
                    <div class="flex justify-between font-bold text-slate-800 text-sm border-t border-slate-200 pt-1.5 mt-1.5">
                        <span>TOTAL</span>
                        <span x-text="formatCurrency(lastTotal)"></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span x-text="lastPaymentMethod === 'cash' ? 'Bayar (Tunai)' : 'Bayar (QRIS)'"></span>
                        <span x-text="formatCurrency(lastPaid)"></span>
                    </div>
                    <template x-if="lastPaymentMethod === 'cash'">
                        <div class="flex justify-between font-bold text-emerald-700 text-sm">
                            <span>Kembalian</span>
                            <span x-text="formatCurrency(lastChange)"></span>
                        </div>
                    </template>
                </div>

                <div class="border-t border-dashed border-slate-300 mt-4 mb-4"></div>

                {{-- Footer --}}
                <div class="text-center">
                    <p class="text-xs font-semibold text-slate-700">*** Terima Kasih ***</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Barang yang sudah dibeli tidak dapat dikembalikan</p>
                    <p class="text-[10px] text-slate-400">Simpan struk ini sebagai bukti pembayaran</p>
                </div>

            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="shrink-0 border-t border-slate-100 px-6 py-4 flex gap-3 bg-white">
            <button @click="printReceipt"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-xl
                           bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm
                           transition-colors border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Struk
            </button>
            <button @click="closeModal"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-xl
                           bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm
                           transition-colors shadow-md shadow-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Transaksi Baru
            </button>
        </div>

    </div>
</div>

{{-- PRINT RECEIPT (hidden, only shown on print) --}}
<div id="print-receipt" class="hidden print-only">
    <style>
        @media print {
            body * { visibility: hidden !important; }
            #print-receipt, #print-receipt * { visibility: visible !important; }
            #print-receipt {
                display: block !important;
                position: absolute !important;
                top: 0 !important; left: 0 !important;
                width: {{ setting('receipt_format', '58mm') == '80mm' ? '80mm' : (setting('receipt_format') == 'A4' ? '210mm' : '58mm') }} !important;
                padding: 4mm !important;
                font-family: 'Courier New', monospace !important;
                font-size: 10pt !important;
                color: #000 !important;
                background: #fff !important;
            }
            .no-print { display: none !important; }
        }
    </style>
    <div style="text-align:center; font-family: 'Courier New', monospace; width: {{ setting('receipt_format', '58mm') == '80mm' ? '80mm' : (setting('receipt_format') == 'A4' ? '210mm' : '58mm') }}; margin:0 auto;">
        @if(setting('store_logo'))
            <div style="margin-bottom: 6px;">
                <img src="{{ asset('storage/' . setting('store_logo')) }}" style="max-height: 40px; width: auto; filter: grayscale(100%);">
            </div>
        @endif
        <div style="font-size:13pt; font-weight:900; letter-spacing:1px;" id="pr-store"></div>
        <div style="font-size:8pt; margin-top:2px;" id="pr-address"></div>
        <div style="font-size:8pt;" id="pr-phone"></div>
        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
        <div style="font-size:8pt; text-align:left;">
            <div>No : <span id="pr-invoice" style="font-weight:bold;"></span></div>
            <div>Tgl: <span id="pr-date"></span></div>
            <div>Kasir: {{ Auth::user()->name }}</div>
            <div>Metode: <span id="pr-method" style="font-weight:bold; text-transform:uppercase;"></span></div>
        </div>
        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
        <table style="width: 100%; font-size: 8pt; text-align: left; border-collapse: collapse;">
            <tbody id="pr-items"></tbody>
        </table>
        <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
        <table style="width: 100%; font-size: 8pt; text-align: left; border-collapse: collapse;">
            <tr>
                <td style="padding: 2px 0;">Subtotal</td>
                <td style="padding: 2px 0; text-align: right;" id="pr-subtotal"></td>
            </tr>
            <tr id="pr-tax-row" style="display: none;">
                <td style="padding: 2px 0;">Pajak (PPN <span id="pr-tax-percent"></span>%)</td>
                <td style="padding: 2px 0; text-align: right;" id="pr-tax-amount"></td>
            </tr>
            <tr style="font-weight: 900; font-size: 9pt; border-top: 1px solid #000;">
                <td style="padding: 4px 0;">TOTAL</td>
                <td style="padding: 4px 0; text-align: right;" id="pr-total"></td>
            </tr>
            <tr>
                <td style="padding: 2px 0;" id="pr-pay-label">Tunai</td>
                <td style="padding: 2px 0; text-align: right;" id="pr-paid"></td>
            </tr>
            <tr id="pr-change-wrapper" style="font-weight: 900;">
                <td style="padding: 2px 0;">Kembali</td>
                <td style="padding: 2px 0; text-align: right;" id="pr-change"></td>
            </tr>
        </table>
        <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
        <div style="font-size:8pt; font-weight:bold;">*** Terima Kasih ***</div>
        <div style="font-size:7pt; margin-top:4px;">Barang yang sudah dibeli tidak dapat dikembalikan</div>
        <div style="font-size:7pt;">Simpan struk ini sebagai bukti pembayaran</div>
    </div>
</div>
</div>
</template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('posApp', (products, categories) => ({
        products, categories,
        searchQuery: '',
        selectedCategory: null,
        cart: [],
        paidAmount: null,
        displayPaidAmount: '',
        isProcessing: false,
        showSuccessModal: false,
        lastInvoice: '', lastChange: 0, lastTotal: 0, lastPaid: 0,
        lastItems: [], lastDateTime: '', lastPaymentMethod: 'cash',
        currentPage: 1,
        perPage: 10,
        paymentMethod: 'cash',

        // ── Mobile UI State ──────────────────────────────────────
        mobileSearchOpen: false,
        cartDrawerOpen: false,
        tableName: 'POS Kasir',
        customerName: '',

        toggleCartDrawer(status) {
            this.cartDrawerOpen = status;
            if (status) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        },

        setPaymentMethod(method) {
            this.paymentMethod = method;
            if (method === 'qris') {
                this.paidAmount = this.totalAmount;
                this.displayPaidAmount = this.formatCurrency(this.totalAmount);
            } else {
                this.paidAmount = null;
                this.displayPaidAmount = '';
            }
        },

        get filteredProducts() {
            return this.products.filter(p => {
                const q = this.searchQuery.toLowerCase();
                const matchSearch = !q || p.name.toLowerCase().includes(q) || (p.sku && p.sku.toLowerCase().includes(q));
                const matchCat = !this.selectedCategory || (p.category && p.category.id === this.selectedCategory);
                return matchSearch && matchCat;
            });
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredProducts.length / this.perPage));
        },

        get pageStart() {
            if (this.filteredProducts.length === 0) return 0;
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get pageEnd() {
            return Math.min(this.currentPage * this.perPage, this.filteredProducts.length);
        },

        get paginatedProducts() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredProducts.slice(start, start + this.perPage);
        },

        get subtotalAmount() {
            return this.cart.reduce((t, i) => t + i.price * i.quantity, 0);
        },

        get taxPercent() {
            return parseFloat('{{ setting("default_tax", 0) }}');
        },

        get taxAmount() {
            return this.subtotalAmount * (this.taxPercent / 100);
        },

        get totalAmount() {
            return this.subtotalAmount + this.taxAmount;
        },

        get changeAmount() {
            if (!this.paidAmount) return 0;
            return this.paidAmount - this.totalAmount;
        },

        stockAlert: null,

        handleAddToCart(product) {
            if (product.stock <= 0) return;
            const existing = this.cart.find(i => i.product_id === product.id);
            const inCart = existing ? existing.quantity : 0;
            if (inCart >= product.stock) {
                // Flash visual alert on card
                this.stockAlert = product.id;
                setTimeout(() => { this.stockAlert = null; }, 1800);
                return;
            }
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.unshift({ product_id: product.id, name: product.name, price: product.selling_price, quantity: 1, max_stock: product.stock });
            }
        },

        addToCart(product) { this.handleAddToCart(product); },

        increaseQty(index) {
            const item = this.cart[index];
            if (item.quantity < item.max_stock) { item.quantity++; }
            else {
                // flash di cart item — cukup shake visual saja, tidak ada alert
                item._shake = true;
                setTimeout(() => { item._shake = false; }, 500);
            }
        },

        decreaseQty(index) { if (this.cart[index].quantity > 1) this.cart[index].quantity--; },
        removeFromCart(index) { this.cart.splice(index, 1); },

        updatePaidAmount(e) {
            let val = e.target.value.replace(/\D/g, '');
            this.paidAmount = val ? parseInt(val, 10) : null;
            const currency = '{{ setting("store_currency", "IDR") }}';
            let locale = 'id-ID';
            if (currency === 'USD') locale = 'en-US';
            else if (currency === 'SGD') locale = 'en-SG';
            else if (currency === 'EUR') locale = 'de-DE';
            this.displayPaidAmount = val ? new Intl.NumberFormat(locale).format(this.paidAmount) : '';
        },

        printReceipt() {
            // Populate print-only receipt
            const storeName = document.getElementById('pr-store');
            const address   = document.getElementById('pr-address');
            const phone     = document.getElementById('pr-phone');
            if (storeName) storeName.textContent = '{{ $branch->name ?? config("app.name") }}';
            if (address)   address.textContent   = '{{ $branch->address ?? "" }}';
            if (phone)     phone.textContent      = '{{ !empty($branch->phone) ? "Telp: ".$branch->phone : "" }}';
            
            document.getElementById('pr-invoice').textContent  = this.lastInvoice;
            document.getElementById('pr-date').textContent     = this.lastDateTime;
            document.getElementById('pr-method').textContent   = this.lastPaymentMethod === 'cash' ? 'cash' : 'qris';
            
            const subtotal = this.lastItems.reduce((s, i) => s + i.price * i.quantity, 0);
            const taxAmount = subtotal * (this.taxPercent / 100);
            
            document.getElementById('pr-subtotal').textContent = this.formatCurrency(subtotal);
            
            const taxRow = document.getElementById('pr-tax-row');
            if (this.taxPercent > 0) {
                taxRow.style.display = 'table-row';
                document.getElementById('pr-tax-percent').textContent = this.taxPercent;
                document.getElementById('pr-tax-amount').textContent = this.formatCurrency(taxAmount);
            } else {
                taxRow.style.display = 'none';
            }
            
            document.getElementById('pr-total').textContent    = this.formatCurrency(this.lastTotal);
            document.getElementById('pr-paid').textContent     = this.formatCurrency(this.lastPaid);
            document.getElementById('pr-change').textContent   = this.formatCurrency(this.lastChange);
            
            // Adjust print layout labels based on payment method
            const payLabel = document.getElementById('pr-pay-label');
            const changeWrapper = document.getElementById('pr-change-wrapper');
            if (payLabel) {
                payLabel.textContent = this.lastPaymentMethod === 'cash' ? 'Tunai' : 'QRIS';
            }
            if (changeWrapper) {
                changeWrapper.style.display = this.lastPaymentMethod === 'cash' ? 'table-row' : 'none';
            }

            // Build items
            const itemsEl = document.getElementById('pr-items');
            itemsEl.innerHTML = this.lastItems.map(i => {
                const sub = i.price * i.quantity;
                return `<tr><td colspan="3" style="font-weight:bold; padding-top:4px;">${i.name}</td></tr>`
                     + `<tr>`
                     + `<td style="width: 15%; padding-bottom:4px;">${i.quantity}x</td>`
                     + `<td style="width: 45%; padding-bottom:4px;">${this.formatCurrency(i.price)}</td>`
                     + `<td style="width: 40%; text-align: right; padding-bottom:4px;">${this.formatCurrency(sub)}</td>`
                     + `</tr>`;
            }).join('');
            
            // Beri waktu browser untuk merender HTML secara full sebelum membuka print dialog
            setTimeout(() => {
                window.print();
            }, 300);
        },

        async processCheckout() {
            const sendPaidAmount = this.paymentMethod === 'qris' ? this.totalAmount : this.paidAmount;
            if (!this.cart.length || (this.paymentMethod === 'cash' && (!sendPaidAmount || sendPaidAmount < this.totalAmount))) return;
            this.isProcessing = true;
            try {
                const res = await fetch('{{ route("transaction.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ 
                        items: this.cart.map(i => ({ product_id: i.product_id, quantity: i.quantity })), 
                        payment_method: this.paymentMethod,
                        paid_amount: sendPaidAmount 
                    })
                });
                const result = await res.json();
                if (result.success) {
                    this.lastInvoice  = result.invoice;
                    this.lastChange   = result.change;
                    this.lastTotal    = this.totalAmount;
                    this.lastPaid     = result.paid_amount;
                    this.lastPaymentMethod = result.payment_method;
                    this.lastItems    = JSON.parse(JSON.stringify(this.cart)); // snapshot before clear
                    this.lastDateTime = new Date().toLocaleString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
                    this.showSuccessModal = true;
                    this.cart.forEach(ci => {
                        const p = this.products.find(p => p.id === ci.product_id);
                        if (p) p.stock -= ci.quantity;
                    });
                    this.cart = []; 
                    this.paidAmount = null; 
                    this.displayPaidAmount = ''; 
                    this.searchQuery = '';
                    this.paymentMethod = 'cash'; // reset for next transaction
                } else { alert('Gagal: ' + result.message); }
            } catch (e) { alert('Terjadi kesalahan koneksi.'); console.error(e); }
            finally { this.isProcessing = false; }
        },

        closeModal() { this.showSuccessModal = false; },

        formatCurrency(n) {
            const currency = '{{ setting("store_currency", "IDR") }}';
            let locale = 'id-ID';
            let decimals = 0;
            if (currency === 'USD') { locale = 'en-US'; decimals = 2; }
            else if (currency === 'SGD') { locale = 'en-SG'; decimals = 2; }
            else if (currency === 'EUR') { locale = 'de-DE'; decimals = 2; }
            return new Intl.NumberFormat(locale, { style: 'currency', currency: currency, maximumFractionDigits: decimals }).format(n);
        }
    }));
});
</script>
</x-app-layout>
