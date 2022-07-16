 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="index3.html" class="brand-link">
         <img src="{{ asset('AdminLTE-3/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
         <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="{{ asset('AdminLTE-3/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                     alt="User Image">
             </div>
             <div class="info">
                 <a href="#" class="d-block">{{ Auth::user()->name }}</a>
             </div>
         </div>

         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">
                 <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->
                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon fas fa-tachometer-alt"></i>
                         <p>
                             Dashboard
                         </p>
                     </a>
                 </li>
                 <li class="nav-header">EXAMPLES</li>
                 <li class="nav-item">
                     <a href="pages/calendar.html" class="nav-link">
                         <i class="fa fa-cube"></i>
                         <p>
                             Kategori
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-cubes"></i>
                         <p>
                             Produk
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-id-card"></i>
                         <p>
                             Member
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-truck"></i>
                         <p>
                             Supplier
                         </p>
                     </a>
                 </li>
                 <li class="nav-header">TRANSAKSI</li>
                 <li class="nav-item">
                     <a href="pages/calendar.html" class="nav-link">
                         <i class="fa fa-credit-card"></i>
                         <p>
                             Pengeluaran
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/calendar.html" class="nav-link">
                         <i class="fa fa-shopping-cart"></i>
                         <p>
                             Pembelian
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-upload"></i>
                         <p>
                             Penjualan
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-cart-arrow-down"></i>
                         <p>
                             Transaksi Lama
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-cart-arrow-down"></i>
                         <p>
                             Transaksi Baru
                         </p>
                     </a>
                 </li>
                 <li class="nav-header">Report</li>
                 <li class="nav-item">
                     <a href="pages/calendar.html" class="nav-link">
                         <i class="fa fa-file"></i>
                         <p>
                             Laporan
                         </p>
                     </a>
                 </li>
                 <li class="nav-header">System</li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-user"></i>
                         <p>
                             User
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="pages/gallery.html" class="nav-link">
                         <i class="fa fa-cog"></i>
                         <p>
                             Pengaturan
                         </p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                         <i class="fa fa-cog"></i>
                         <p>
                             Logout
                         </p>
                     </a>

                 </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
     <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none">
         @csrf
     </form>
 </aside>
