<?php
class Buku {
    private string $kodeBuku;
    private string $judul;
    private int $stok;

    public function __construct(string $kodeBuku, string $judul, int $stokAwal = 0) {
        $this->kodeBuku = trim($kodeBuku); 
        $this->setJudul($judul);
        
        if ($stokAwal < 0) {
            throw new InvalidArgumentException("Stok awal tidak boleh negatif.");
        }
        $this->stok = $stokAwal;
    }

    public function getKodeBuku(): string {
        return $this->kodeBuku;
    }

    public function getJudul(): string {
        return $this->judul;
    }

    public function setJudul(string $judul): void {
        $judul = trim($judul);
        if ($judul === "") {
            throw new InvalidArgumentException("Judul buku wajib diisi.");
        }
        $this->judul = $judul;
    }

    public function getStok(): int {
        return $this->stok;
    }

    public function tambahStok(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah penambahan stok harus lebih dari 0.");
        }
        $this->stok += $jumlah;
    }

    public function kurangiStok(int $jumlah): void {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah pengurangan stok harus lebih dari 0.");
        }
        if ($this->stok - $jumlah < 0) {
            throw new InvalidArgumentException("Stok tidak mencukupi untuk dikurangi. Stok saat ini: " . $this->stok);
        }
        $this->stok -= $jumlah;
    }
}

echo "<b>--- 2 PENGUJIAN VALID ---</b><br>";

$buku1 = new Buku("B001", "Pemrograman PHP 8", 10);
echo "Valid 1 (Sukses) -> Buku: " . $buku1->getKodeBuku() . " - " . $buku1->getJudul() . " (Stok awal: " . $buku1->getStok() . ")<br>";

$buku1->tambahStok(5);
$buku1->kurangiStok(3);
echo "Valid 2 (Sukses) -> Stok akhir setelah transaksi normal: " . $buku1->getStok() . "<br><br>";


echo "<b>--- 3 PENGUJIAN TIDAK VALID ---</b><br>";

try {
    $buku1->setJudul("");
} catch (Exception $e) {
    echo "Tidak Valid 1 (Ditolak) -> " . $e->getMessage() . "<br>";
}

try {
    $buku1->kurangiStok(100);
} catch (Exception $e) {
    echo "Tidak Valid 2 (Ditolak) -> " . $e->getMessage() . "<br>";
}

try {
    $buku1->tambahStok(-5);
} catch (Exception $e) {
    echo "Tidak Valid 3 (Ditolak) -> " . $e->getMessage() . "<br>";
}
?>