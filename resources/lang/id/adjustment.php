<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Adjustment Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during adjustment for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    //==========================================
    // Adjustment module messages
    //==========================================
    'title' => 'Penyesuaian Stok',
    'Adjustments' => 'Penyesuaian Stok',
    'Create_Adjustment' => 'Buat Penyesuaian',
    'Edit_Adjustment' => 'Edit Penyesuaian',
    'Adjustment_Details' => 'Detail Penyesuaian',
    'Adjustment_List' => 'Daftar Penyesuaian',
    'sure' => 'Apakah Anda yakin?',

    //==========================================
    // Error handling messages
    //==========================================
    'error_creating' => 'Maaf, terjadi masalah saat membuat penyesuaian.',
    'success_creating' => 'Berhasil, penyesuaian Anda telah dibuat.',
    'error_updating' => 'Maaf, terjadi masalah saat memperbarui penyesuaian.',
    'success_updating' => 'Berhasil, penyesuaian Anda telah diperbarui.',
    'error_deleting' => 'Maaf, terjadi masalah saat menghapus penyesuaian.',
    'success_deleting' => 'Berhasil, penyesuaian Anda telah dihapus.',

    //==========================================
    // Adjustment table messages
    //==========================================
    'ID' => 'ID',
    'Product' => 'Produk',
    'Type' => 'Jenis',
    'Quantity' => 'Jumlah',
    'Reason' => 'Alasan',
    'Adjusted_By' => 'Disesuaikan Oleh',
    'Adjusted_At' => 'Waktu Penyesuaian',
    'Select_Product' => 'Pilih Produk',
    'Select_Type' => 'Pilih Jenis',
    'Increase' => 'Penambahan',
    'Decrease' => 'Pengurangan',
    'Enter_reason' => 'Masukkan alasan penyesuaian...',
    'No_reason_provided' => 'Tidak ada alasan',

    //==========================================
    // Filter and Search messages
    //==========================================
    'Search_Adjustments' => 'Cari Penyesuaian',
    'Filter_Type' => 'Filter Jenis',
    'Filter_Date_From' => 'Tanggal Dari',
    'Filter_Date_To' => 'Tanggal Sampai',
    'All_Types' => 'Semua Jenis',
    'Search_placeholder' => 'Cari berdasarkan nama produk, alasan, atau user...',

    //==========================================
    // Action messages
    //==========================================
    'Actions' => 'Aksi',
    'really_delete' => 'Apakah Anda yakin ingin menghapus penyesuaian ini?',
    'delete_confirmation' => 'Anda akan menghapus penyesuaian :type sebanyak :quantity unit untuk :product',
    'yes_delete' => 'Ya, hapus!',
    'No' => 'Tidak',
    'Yes' => 'Ya',
    'deleted' => 'Berhasil Dihapus',
    'deleted_message' => 'Penyesuaian Berhasil Dihapus',

    //==========================================
    // Empty state messages
    //==========================================
    'No_Adjustments_Found' => 'Tidak Ada Penyesuaian Ditemukan',
    'Empty_State_Description' => 'Mulai dengan membuat penyesuaian stok pertama Anda.',

    //==========================================
    // Validation messages
    //==========================================
    'Please_add_at_least_one_item' => 'Harap tambahkan setidaknya satu item ke penyesuaian.',
    'product_id_required' => 'Produk wajib dipilih',
    'type_required' => 'Jenis penyesuaian wajib dipilih',
    'type_in' => 'Jenis harus berupa penambahan atau pengurangan',
    'quantity_required' => 'Jumlah wajib diisi',
    'quantity_min' => 'Jumlah minimal 1',
    'adjusted_at_required' => 'Tanggal penyesuaian wajib diisi',
    'adjusted_at_date' => 'Tanggal penyesuaian harus berupa tanggal yang valid',
    'Quantity_Hint' => 'Masukkan jumlah yang akan disesuaikan. Untuk pengurangan, jumlah tidak boleh melebihi stok saat ini.',
    'Current_Stock' => 'Stok Saat Ini',
    'Additional_Information' => 'Informasi Tambahan',

    //==========================================
    // Stock update messages
    //==========================================
    'stock_increased' => 'Stok bertambah :quantity unit',
    'stock_decreased' => 'Stok berkurang :quantity unit',
    'stock_updated' => 'Stok berhasil diperbarui',
];