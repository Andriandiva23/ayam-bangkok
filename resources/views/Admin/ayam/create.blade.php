@extends('layouts.admin')



@section('content')

<div class="p-6 max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold text-gray-800">Tambah Data Ayam</h2>

        <a href="{{ route('admin.ayam.index') }}" class="text-gray-500 hover:text-gray-700">

            <i class="fas fa-arrow-left"></i> Kembali

        </a>

    </div>



    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <form action="{{ route('admin.ayam.store') }}" method="POST" enctype="multipart/form-data">

            @csrf



            <div class="mb-4">

                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_ayam">Nama Ayam</label>

                <input type="text" name="nama_ayam" id="nama_ayam" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required placeholder="Contoh: Pakhoy Super Import">

            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="link_video">Link Video YouTube</label>
                    <input type="text" name="link_video" id="link_video" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Contoh: https://youtube.com/watch?v=...">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
            </div>



            <div class="grid grid-cols-2 gap-4 mb-4">

                <div>

                    <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">Harga (Rp)</label>

                    <input type="number" name="harga" id="harga" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required placeholder="Contoh: 2500000">

                </div>

                <div>

                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stok">Stok (Ekor)</label>

                    <input type="number" name="stok" id="stok" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" required placeholder="Contoh: 10">

                </div>

            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="berat">Berat (Kg)</label>
                    <input type="text" name="berat" id="berat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Contoh: 3.2 Kg">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="ukuran">Ukuran</label>
                    <input type="text" name="ukuran" id="ukuran" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Contoh: Ukuran 6 / Besar">
                </div>
            </div>



            <div class="mb-4">

                <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi</label>

                <textarea name="deskripsi" id="deskripsi" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Keterangan fisik, usia, atau asal usul ayam..."></textarea>

            </div>



            <div class="mb-6">

                <label class="block text-gray-700 text-sm font-bold mb-2" for="foto">Foto Ayam</label>

                <input type="file" name="foto" id="foto" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500" accept="image/*">

                <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>

            </div>



            <div class="flex items-center justify-end">

                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-6 rounded shadow">

                    Simpan Data

                </button>

            </div>

        </form>

    </div>

</div>

@endsection