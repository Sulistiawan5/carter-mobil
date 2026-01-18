<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full group">
    
    <div class="relative overflow-hidden">
        <img src="<?= $row['gambar']; ?>" 
             alt="<?= $row['nama_mobil']; ?>" 
             class="w-full h-48 sm:h-56 object-cover transform group-hover:scale-110 transition duration-500">
        
        <div class="absolute top-3 right-3">
            <?php if($row['status'] == 'tersedia'): ?>
                <span class="bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                    Tersedia
                </span>
            <?php else: ?>
                <span class="bg-red-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                    Dipinjam
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div class="p-5 flex flex-col flex-grow">
        <div class="mb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 line-clamp-1" title="<?= $row['nama_mobil']; ?>">
                <?= $row['nama_mobil']; ?>
            </h3>
            <p class="text-blue-600 font-bold text-base sm:text-lg mt-1">
                Rp <?= number_format($row['harga_per_hari'], 0, ',', '.'); ?> 
                <span class="text-gray-400 text-sm font-normal">/ hari</span>
            </p>
        </div>
        
        <div class="mt-auto">
            <?php if($row['status'] == 'tersedia'): ?>
                <a href="booking.php?id=<?= $row['id']; ?>" 
                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                   Sewa Sekarang
                </a>
            <?php else: ?>
                <button disabled class="block w-full text-center bg-gray-200 text-gray-400 font-semibold py-3 rounded-lg cursor-not-allowed">
                    Sedang Dipinjam
                </button>
            <?php endif; ?>

            <?php if(isset($isAdmin) && $isAdmin): ?>
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end items-center text-sm">
                    <div class="flex gap-3">
                        <a href="admin_edit.php?id=<?= $row['id']; ?>" class="text-yellow-600 hover:text-yellow-700 font-medium hover:underline">Edit</a>
                        <a href="admin_hapus.php?id=<?= $row['id']; ?>" class="text-red-600 hover:text-red-700 font-medium hover:underline" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>