<?php
require_once 'Mahasiswa.php';

class DataMahasiswa {
    private array $data = [];

    public function __construct() {
        if (isset($_SESSION['data_mhs_tugas3'])) {
            $this->data = $_SESSION['data_mhs_tugas3'];
        }
    }

    private function simpanKeSession(): void {
        $_SESSION['data_mhs_tugas3'] = $this->data;
    }

    public function simpan(Mahasiswa $m, bool $isEdit = false): void {
        $nim = $m->getNim();
        
        if (!$isEdit && isset($this->data[$nim])) {
            throw new InvalidArgumentException("NIM '{$nim}' sudah terdaftar!");
        }
        
        $this->data[$nim] = $m;
        $this->simpanKeSession();
    }

    public function hapus(string $nim): void {
        if (isset($this->data[$nim])) {
            unset($this->data[$nim]);
            $this->simpanKeSession();
        }
    }

    public function cari(string $nim): ?Mahasiswa {
        return $this->data[$nim] ?? null;
    }

    public function getAll(): array {
        return $this->data;
    }
}