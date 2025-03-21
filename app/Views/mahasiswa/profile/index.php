<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="alert bg-gradient-info mb-3 text-white fw-bold fs-6">
                <i class="fa-solid fa-circle-info me-2"></i>segera update password default anda. abaikan informasi ini jika sudah melakukan update password.
            </div>
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Profil Mahasiswa</h5>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mx-4 mb-0 py-3 text-white fw-bold fs-6">
                        <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <form action="<?= base_url('mahasiswa/profil-mahasiswa/update') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="d-flex align-items-stretch">
                            <!-- Photo Section -->
                            <div class="photo-container text-center position-relative" style="cursor: pointer; width: 200px; flex-shrink: 0; height: 100%;">
                                <div style="position: relative; width: 100%; height: 0; padding-top: 133.33%; overflow: hidden;">
                                    <img src="<?= $student['photo'] ? base_url('uploads/photos/' . $student['photo']) : base_url('path/to/default-placeholder.png') ?>"
                                        id="profile-image"
                                        alt="Profile Photo"
                                        class="img-thumbnail"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                                </div>

                                <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white">
                                    <div class="mask bg-gradient-dark rounded-bottom">
                                        <p class="text-white text-xs m-0 p-2">Ubah Foto</p>
                                    </div>
                                </div>

                                <input type="file"
                                    class="d-none <?= (session()->has('errors') && isset(session('errors')['photo'])) ? 'is-invalid' : '' ?>"
                                    id="photo"
                                    name="photo"
                                    accept="image/*">

                                <?php if (session()->has('errors') && isset(session('errors')['photo'])): ?>
                                    <div class="text-danger" style="font-size: 0.75rem;">
                                        <?= session('errors')['photo'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Form Fields to the right of the photo -->
                            <div class="flex-grow-1 ms-3">
                                <!-- Row 1: Full Name -->
                                <div class="mb-3">
                                    <div class="form-group mb-0">
                                        <label for="name">Nama Lengkap</label>
                                        <input type="text"
                                            class="form-control <?= (session()->has('errors') && isset(session('errors')['name'])) ? 'is-invalid' : '' ?>"
                                            id="name"
                                            name="name"
                                            value="<?= old('name', $student['name']) ?>"
                                            placeholder="Masukkan nama lengkap"
                                            required>
                                        <?php if (session()->has('errors') && isset(session('errors')['name'])): ?>
                                            <div class="text-danger" style="font-size: 0.75rem;">
                                                <?= session('errors')['name'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Row 2: NIM and Email -->
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1 me-2">
                                        <div class="form-group mb-0">
                                            <label for="nim">NIM</label>
                                            <input type="text"
                                                class="form-control"
                                                id="nim"
                                                name="nim"
                                                value="<?= old('nim', $student['nim']) ?>"
                                                placeholder="Masukkan NIM"
                                                required disabled>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-group mb-0">
                                            <label for="email">Email</label>
                                            <input type="email"
                                                class="form-control <?= (session()->has('errors') && isset(session('errors')['email'])) ? 'is-invalid' : '' ?>"
                                                id="email"
                                                name="email"
                                                value="<?= old('email', $student['email']) ?>"
                                                placeholder="contoh@domain.com"
                                                required disabled>
                                            <!-- Hidden input for email -->
                                            <input type="hidden" name="email" value="<?= esc(old('email', $student['email'])) ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 3: University and Year -->
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1 me-2">
                                        <div class="form-group">
                                            <label for="university">Universitas</label>
                                            <input type="text"
                                                class="form-control <?= (session()->has('errors') && isset(session('errors')['university'])) ? 'is-invalid' : '' ?>"
                                                id="university"
                                                name="university"
                                                value="<?= old('university', $student['university']) ?>"
                                                placeholder="Masukkan nama universitas"
                                                required>
                                            <?php if (session()->has('errors') && isset(session('errors')['university'])): ?>
                                                <div class="text-danger" style="font-size: 0.75rem;">
                                                    <?= session('errors')['university'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="form-group">
                                            <label for="year">Angkatan</label>
                                            <input type="number"
                                                class="form-control <?= (session()->has('errors') && isset(session('errors')['year'])) ? 'is-invalid' : '' ?>"
                                                id="year"
                                                name="year"
                                                value="<?= old('year', $student['year']) ?>"
                                                placeholder="Masukkan tahun angkatan"
                                                max="<?= date('Y') ?>"
                                                required>
                                            <?php if (session()->has('errors') && isset(session('errors')['year'])): ?>
                                                <div class="text-danger" style="font-size: 0.75rem;">
                                                    <?= session('errors')['year'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rest of the form (below the photo and top fields) -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="date_of_birth">Tanggal Lahir</label>
                                    <input type="date"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['date_of_birth'])) ? 'is-invalid' : '' ?>"
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        value="<?= old('date_of_birth', $student['date_of_birth']) ?>"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['date_of_birth'])): ?>
                                        <div class="text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['date_of_birth'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 ps-0">
                                <div class="form-group">
                                    <label for="place_of_birth">Tempat Lahir</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['place_of_birth'])) ? 'is-invalid' : '' ?>"
                                        id="place_of_birth"
                                        name="place_of_birth"
                                        value="<?= old('place_of_birth', $student['place_of_birth']) ?>"
                                        placeholder="Masukkan tempat lahir"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['place_of_birth'])): ?>
                                        <div class="text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['place_of_birth'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['phone'])) ? 'is-invalid' : '' ?>"
                                        id="phone"
                                        name="phone"
                                        value="<?= old('phone', $student['phone']) ?>"
                                        placeholder="Masukkan nomor telepon"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['phone'])): ?>
                                        <div class="text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['phone'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 ps-0">
                                <div class="form-group">
                                    <label for="mobile_no">Nomor HP</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['mobile_no'])) ? 'is-invalid' : '' ?>"
                                        id="mobile_no"
                                        name="mobile_no"
                                        value="<?= old('mobile_no', $student['mobile_no']) ?>"
                                        placeholder="Masukkan nomor HP">
                                    <?php if (session()->has('errors') && isset(session('errors')['mobile_no'])): ?>
                                        <div class="text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['mobile_no'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['gender'])) ? 'is-invalid' : '' ?>"
                                        id="gender"
                                        name="gender"
                                        required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Male" <?= old('gender', $student['gender']) == 'Male' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Female" <?= old('gender', $student['gender']) == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['gender'])): ?>
                                        <div class="text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['gender'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 ps-0">
                                <div class="form-group">
                                    <label for="religion">Agama</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['religion'])) ? 'is-invalid' : '' ?>"
                                        id="religion"
                                        name="religion"
                                        required>
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="Islam" <?= old('religion', $student['religion']) == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                        <option value="Protestan" <?= old('religion', $student['religion']) == 'Protestan' ? 'selected' : '' ?>>Protestan</option>
                                        <option value="Katolik" <?= old('religion', $student['religion']) == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                        <option value="Hindu" <?= old('religion', $student['religion']) == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                        <option value="Buddha" <?= old('religion', $student['religion']) == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                        <option value="Konghucu" <?= old('religion', $student['religion']) == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                                    </select>

                                    <?php if (session()->has('errors') && isset(session('errors')['religion'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['religion'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 ps-0">
                                <div class="form-group">
                                    <label for="blood_group">Golongan Darah</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['blood_group'])) ? 'is-invalid' : '' ?>"
                                        id="blood_group"
                                        name="blood_group"
                                        required>
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="A" <?= old('blood_group', $student['blood_group']) == 'A' ? 'selected' : '' ?>>A</option>
                                        <option value="B" <?= old('blood_group', $student['blood_group']) == 'B' ? 'selected' : '' ?>>B</option>
                                        <option value="AB" <?= old('blood_group', $student['blood_group']) == 'AB' ? 'selected' : '' ?>>AB</option>
                                        <option value="O" <?= old('blood_group', $student['blood_group']) == 'O' ? 'selected' : '' ?>>O</option>
                                        <option value="Unknown" <?= old('blood_group', $student['blood_group']) == 'Unknown' ? 'selected' : '' ?>>Tidak Tahu</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['blood_group'])): ?>
                                        <div class="text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['blood_group'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address">Alamat</label>
                            <textarea class="form-control <?= (session()->has('errors') && isset(session('errors')['address'])) ? 'is-invalid' : '' ?>"
                                id="address"
                                name="address"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                required><?= old('address', $student['address']) ?></textarea>
                            <?php if (session()->has('errors') && isset(session('errors')['address'])): ?>
                                <div class="text-danger" style="font-size: 0.75rem;">
                                    <?= session('errors')['address'] ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Tombol Submit dan Kembali -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn bg-gradient-success me-1">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('mahasiswa/profil-mahasiswa/update-password') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group mb-3">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn bg-gradient-success">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk membuat foto dapat diklik -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoContainer = document.querySelector('.photo-container');
        const fileInput = document.getElementById('photo');

        photoContainer.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const profileImage = document.getElementById('profile-image');
                    profileImage.src = e.target.result;
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
<?= $this->endSection(); ?>