<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Tambah Dokter</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/dokter/store') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Nama dan Email -->
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
                                        placeholder="Masukkan email"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['email'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['email'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Identitas dan Tanggal Lahir -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_card">Nomor Identitas</label>
                                    <input type="text"
                                        class="form-control <?= (session()->has('errors') && isset(session('errors')['id_card'])) ? 'is-invalid' : '' ?>"
                                        id="id_card"
                                        name="id_card"
                                        value="<?= old('id_card') ?>"
                                        placeholder="Masukkan nomor KTP"
                                        required>
                                    <?php if (session()->has('errors') && isset(session('errors')['id_card'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['id_card'] ?>
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
                        <!-- Tempat Lahir & Jenis Kelamin -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="place_of_birth">Tempat Lahir</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['place_of_birth'])) ? 'is-invalid' : '' ?>" id="place_of_birth" name="place_of_birth" value="<?= old('place_of_birth') ?>" placeholder="Masukkan tempat lahir">
                                    <?php if (session()->has('errors') && isset(session('errors')['place_of_birth'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['place_of_birth'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['gender'])) ? 'is-invalid' : '' ?>" id="gender" name="gender" required>
                                        <option value="">Pilih</option>
                                        <option value="Male" <?= old('gender') == 'Male' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Female" <?= old('gender') == 'Female' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['gender'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['gender'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                        <!-- Status Perkawinan & Mother Tongue -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="marital_status">Status Perkawinan (opsional)</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['marital_status'])) ? 'is-invalid' : '' ?>" id="marital_status" name="marital_status" required>
                                        <option value="">Pilih</option>
                                        <option value="Single" <?= old('marital_status') == 'Single' ? 'selected' : '' ?>>Belum Kawin</option>
                                        <option value="Married" <?= old('marital_status') == 'Married' ? 'selected' : '' ?>>Menikah</option>
                                        <option value="Divorced" <?= old('marital_status') == 'Divorced' ? 'selected' : '' ?>>Cerai</option>
                                        <option value="Widowed" <?= old('marital_status') == 'Widowed' ? 'selected' : '' ?>>Duda/Janda</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['marital_status'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['marital_status'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mother_tongue">Bahasa Ibu</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['mother_tongue'])) ? 'is-invalid' : '' ?>" id="mother_tongue" name="mother_tongue" value="<?= old('mother_tongue') ?>" placeholder="Masukkan bahasa ibu">
                                    <?php if (session()->has('errors') && isset(session('errors')['mother_tongue'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['mother_tongue'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Religion & Blood Group -->
                        <div class="row">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blood_group">Golongan Darah</label>
                                    <select class="form-control <?= (session()->has('errors') && isset(session('errors')['blood_group'])) ? 'is-invalid' : '' ?>" id="blood_group" name="blood_group" required>
                                        <option value="">Pilih</option>
                                        <option value="A" <?= old('blood_group') == 'A' ? 'selected' : '' ?>>A</option>
                                        <option value="B" <?= old('blood_group') == 'B' ? 'selected' : '' ?>>B</option>
                                        <option value="AB" <?= old('blood_group') == 'AB' ? 'selected' : '' ?>>AB</option>
                                        <option value="O" <?= old('blood_group') == 'O' ? 'selected' : '' ?>>O</option>
                                        <option value="Unknown" <?= old('blood_group') == 'Unknown' ? 'selected' : '' ?>>Tidak Diketahui</option>
                                    </select>
                                    <?php if (session()->has('errors') && isset(session('errors')['blood_group'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['blood_group'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control <?= (session()->has('errors') && isset(session('errors')['address'])) ? 'is-invalid' : '' ?>" id="address" name="address" placeholder="Masukkan alamat"><?= old('address') ?></textarea>
                            <?php if (session()->has('errors') && isset(session('errors')['address'])): ?>
                                <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                    <?= session('errors')['address'] ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- City & Address -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city">Kota</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['city'])) ? 'is-invalid' : '' ?>" id="city" name="city" value="<?= old('city') ?>" placeholder="Masukkan kota">
                                    <?php if (session()->has('errors') && isset(session('errors')['city'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['city'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- State -->
                                <div class="form-group">
                                    <label for="state">Provinsi</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['state'])) ? 'is-invalid' : '' ?>" id="state" name="state" value="<?= old('state') ?>" placeholder="Masukkan provinsi">
                                    <?php if (session()->has('errors') && isset(session('errors')['state'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['state'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Qualification -->
                                <div class="form-group">
                                    <label for="qualification">Kualifikasi</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['qualification'])) ? 'is-invalid' : '' ?>" id="qualification" name="qualification" value="<?= old('qualification') ?>" placeholder="Masukkan kualifikasi">
                                    <?php if (session()->has('errors') && isset(session('errors')['qualification'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['qualification'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Nationality -->
                                <div class="form-group">
                                    <label for="nationality">Kewarganegaraan</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['nationality'])) ? 'is-invalid' : '' ?>" id="nationality" name="nationality" value="<?= old('nationality') ?>" placeholder="Masukkan kewarganegaraan">
                                    <?php if (session()->has('errors') && isset(session('errors')['nationality'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['nationality'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Phone -->
                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['phone'])) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= old('phone') ?>" placeholder="Masukkan nomor telepon">
                                    <?php if (session()->has('errors') && isset(session('errors')['phone'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['phone'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Mobile No -->
                                <div class="form-group">
                                    <label for="mobile_no">Nomor Seluler</label>
                                    <input type="text" class="form-control <?= (session()->has('errors') && isset(session('errors')['mobile_no'])) ? 'is-invalid' : '' ?>" id="mobile_no" name="mobile_no" value="<?= old('mobile_no') ?>" placeholder="Masukkan nomor seluler">
                                    <?php if (session()->has('errors') && isset(session('errors')['mobile_no'])): ?>
                                        <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                            <?= session('errors')['mobile_no'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Foto -->
                            <div class="form-group">
                                <label for="photo">Foto</label>
                                <input type="file" class="form-control <?= (session()->has('errors') && isset(session('errors')['photo'])) ? 'is-invalid' : '' ?>" id="photo" name="photo" accept="image/*">
                                <?php if (session()->has('errors') && isset(session('errors')['photo'])): ?>
                                    <div class="ms-1 text-danger" style="font-size: 0.75rem;">
                                        <?= session('errors')['photo'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn bg-gradient-success">Simpan</button>
                                <a href="<?= base_url('admin/dokter') ?>" class="btn bg-gradient-danger">Kembali</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>