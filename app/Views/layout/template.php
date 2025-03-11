<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Administrasi Kegiatan Coass di Rumah Sakit Pendidikan Gigi dan Mulut adalah platform berbasis web yang dirancang untuk mengelola administrasi mahasiswa co-assistant. Sistem ini mencakup pengelolaan jadwal rotasi, logbook, penilaian, serta administrasi dokter dan mahasiswa, sehingga memastikan proses pendidikan klinis berjalan lebih efisien dan terorganisir." />
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url(); ?>/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?= base_url(); ?>/assets/img/favicon.png">
    <title>
        <?= isset($title) ? $title : 'SI-COASS' ?>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/f87d0eb9a6.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= base_url(); ?>/assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />

</head>

<body class="g-sidenav-show  bg-gray-100">
    <?php
    $role = session()->get('role'); // Ambil role dari session

    // Memuat sidebar berdasarkan role
    if ($role === 'Admin') {
        echo $this->include('layout/sidebar_admin');
    } elseif ($role === 'Dokter') {
        echo $this->include('layout/sidebar_dokter');
    } else {
        echo $this->include('layout/sidebar_mahasiswa');
    }
    ?>
    <?= $this->include('layout/navbar'); ?>
    <?= $this->renderSection('content'); ?>

    <!--   Core JS Files   -->
    <script src="<?= base_url(); ?>/assets/js/core/popper.min.js"></script>
    <script src="<?= base_url(); ?>/assets/js/core/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="<?= base_url(); ?>/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="<?= base_url(); ?>/assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
</body>

</html>