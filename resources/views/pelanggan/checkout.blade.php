<form action="{{ route('checkout.process') }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    @csrf
    <input type="hidden" name="ayam_id" value="{{ $ayam->id }}">
    <input type="hidden" name="qty" value="{{ $qty }}">
    
    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Data Pengiriman</h3>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
        <input type="text" name="nama_penerima" required 
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none transition">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">No HP (WhatsApp)</label>
        <input type="text" name="no_hp" required placeholder="Contoh: 08123456789"
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none transition">
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
        <textarea name="alamat_lengkap" required rows="3"
            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none transition"></textarea>
    </div>

    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pengiriman</label>
        <select name="metode_pengiriman" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none transition">
            <option value="JNE">JNE Express</option>
            <option value="J&T">J&T Express</option>
            <option value="Travel">Travel (Cepat)</option>
            <option value="Ambil Sendiri">Ambil Sendiri (Self Pickup)</option>
        </select>
    </div>

    <button type="submit" 
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition duration-200">
        Lanjut ke Pembayaran
    </button>
</form>