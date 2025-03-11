<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Tambah Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/mahasiswa-coass/store') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <!-- Baris 1: Nama dan Email -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['name'])) ? 'is-invalid' : '' ?>"
                                        id="name"
                                        name="name"
                                        value="<?= old('name') ?>"
                                        placeholder="Masukkan nama lengkap"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['name'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['name'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['email'])) ? 'is-invalid' : '' ?>"
                                        id="email"
                                        name="email"
                                        value="<?= old('email') ?>"
                                        placeholder="contoh@domain.com"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['email'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['email'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2: NIM dan Tanggal Lahir -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['nim'])) ? 'is-invalid' : '' ?>"
                                        id="nim"
                                        name="nim"
                                        value="<?= old('nim') ?>"
                                        placeholder="Masukkan NIM"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['nim'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['nim'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Tanggal Lahir</label>
                                    <input type="date"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['date_of_birth'])) ? 'is-invalid' : '' ?>"
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        value="<?= old('date_of_birth') ?>"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['date_of_birth'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['date_of_birth'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 3: Tempat Lahir dan Agama -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="place_of_birth">Tempat Lahir</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['place_of_birth'])) ? 'is-invalid' : '' ?>"
                                        id="place_of_birth"
                                        name="place_of_birth"
                                        value="<?= old('place_of_birth') ?>"
                                        placeholder="Masukkan tempat lahir"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['place_of_birth'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['place_of_birth'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="religion">Agama</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['religion'])) ? 'is-invalid' : '' ?>"
                                        id="religion"
                                        name="religion"
                                        required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="Islam" <?= old('religion') == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                        <option value="Protestan" <?= old('religion') == 'Kristen Protestan' ? 'selected' : '' ?>>Protestan</option>
                                        <option value="Katolik" <?= old('religion') == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                        <option value="Hindu" <?= old('religion') == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                        <option value="Buddha" <?= old('religion') == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                        <option value="Konghucu" <?= old('religion') == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                                    </select>

                                    <?php if (session()->has('errors') && isset(session('errors')['religion'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['religion'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <!-- Baris 4: Jenis Kelamin dan Golongan Darah -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['gender'])) ? 'is-invalid' : '' ?>"
                                        id="gender"
                                        name="gender"
                                        required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                                        <option value="Other" <?= old('gender') == 'Other' ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['gender'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['gender'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blood_group">Golongan Darah</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['blood_group'])) ? 'is-invalid' : '' ?>"
                                        id="blood_group"
                                        name="blood_group"
                                        required>
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="A" <?= old('blood_group') == 'A' ? 'selected' : '' ?>>A</option>
                                        <option value="B" <?= old('blood_group') == 'B' ? 'selected' : '' ?>>B</option>
                                        <option value="AB" <?= old('blood_group') == 'AB' ? 'selected' : '' ?>>AB</option>
                                        <option value="O" <?= old('blood_group') == 'O' ? 'selected' : '' ?>>O</option>
                                        <option value="Unknown" <?= old('blood_group') == 'Unknown' ? 'selected' : '' ?>>Tidak Tahu</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['blood_group'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['blood_group'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 5: Nomor Telepon dan HP -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['phone'])) ? 'is-invalid' : '' ?>"
                                        id="phone"
                                        name="phone"
                                        value="<?= old('phone') ?>"
                                        placeholder="Masukkan nomor telepon"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['phone'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['phone'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile_no">Nomor HP</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['mobile_no'])) ? 'is-invalid' : '' ?>"
                                        id="mobile_no"
                                        name="mobile_no"
                                        value="<?= old('mobile_no') ?>"
                                        placeholder="Masukkan nomor HP">
                                    <?php if (session()->has('errors') && isset(session('errors')['mobile_no'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['mobile_no'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 6: Alamat -->
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control <?= (session()->has('errors') && isset(session('errors')['address'])) ? 'is-invalid' : '' ?>"
                                id="address"
                                name="address"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                required><?= old('address') ?></textarea>
                            <?php if (session()->has('errors') && isset(session('errors')['address'])): ?>
                                <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                    <?= session('errors')['address'] ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Baris 7: Universitas dan Angkatan -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="university">Universitas</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['university'])) ? 'is-invalid' : '' ?>"
                                        id="university"
                                        name="university"
                                        value="<?= old('university') ?>"
                                        placeholder="Masukkan nama universitas"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['university'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['university'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year">Angkatan</label>
                                    <input type="number"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['year'])) ? 'is-invalid' : '' ?>"
                                        id="year"
                                        name="year"
                                        value="<?= old('year') ?>"
                                        placeholder="Masukkan tahun angkatan"
                                        min="2000"
                                        max="<?= date('Y') ?>"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['year'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['year'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 8: Upload Foto -->
                        <div class="form-group">
                            <label for="photo">Foto (Opsional)</label>
                            <input type="file"
                                class="form-control <?= (session()->has('errors') && isset(session('errors')['photo'])) ? 'is-invalid' : '' ?>"
                                id="photo"
                                name="photo"
                                accept="image/*">
                            <?php if (session()->has('errors') && isset(session('errors')['photo'])): ?>
                                <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                    <?= session('errors')['photo'] ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tombol Submit dan Kembali -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn bg-gradient-success me-1">Simpan</button>
                            <a href="<?= base_url('admin/mahasiswa-coass') ?>" class="btn bg-gradient-info">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>