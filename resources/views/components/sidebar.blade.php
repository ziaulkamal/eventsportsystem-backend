<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li><a href="{{ url('/dashboard') }}"><i data-feather="grid"></i><span>Dashboard</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="users"></i><span>Data Kontingen</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="/atlet">Atlet</a></li>
                                <li><a href="/coach">Coach</a></li>
                                <li><a href="/official">Official</a></li>
                                <li><a href="javascript:void(0);">Pencarian</a></li>

                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="monitor"></i><span>Data Pertandingan</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('pertandingan.data') }}">Daftarkan Pertandingan</a></li>
                                <li><a href="javascript:void(0);">Daftarkan Atlet</a></li>
                                <li><a href="javascript:void(0);">Data Pemenang</a></li>
                                <li><a href="javascript:void(0);">Pencarian</a></li>

                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="database"></i><span>Data Dokumen KYC</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="javascript:void(0);">Atlet</a></li>
                                <li><a href="javascript:void(0);">Non Atlet</a></li>

                            </ul>
                        </li>
                        <li><a href="{{ url('/venue') }}"><i data-feather="map"></i><span>Daftarkan Venue</span></a></li>
                        <li><a href="{{ route('penginapan') }}"><i data-feather="navigation"></i><span>Daftarkan Penginapan</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="truck"></i><span>Data Transportasi</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="javascript:void(0);">Datarkan Petugas</a></li>
                                <li><a href="javascript:void(0);">Kendaraan</a></li>

                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="calendar"></i><span>Jadwal Pertandingan</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="javascript:void(0);">Data Pertandingan</a></li>
                                <li><a href="javascript:void(0);">Cari Pertandingan</a></li>

                            </ul>
                        </li>
                    </ul>
                </li>


                <li class="submenu-open">
                    <h6 class="submenu-hdr">Logs</h6>
                    <ul>
                        <li><a href="{{ url('#') }}"><i data-feather="activity"></i><span>Aktifitas Pengguna</span></a></li>
                        <li><a href="{{ url('#') }}"><i data-feather="credit-card"></i><span>Badge Nama Tercetak</span></a></li>

                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Super Admin</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="code"></i><span>Master Konfigurasi</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('master_cabor') }}">Data Cabor</a></li>
                                <li><a href="{{ route('master_kelas_cabor') }}">Data Kelas Cabor</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="user"></i><span>Moderasi User</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="javascript:void(0);">Data Pengguna</a></li>
                                <li><a href="javascript:void(0);">Cari Pengguna</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>



