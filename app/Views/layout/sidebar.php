<?php $url = current_url(); ?>

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 ps ps--active-y bg-white" id="sidenav-main" data-color='info'>
    <div class="sidenav-header">
        <i class="ni ni-html5 p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html" target="_blank">
            <img src="<?= base_url(); ?>/assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">SI-COASS</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?= (strpos($url, 'dashboard') !== false) ? 'active ' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-dark text-center me-2 d-flex align-items-center justify-content-center">
                        <div role="img" aria-label="Dashboard Icon" class="fa-solid fa-house fa-lg color-background"></div>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (strpos($url, 'mahasiswa-coass') !== false) ? 'active ' : '' ?>" href="<?= base_url('admin/mahasiswa-coass') ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <div role="img" aria-label="Mahasiswa Coass Icon" class="fa-solid fa-user-tie fa-lg color-background"></div>
                    </div>
                    <span class="nav-link-text ms-1">Mahasiswa Coass</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (strpos($url, 'dokter') !== false) ? 'active ' : '' ?>" href="<?= base_url('admin/dokter') ?>">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <div role="img" aria-label="Dokter Icon" class="fa-solid fa-user-doctor fa-lg color-background"></div>
                    </div>
                    <span class="nav-link-text ms-1">Dokter</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (strpos($url, 'logbook') !== false) ? 'active ' : '' ?>" href="../pages/profile.html">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
        </ul>
    </div>
</aside>