<?php
require_once 'Mahasiswa.php';
require_once 'DataMahasiswa.php';
session_start();

function aman(string|int $data): string {
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

$repo = new DataMahasiswa();
$error = '';
$sukses = '';
$editData = null;
$modeEdit = false;

if (isset($_GET['hapus'])) {
    $repo->hapus($_GET['hapus']);
    header("Location: index.php");
    exit;
}

if (isset($_GET['edit'])) {
    $editData = $repo->cari($_GET['edit']);
    if ($editData) {
        $modeEdit = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isEditPost = isset($_POST['is_edit']) && $_POST['is_edit'] === '1';
    
    try {
        $m = new Mahasiswa(
            trim((string) ($_POST['nim'] ?? '')),
            trim((string) ($_POST['nama'] ?? '')),
            trim((string) ($_POST['fakultas'] ?? '')),
            trim((string) ($_POST['prodi'] ?? '')),
            trim((string) ($_POST['asal_sekolah'] ?? '')),
            (int)($_POST['semester'] ?? 0),
            trim((string) ($_POST['hobi'] ?? '')),
            trim((string) ($_POST['agama'] ?? ''))
        );
        
        $repo->simpan($m, $isEditPost);
        $sukses = $isEditPost ? "Data berhasil diupdate!" : "Data berhasil ditambah!";
        $modeEdit = false; 
        $editData = null;
        
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
        $modeEdit = $isEditPost;
    }
}

$semuaData = $repo->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mahasiswa - Enkapsulasi</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        h2 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            font-size: 1.4em;
        }
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-weight: 500;
        }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: 600; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: inherit;
        }
        input[readonly] { background-color: #e9ecef; cursor: not-allowed; color: #6c757d; }
        
        .btn-group { display: flex; gap: 10px; margin-top: 20px; }
        button, .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
            text-decoration: none;
            text-align: center;
        }
        .btn-primary { background-color: #3498db; color: white; width: 100%; }
        .btn-primary:hover { background-color: #2980b9; }
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-danger:hover { background-color: #c0392b; }
        .btn-secondary { background-color: #95a5a6; color: white; width: 100%; display: inline-block;}
        .btn-secondary:hover { background-color: #7f8c8d; }
        .btn-sm { padding: 6px 10px; font-size: 0.85em; margin-right: 2px;}

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th { background-color: #f8f9fa; color: #2c3e50; }
        tr:hover { background-color: #f1f4f6; }
        
        .table-responsive { overflow-x: auto; }

        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2><?= $modeEdit ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h2>
            
            <?php if ($error): ?> <div class="alert alert-error"><?= aman($error) ?></div> <?php endif; ?>
            <?php if ($sukses): ?> <div class="alert alert-success"><?= aman($sukses) ?></div> <?php endif; ?>

            <form method="POST" action="index.php">
                <input type="hidden" name="is_edit" value="<?= $modeEdit ? '1' : '0' ?>">
                
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" value="<?= aman($editData?->getNim() ?? ($_POST['nim'] ?? '')) ?>" 
                        <?= $modeEdit ? 'readonly' : '' ?> placeholder="Masukkan NIM">
                </div>
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= aman($editData?->getNama() ?? ($_POST['nama'] ?? '')) ?>" placeholder="Masukkan Nama">
                </div>

                <div class="form-group">
                    <label>Fakultas</label>
                    <input type="text" name="fakultas" value="<?= aman($editData?->getFakultas() ?? ($_POST['fakultas'] ?? '')) ?>" placeholder="Fakultas">
                </div>

                <div class="form-group">
                    <label>Program Studi</label>
                    <input type="text" name="prodi" value="<?= aman($editData?->getProdi() ?? ($_POST['prodi'] ?? '')) ?>" placeholder="Program Studi">
                </div>

                <div class="form-group">
                    <label>Semester (1-14)</label>
                    <input type="number" name="semester" value="<?= aman($editData?->getSemester() ?? ($_POST['semester'] ?? '')) ?>" placeholder="1">
                </div>

                <div class="form-group">
                    <label>Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" value="<?= aman($editData?->getAsalSekolah() ?? ($_POST['asal_sekolah'] ?? '')) ?>" placeholder="Nama SMA/SMK">
                </div>

                <div class="form-group">
                    <label>Hobi</label>
                    <input type="text" name="hobi" value="<?= aman($editData?->getHobi() ?? ($_POST['hobi'] ?? '')) ?>" placeholder="Contoh: Membaca, Coding">
                </div>

                <div class="form-group">
                    <label>Agama</label>
                    <?php $agamaAktif = $editData?->getAgama() ?? ($_POST['agama'] ?? ''); ?>
                    <select name="agama">
                        <option value="">-- Pilih Agama --</option>
                        <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm): ?>
                            <option value="<?= $agm ?>" <?= $agamaAktif === $agm ? 'selected' : '' ?>><?= $agm ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><?= $modeEdit ? 'Update Data' : 'Simpan Data' ?></button>
                    <?php if($modeEdit): ?> 
                        <a href="index.php" class="btn btn-secondary" style="box-sizing: border-box;">Batal</a> 
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Data Mahasiswa (<?= count($semuaData) ?>)</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>NIM</th> 
                            <th>Nama</th> 
                            <th>Prodi</th> 
                            <th>Smt</th> 
                            <th>Agama</th> 
                            <th style="min-width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($semuaData)): ?>
                            <tr><td colspan="6" style="text-align:center; color:#7f8c8d;">Belum ada data tersimpan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($semuaData as $m): ?>
                            <tr>
                                <td><strong><?= aman($m->getNim()) ?></strong></td>
                                <td><?= aman($m->getNama()) ?></td>
                                <td><?= aman($m->getProdi()) ?></td>
                                <td><?= aman($m->getSemester()) ?></td>
                                <td><?= aman($m->getAgama()) ?></td>
                                <td>
                                    <a href="index.php?edit=<?= aman($m->getNim()) ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="index.php?hapus=<?= aman($m->getNim()) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data <?= aman($m->getNama()) ?>?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>