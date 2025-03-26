<?= $this->extend('./layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Tambah Mahasiswa ke Stase</h5>
                </div>
                <div class="card-body">
                    <!-- Menampilkan pesan kesalahan jika ada -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger text-white fw-bold fs-6">
                            <i class="fa-solid fa-circle-info me-2"></i><?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('dokter/stase/detail-stase/store') ?>" method="POST">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="stase_id" value="<?= esc($stase['stase_id']) ?>">

                        <div class="mb-3">
                            <label for="coass_select" class="form-label">Pilih Mahasiswa</label>
                            <select class="form-select" id="coass_select">
                                <option value="">Pilih Mahasiswa</option>
                                <?php foreach ($allMahasiswa as $mhs): ?>
                                    <option value="<?= esc($mhs['coass_id']); ?>" data-name="<?= esc($mhs['name']); ?>" data-nim="<?= esc($mhs['nim']); ?>">
                                        <?= esc($mhs['name']); ?> (<?= esc($mhs['nim']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div id="selected-mahasiswa" class="mb-3 d-flex flex-wrap"></div>

                        <button type="submit" class="btn bg-gradient-success">Simpan</button>
                        <a href="<?= base_url('dokter/stase/detail-stase/' . $encryptedID) ?>" class="btn bg-gradient-info">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('coass_select').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let coassId = selectedOption.value;
        let coassName = selectedOption.getAttribute('data-name');
        let coassNim = selectedOption.getAttribute('data-nim');

        // Cek jika ada mahasiswa yang dipilih dan belum ditambahkan
        if (coassId && !document.getElementById('selected-' + coassId)) {
            let selectedContainer = document.getElementById('selected-mahasiswa');
            let newItem = document.createElement('div');
            newItem.classList.add('d-flex', 'align-items-center', 'p-0', 'm-0');
            newItem.id = 'selected-' + coassId;
            newItem.innerHTML = `
                <input type="hidden" name="coass_id[]" value="${coassId}">
                <div class="d-flex align-items-center border border-2 border-success rounded-pill mb-2 me-2">
                    <span class="me-2 py-2 ps-3">${coassName} (${coassNim})</span>
                    <button type="button" class="btn btn-link text-secondary remove-mahasiswa mb-0 p-0 pe-3" data-id="${coassId}">
                        <i class="fa-solid fa-times fa-lg"></i>
                    </button>
                </div>
            `;
            selectedContainer.appendChild(newItem);
        }
        // Reset pilihan select
        this.value = "";
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-mahasiswa')) {
            let coassId = e.target.closest('.remove-mahasiswa').getAttribute('data-id');
            let selectedItem = document.getElementById('selected-' + coassId);
            if (selectedItem) {
                selectedItem.remove();
            }
        }
    });
</script>

<?= $this->endSection(); ?>