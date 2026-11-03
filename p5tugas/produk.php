<?php
class Produk {
    public string $nama;
    protected string $kategori;
    private int $harga;

    public function __construct(string $nama, string $kategori, int $harga) {
        $this->nama = $nama;
        $this->kategori = $kategori;
        $this->harga = $harga;
    }
}

class ProdukDigital extends Produk {
    public function tampilkanKategori(): string {
        // BERHASIL: Subclass (class turunan) bisa mengakses properti protected dari class induk
        return "Kategori produk ini adalah: " . $this->kategori;
    }
    
    // public function tampilkanHarga() {
    //     return $this->harga;
    // }
}


$produk1 = new ProdukDigital("E-Book Pemrograman PHP", "Buku Digital", 50000);

// 1. Menguji Akses PUBLIC dari luar class
echo "Nama Produk: " . $produk1->nama . "<br>"; 

// 2. Menguji Akses PROTECTED melalui method di subclass
echo $produk1->tampilkanKategori() . "<br>";

// 3. Menguji Akses PROTECTED dari luar class secara langsung
// echo $produk1->kategori; 

// 4. Menguji Akses PRIVATE dari luar class secara langsung
// echo $produk1->harga; 