<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid pt-3 ps-0 ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <?php $breadcrumbs = generate_breadcrumb(); ?>
                    <?php foreach ($breadcrumbs as $index => $crumb): ?>
                        <li class="breadcrumb-item text-sm <?= $crumb['active'] ? 'text-dark active' : '' ?>" <?= $crumb['active'] ? 'aria-current="page"' : '' ?>>
                            <?php if (!$crumb['active']): ?>
                                <a class="opacity-5 text-dark" href="<?= $crumb['link'] ?>"><?= $crumb['title'] ?></a>
                            <?php else: ?>
                                <?= $crumb['title'] ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
                <h6 class="font-weight-bolder mb-0"><?= end($breadcrumbs)['title'] ?></h6>
            </nav>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item dropdown d-flex align-items-center">
                    <a class="nav-link dropdown-toggle font-weight-bold px-0 fs-6" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-circle-user fa-lg me-1"></i>
                        <span><?= session()->get('name'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?= base_url('/auth/logout') ?>">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <div class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    </nav>
    <!-- End Navbar -->