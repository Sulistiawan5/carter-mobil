<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
    <img src="<?= $row['gambar']; ?>" class="w-full h-48 object-cover">
    <div class="p-5">
        <h3 class="text-xl font-bold mb-1"><?= $row['nama_mobil']; ?></h3>
        <p class="text-gray-500 text-sm mb-3">Rp <?= number_format($row['harga_per_hari']); ?> / hari</p>
        
        <div class="flex justify-between items-center">
            <?php if($row['status'] == 'tersedia'): ?>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Tersedia</span>
                <a href="booking.php?id=<?= $row['id']; ?>" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Sewa</a>
            <?php else: ?>
                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Dipinjam</span>
                <button disabled class="bg-gray-300 text-white px-3 py-1 rounded text-sm cursor-not-allowed">Sewa</button>
            <?php endif; ?>
        </div>

        <?php if($isAdmin): ?>
        <div class="mt-4 pt-3 border-t flex justify-end gap-2 text-sm">
            <a href="admin_edit.php?id=<?= $row['id']; ?>" class="text-yellow-600">Edit</a>
            <a href="admin_hapus.php?id=<?= $row['id']; ?>" class="text-red-600" onclick="return confirm('Hapus?');">Hapus</a>
        </div>
        <?php endif; ?>
    </div>
</div>