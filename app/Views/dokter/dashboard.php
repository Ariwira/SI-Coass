<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <span class="fs-4">👋</span>
    <h4 class="d-inline-block text-info text-gradient mb-4 text-bolder" role="alert">
        Selamat datang, Dokter <?= esc($doctor['name'] ?? ''); ?>!
    </h4>
    <div class="card mb-4 overflow-hidden position-relative border-radius-lg" style="background-image: url('<?= base_url(); ?>/assets/img/curved-images/white-curved.jpg'); background-size: cover;">
        <span class="mask bg-gradient-info"></span>

        <div class="card-header pb-0 d-flex justify-content-between align-items-center z-index-1 bg-transparent">
            <h5 class="text-white">Profil Dokter</h5>
        </div>

        <div class="card-body text-white z-index-1">
            <div class="row">
                <!-- Profile Column -->
                <div class="col-lg-4 mb-4 mb-lg-0 d-flex flex-column justify-content-between">
                    <div class="text-center">
                        <div class="avatar-section mt-2">
                            <img src="<?= $doctor['photo'] ? base_url('uploads/photos/' . $doctor['photo']) : base_url('assets/img/default-avatar.jpg') ?>"
                                id="profile-image"
                                alt="Profile Photo"
                                class="rounded-circle"
                                style="width: 180px; height: 180px; object-fit: cover;">
                            <h5 class="mb-0 mt-3 fw-bold text-white">
                                <?= esc($doctor['name'] ?? 'Nama Dokter') ?>
                            </h5>
                            <span class="text-white text-md">
                                <?= esc($doctor['id_card'] ?? 'Nomor identitas Belum Terdaftar') ?>
                            </span>
                        </div>
                    </div>
                    <div class="contact-info d-grid gap-2 mt-4 mb-2">
                        <a href="profil-dokter" class="btn bg-gradient-success">
                            <div class="fas fa-edit fa-lg me-2"></div>Edit Profil
                        </a>
                        <a href="stase/tambah-stase" class="btn bg-gradient-primary">
                            <div class="fas fa-plus fa-lg me-2"></div>Tambah Stase
                        </a>
                    </div>
                </div>

                <!-- Information Column -->
                <div class="col-lg-8">
                    <div class="row g-4">
                        <!-- Data Pribadi -->
                        <div class="col-md-6">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-id-card text-white rounded-circle me-2"></i>
                                Data Pribadi
                            </h6>
                            <p class="fw-bold mb-1">Tempat, Tanggal Lahir</p>
                            <p class="mb-3"><?= esc(($doctor['place_of_birth'] ?? '-') . ', ' . ($doctor['date_of_birth'] ?? '-')) ?></p>

                            <p class="fw-bold mb-1">Jenis Kelamin</p>
                            <p class="mb-3"><?= esc($doctor['gender'] ?? '-') ?></p>
                        </div>

                        <!-- Data Pendidikan & Kualifikasi -->
                        <div class="col-md-6">
                            <h6 class="text-white mb-3 d-flex align-items-center">
                                <i class="fas fa-user-graduate text-white rounded-circle me-2"></i>
                                Data Pendidikan
                            </h6>
                            <p class="fw-bold mb-1">Kualifikasi</p>
                            <p class="mb-3"><?= esc($doctor['qualification'] ?? '-') ?></p>
                        </div>

                        <hr class="horizontal light opacity-4 my-4">

                        <!-- Kontak dan Alamat -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-white mb-3 d-flex align-items-center">
                                    <i class="fas fa-address-book text-white rounded-circle me-2"></i>
                                    Kontak dan Alamat
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <p class="fw-bold mb-1">Nomor HP</p>
                                        <p><?= esc($doctor['mobile_no'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <p class="fw-bold mb-1">Nomor Telepon</p>
                                        <p><?= esc($doctor['phone'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-12 mb-1">
                                        <p class="fw-bold mb-1">Alamat Lengkap</p>
                                        <p><?= esc($doctor['address'] ?? '-') ?></p>
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