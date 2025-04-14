<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <span class="fs-4">👋</span>
    <h4 class="d-inline-block text-info text-gradient mb-4 text-bolder" role="alert">
        Selamat datang, <?= esc($mahasiswa['name'] ?? ''); ?>!
    </h4>

    <div class="card mb-4 overflow-hidden position-relative border-radius-lg" style="background-image: url('<?= base_url(); ?>/assets/img/curved-images/white-curved.jpg'); background-size: cover;">
        <span class="mask bg-gradient-info"></span>

        <div class="card-header pb-0 d-flex justify-content-between align-items-center z-index-1 bg-transparent">
            <h5 class="text-white">Profil Mahasiswa</h5>
        </div>

        <div class="card-body text-white z-index-1">
            <div class="row">
                <!-- Profile Column -->
                <div class="col-lg-4 mb-4 mb-lg-0 d-flex flex-column justify-content-between">
                    <div class="text-center">
                        <div class="avatar-section mt-2">
                            <img src="<?= $mahasiswa['photo'] ? base_url('uploads/photos/' . $mahasiswa['photo']) : base_url('assets/img/default-avatar.jpg') ?>"
                                id="profile-image"
                                alt="Profile Photo"
                                class="rounded-circle"
                                style="width: 180px; height: 180px; object-fit: cover;">
                            <h5 class="mb-0 mt-3 fw-bold text-white">
                                <?= esc($mahasiswa['name'] ?? 'Nama Mahasiswa') ?>
                            </h5>
                            <span class="text-white text-md ">
                                <?= esc($mahasiswa['nim'] ?? 'NIM Belum Terdaftar') ?>
                            </span>
                        </div>
                    </div>
                    <div class="contact-info d-grid gap-2 mt-4 mb-2">
                        <a href="profil-mahasiswa" class="btn bg-gradient-success">
                            <div class="fas fa-edit fa-lg me-2"></div>Edit Profil
                        </a>
                        <a href="logbook/tambah-logbook" class="btn bg-gradient-primary">
                            <div class="fas fa-plus fa-lg me-2"></div>Tambah Logbook
                        </a>
                    </div>
                </div>

                <!-- Information Column -->
                <div class="col-lg-8">
                    <div class="row g-4">
                        <!-- Personal Data -->
                        <div class="col-md-6">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-id-card text-white rounded-circle me-2"></i>
                                Data Pribadi
                            </h6>
                            <div>
                                <p class="fw-bold mb-1">Tempat Tanggal Lahir</p>
                                <p class="mb-3"><?= esc(($mahasiswa['place_of_birth'] ?? '-') . ', ' . ($mahasiswa['date_of_birth'] ?? '-')) ?></p>

                                <p class="fw-bold mb-1">Jenis Kelamin</p>
                                <p class="mb-3"><?= esc($mahasiswa['gender'] ?? '-') ?></p>
                            </div>
                        </div>

                        <!-- Academic Data -->
                        <div class="col-md-6">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-university text-white rounded-circle me-2"></i>
                                Data Akademik
                            </h6>
                            <div>
                                <p class="fw-bold mb-1">Universitas</p>
                                <p class="mb-3"><?= esc($mahasiswa['university'] ?? '-') ?></p>

                                <p class="fw-bold mb-1">Tahun Angkatan</p>
                                <p class="mb-3"><?= esc($mahasiswa['year'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal light opacity-4">

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-address-book text-white rounded-circle me-2"></i>
                                Kontak dan Alamat
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <p class="fw-bold mb-1">Nomor HP</p>
                                    <p><?= esc($mahasiswa['mobile_no'] ?? '-') ?></p>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <p class="fw-bold mb-1">Nomor Telepon</p>
                                    <p><?= esc($mahasiswa['phone'] ?? '-') ?></p>
                                </div>
                                <div class="col-md-12 mb-1">
                                    <p class="fw-bold mb-1">Alamat</p>
                                    <p><?= esc($mahasiswa['address'] ?? '-') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Right Column -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>