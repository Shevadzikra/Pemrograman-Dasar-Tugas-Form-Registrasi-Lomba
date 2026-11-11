
<?php
$data = [
    'nama' => '',
    'email' => '',
    'no_hp' => '',
    'umur' => '',
    'github' => '',
    'kategori' => '',
    'peserta' => '',
];

$errors = [];

$success = false;

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

$kategori_valid = [
    'Web Development',
    'Mobile Development',
    'UI/UX Design',
    'Data Science'
];

$peserta_valid = [
    'Individu',
    'Kelompok'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    // 1. validasi nama
    if ($data['nama'] === '') {
        $errors['nama'] = 'Nama wajib diisi.';
    } elseif (strlen($data['nama']) < 3 || strlen($data['nama']) > 50) {
        $errors['nama'] = 'Nama harus 3-50 karakter.';
    }

    // 2. validasi email
    if ($data['email'] === '') {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }

    // 3. Validasi nomor HP
    if ($data['no_hp'] === '') {
        $errors['no_hp'] = 'Nomor HP wajib diisi.';
    } elseif (!preg_match('/^08[0-9]{8,11}$/', $data['no_hp'])) {
        $errors['no_hp'] = 'Nomor HP harus diawali 08 dan terdiri dari 10-13 digit.';
    }

    // 4. validasi umur
    if ($data['umur'] === '') {
        $errors['umur'] = 'Umur wajib diisi.';
    } elseif (!filter_var($data['umur'], FILTER_VALIDATE_INT)) {
        $errors['umur'] = 'Umur harus berupa angka bulat.';
    } elseif ($data['umur'] < 17 || $data['umur'] > 19) {
        $errors['umur'] = 'Maaf, umur peserta harus antara 17-19 tahun.';
    }

    // 5. validasi link github
    if ($data['github'] !== '') {
        if (!filter_var($data['github'], FILTER_VALIDATE_URL)) {
            $errors['github'] = 'URL GitHub tidak valid.';
        } elseif (
            parse_url($data['github'], PHP_URL_HOST) !== 'github.com' &&
            parse_url($data['github'], PHP_URL_HOST) !== 'www.github.com'
        ) {
            $errors['github'] = 'URL harus berasal dari github.com.';
        }
    }

    // 6. validasi kategori
    if (!in_array($data['kategori'], $kategori_valid, true)) {
        $errors['kategori'] = 'Silakan pilih kategori yang tersedia.';
    }

    // 7. validasi jenis peserta
    if (!in_array($data['peserta'], $peserta_valid, true)) {
        $errors['peserta'] = 'Silakan pilih jenis peserta.';
    }

    if (empty($errors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Lomba Coding</title>
</head>
<body>

    <h1>Registrasi Lomba Coding</h1>
    <p>Silakan isi formulir pendaftaran berikut.</p>

    <?php if ($success): ?>

        <h2>Registrasi Berhasil!</h2>
        <p>Data peserta berhasil divalidasi.</p>

        <h3>Data Peserta</h3>

        <p>Nama: <?= e($data['nama']) ?></p>
        <p>Email: <?= e($data['email']) ?></p>
        <p>Nomor HP: <?= e($data['no_hp']) ?></p>
        <p>Umur: <?= e($data['umur']) ?> tahun</p>
        <p>GitHub: <?= e($data['github']) ?></p>
        <p>Kategori: <?= e($data['kategori']) ?></p>
        <p>Jenis Peserta: <?= e($data['peserta']) ?></p>

    <?php else: ?>
        <form method="POST" action="">
            <label>Nama Lengkap</label><br>
            <input type="text" name="nama" value="<?= e($data['nama']) ?>">
            <p style="color:red">
                <?= e($errors['nama'] ?? '') ?>
            </p>

            <label>Email</label><br>
            <input type="text" name="email" value="<?= e($data['email']) ?>">
            <p style="color:red">
                <?= e($errors['email'] ?? '') ?>
            </p>

            <label>Nomor HP</label><br>
            <input type="text" name="no_hp" value="<?= e($data['no_hp']) ?>">
            <p style="color:red">
                <?= e($errors['no_hp'] ?? '') ?>
            </p>

            <label>Umur</label><br>
            <input type="number" name="umur" value="<?= e($data['umur']) ?>">
            <p style="color:red">
                <?= e($errors['umur'] ?? '') ?>
            </p>

            <label>URL GitHub (Opsional)</label><br>
            <input type="text" name="github" value="<?= e($data['github']) ?>" placeholder="https://github.com/username">
            <p style="color:red">
                <?= e($errors['github'] ?? '') ?>
            </p>

            <label>Kategori Lomba</label><br>
            <select name="kategori">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori_valid as $kategori): ?>
                    <option value="<?= e($kategori) ?>"
                        <?= $data['kategori'] === $kategori ? 'selected' : '' ?>
                    >
                        <?= e($kategori) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <p style="color:red">
                <?= e($errors['kategori'] ?? '') ?>
            </p>

            <label>Jenis Peserta</label><br>
            <?php foreach ($peserta_valid as $peserta): ?>
                <input type="radio" name="peserta" value="<?= e($peserta) ?>"
                    <?= $data['peserta'] === $peserta ? 'checked' : '' ?>
                >
                <?= e($peserta) ?>
            <?php endforeach; ?>

            <p style="color:red">
                <?= e($errors['peserta'] ?? '') ?>
            </p>

            <br>
            <button type="submit">Daftar Sekarang</button>
        </form>
    <?php endif; ?>

</body>
</html>
