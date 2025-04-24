@extends('layouts.management')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <h2 class="mb-3 fw-bold text-center">Detail Kegiatan {{ $event->nama }}</h2>
        <h5 class="fw-medium text-center">Tanggal 
            @if ($event->tanggal_mulai != $event->tanggal_selesai)
                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d') }} - 
                {{ \Carbon\Carbon::parse($event->tanggal_selesai)->translatedFormat('d F Y') }}
            @else
                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
            @endif 
        </h5>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>

        @if ($event->jenis_kegiatan == 'seleksi')
            @if(Gate::allows('akses-event') || Gate::allows('akses-member'))
                @include('event.partials.show_seleksi')
            @endif
        @elseif ($event->jenis_kegiatan == 'latihan')
            @can('akses-event')
                @include('event.partials.show_latihan')
            @endcan
        @elseif ($event->jenis_kegiatan == 'konser')
            @if(Gate::allows('akses-event') || Gate::allows('akses-eticket') || Gate::allows('akses-event-panitia') || Gate::allows('akses-eticket-panitia'))
                @include('event.partials.show_konser')
            @endif
        @elseif ($event->jenis_kegiatan == 'gladi')
            @can('akses-event')
                @include('event.partials.show_gladi')
            @endcan
        @elseif ($event->jenis_kegiatan == 'event')
            @can('akses-event')
                @include('event.partials.show_event')
            @endcan
        @endif
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Font Bold Navbar
        let navLinks = document.querySelectorAll(".navbar-nav .nav-link");
        function updateActiveTab() {
            navLinks.forEach(link => link.classList.remove("fw-bold"));
            let activeTab = document.querySelector(".navbar-nav .nav-link.active");
            if (activeTab) {
                activeTab.classList.add("fw-bold");
            }
        }
        
        updateActiveTab();
        navLinks.forEach(link => {
            link.addEventListener("shown.bs.tab", function() {
                updateActiveTab();
            });
        });
    });
</script>
@endsection

@if($event->jenis_kegiatan == 'konser')
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //create modal
            document.querySelectorAll(".create-modal").forEach((button) => {
                button.addEventListener("click", function() {
                    let route = this.dataset.action;
                    let name = this.dataset.name;
                    let id = this.dataset.id;

                    // Clean up any old modals & backdrops first
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    
                    fetch(route)
                        .then(response => response.text())
                        .then(html => {
                            let modalContainer = document.getElementById("modalContainer");
                            modalContainer.innerHTML = '';
                            modalContainer.innerHTML = html;

                            let createModal = "";
                            if (name == 'ticket-type'){
                                createModal = document.getElementById('createTicketModal');
                                createModal.querySelector('#concerts_id').value = id;
                            } else if (name == 'ticket-invites'){
                                createModal = document.getElementById('createTicketInviteModal');
                                createModal.querySelector('#concerts_id').value = id;
                            } else if (name == 'kupon'){
                                createModal = document.getElementById('createKuponModal');
                                createModal.querySelector('#concerts_id').value = id;
                            } else if (name == 'referal'){createReferalModal
                                createModal = document.getElementById('createReferalModal');
                                createModal.querySelector('#concerts_id').value = id;

                                createModal.addEventListener('shown.bs.modal', function () {
                                    $('#penyanyiReferalTable').DataTable({
                                        "lengthMenu": [5, 10, 20, 40],
                                        "order": [[1, "asc"]],
                                        "language": {
                                            "emptyTable": "Belum ada penyanyi"
                                        }
                                    });
                                    document.getElementById('pilihSemuaBtn').addEventListener('click', function () {
                                        const checkboxes = document.querySelectorAll('.checkbox-penyanyi');
                                        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

                                        checkboxes.forEach(checkbox => {
                                            checkbox.checked = !allChecked;
                                        });

                                        this.textContent = allChecked ? 'Pilih Semua' : 'Batalkan Semua';
                                    });
                                });
                            } else if (name == 'penyanyi'){
                                createModal = document.getElementById('tambahPenyanyiModal');
                                createModal.querySelector('#events_id').value = id;

                                createModal.addEventListener('shown.bs.modal', function () {
                                    $('#members_id').select2({
                                        placeholder: 'Cari Anggota...',
                                        dropdownParent: $('#tambahPenyanyiModal'),
                                        width: '100%',
                                        ajax: {
                                            url: @json(route('penyanyi.search')),
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                return {
                                                    search: params.term,
                                                    only_choir_members: true
                                                };
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: data
                                                };
                                            }
                                        }
                                    });
                                    $('#seleksiTable').DataTable({
                                        "lengthMenu": [5, 10, 20, 40],
                                        "order": [[1, "asc"]],
                                        "language": {
                                            "emptyTable": "Belum ada seleksi penyanyi"
                                        }
                                    });
                                    createModal.querySelectorAll('input[name="mode"]').forEach(radio => {
                                        radio.addEventListener('change', function () {
                                            const seleksiSection = document.getElementById('penyanyi-seleksi-section');
                                            const baruSection = document.getElementById('penyanyi-baru-section');
                                            const eventSection = document.getElementById('event-lain-section');

                                            if (this.value === 'seleksi') {
                                                seleksiSection.style.display = 'block';
                                                baruSection.style.display = 'none';
                                                eventSection.style.display = 'none';

                                                seleksiSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                                baruSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                eventSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                            } else if (this.value === 'baru') {
                                                seleksiSection.style.display = 'none';
                                                baruSection.style.display = 'block';
                                                eventSection.style.display = 'none';

                                                seleksiSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                baruSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                                eventSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                            } else if (this.value === 'event') {
                                                seleksiSection.style.display = 'none';
                                                baruSection.style.display = 'none';
                                                eventSection.style.display = 'block';

                                                seleksiSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                baruSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                eventSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                            }
                                        });
                                    });
                                    document.getElementById('pilihSemuaBtn').addEventListener('click', function () {
                                        const checkboxes = document.querySelectorAll('.checkbox-penyanyi');
                                        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

                                        checkboxes.forEach(checkbox => {
                                            checkbox.checked = !allChecked;
                                        });

                                        this.textContent = allChecked ? 'Pilih Semua' : 'Batalkan Semua';
                                    });
                                });
                            } else if (name == 'panitia'){
                                createModal = document.getElementById('tambahPanitiaModal');
                                createModal.querySelector('#events_id').value = id;

                                createModal.addEventListener('shown.bs.modal', function () {
                                    $('#users_id').select2({
                                        placeholder: 'Cari Pengguna...',
                                        dropdownParent: $('#tambahPanitiaModal'),
                                        width: '100%',
                                        ajax: {
                                            url: @json(route('panitia.search')),
                                            dataType: 'json',
                                            delay: 250,
                                            data: function(params) {
                                                return {
                                                    search: params.term,
                                                    only_choir_members: false,
                                                };
                                            },
                                            processResults: function(data) {
                                                return {
                                                    results: data
                                                };
                                            }
                                        }
                                    });
                                    $('#seleksiTable').DataTable({
                                        "lengthMenu": [5, 10, 20, 40],
                                        "order": [[1, "asc"]],
                                        "language": {
                                            "emptyTable": "Belum ada seleksi panitia"
                                        }
                                    });
                                    createModal.querySelectorAll('input[name="mode"]').forEach(radio => {
                                        radio.addEventListener('change', function () {
                                            const seleksiSection = document.getElementById('panitia-seleksi-section');
                                            const baruSection = document.getElementById('panitia-baru-section');
                                            const eventSection = document.getElementById('event-lain-section');

                                            if (this.value === 'seleksi') {
                                                seleksiSection.style.display = 'block';
                                                baruSection.style.display = 'none';
                                                eventSection.style.display = 'none';

                                                seleksiSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                                baruSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                eventSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                            } else if (this.value === 'baru') {
                                                seleksiSection.style.display = 'none';
                                                baruSection.style.display = 'block';
                                                eventSection.style.display = 'none';

                                                seleksiSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                baruSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                                eventSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                            } else if (this.value === 'event') {
                                                seleksiSection.style.display = 'none';
                                                baruSection.style.display = 'none';
                                                eventSection.style.display = 'block';

                                                seleksiSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                baruSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                                                eventSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                                            }
                                        });
                                    });
                                    document.getElementById('pilihSemuaBtn').addEventListener('click', function () {
                                        const checkboxes = document.querySelectorAll('.checkbox-panitia');
                                        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

                                        checkboxes.forEach(checkbox => {
                                            checkbox.checked = !allChecked;
                                        });

                                        this.textContent = allChecked ? 'Pilih Semua' : 'Batalkan Semua';
                                    });
                                });
                            }
                            new bootstrap.Modal(createModal).show();
                        });
                });
            });

            //Edit Modal
            document.querySelectorAll(".edit-modal").forEach((button) => {
                button.addEventListener("click", function() {
                    let name = this.dataset.name;
                    let editUrl = this.dataset.route;

                    // Clean up any old modals & backdrops first
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    
                    fetch(editUrl)
                        .then(response => response.text())
                        .then(html => {
                            let modalContainer = document.getElementById("modalContainer");
                            modalContainer.innerHTML = '';
                            modalContainer.innerHTML = html;

                            let editModal = "";
                            if (name == 'ticket-type'){
                                editModal = document.getElementById('editTicketModal');
                            } else if (name == 'panitia'){
                                editModal = document.getElementById('editPanitiaModal');
                            } else if (name == 'kupon'){
                                editModal = document.getElementById('editKuponModal');
                            }
                            new bootstrap.Modal(editModal).show();
                        })
                        .catch(error => console.error("Error loading modal:", error));
                });
            });


            //Data table
            $('#penyanyiTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada penyanyi"
                }
            });
            $('#panitiaTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada panitia"
                }
            });
            $('#purchaseTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada pembeli"
                }
            });
            $('#invitationTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada tamu undangan"
                }
            });
            $('#ticketTypeTable').DataTable({
                "lengthMenu": [5, 10, 20, 40],
                "language": {
                    "emptyTable": "Belum ada jenis tiket yang dijual"
                }
            });
            $('#kuponTable').DataTable({
                "lengthMenu": [5, 10, 20, 40],
                "language": {
                    "emptyTable": "Belum ada kupon yang dibuat"
                }
            });
            $('#referalTable').DataTable({
                "lengthMenu": [5, 10, 20, 40],
                "language": {
                    "emptyTable": "Belum ada kode referal yang dibuat"
                }
            });

            //Show check in button
            document.querySelectorAll(".loadCheckInForm").forEach((button) => {
                button.addEventListener("click", function() {
                    let purchaseId = this.dataset.purchaseId;
                    let checkInShowUrl = `{{ route('events.checkInShow', ':id') }}`.replace(':id', purchaseId);
                    fetch(checkInShowUrl)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById("modalContainer").innerHTML = html;
                            let checkInModal = document.getElementById("checkInModal");
                            if (checkInModal) {
                                new bootstrap.Modal(checkInModal).show();
                                //Check in button di modalnya
                                document.querySelectorAll(".btn-check-in").forEach((button) => {
                                    button.addEventListener("click", function () {
                                        let ticketId = this.dataset.ticketId;
                                        let buttonElement = this;
                                        let checkInUrl = `{{ route('tickets.checkIn', ':id') }}`.replace(':id', ticketId);
                                        fetch(checkInUrl, {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                                "Accept": "application/json",
                                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                            },
                                        })
                                            .then((response) => response.json())
                                            .then((data) => {
                                                if (data.error) {
                                                    alert(data.error);
                                                    return;
                                                }
                                                // Update UI
                                                let row = buttonElement.closest("tr");
                                                row.querySelector("td:nth-child(3)").textContent = "Checked In"; // Update Status
                                                row.querySelector("td:nth-child(4)").textContent = data.waktu_check_in; // Update Time
                                                buttonElement.replaceWith(document.createElement("span")); // Remove Button
                                                row.querySelector("td:last-child span").textContent = "Checked In";
                                                row.querySelector("td:last-child span").classList.add("text-success");
                                            })
                                            .catch((error) => {
                                                console.error("Check-in error:", error);
                                                alert("Failed to check in. Please try again.");
                                            });
                                    });
                                });
                            } else {
                                console.error("Modal element #checkInModal not found.");
                            }
                        })
                        .catch(error => console.error("Error loading modal:", error));
                });
            });


            //Counter text area
            const deksripsiInput = document.getElementById("deskripsi-area");
            const syaratInput = document.getElementById("syarat_ketentuan-area");
            const deskripsiCount = document.getElementById("deskripsi-counter");
            const syaratCount = document.getElementById("syarat_ketentuan-counter");

            function updateCounter(inputElement, counterElement) {
                counterElement.textContent = `${inputElement.value.length}/1000 karakter`;
            }

            updateCounter(deksripsiInput, deskripsiCount);
            updateCounter(syaratInput, syaratCount);

            deksripsiInput.addEventListener("input", function() {
                updateCounter(deksripsiInput, deskripsiCount);
            });

            syaratInput.addEventListener("input", function() {
                updateCounter(syaratInput, syaratCount);
            });
            

            //Ebooklet container
            let ebookletYes = document.getElementById("ebooklet_yes");
            let ebookletNo = document.getElementById("ebooklet_no");
            let ebookletLinkContainer = document.getElementById("ebooklet_link_container");
            let ebookletInput = document.getElementById("link_ebooklet");

            function toggleEbookletInput() {
                if (ebookletYes.checked) {
                    ebookletLinkContainer.style.display = "block";
                    ebookletInput.setAttribute("required", "required");
                } else {
                    ebookletLinkContainer.style.display = "none";
                    ebookletInput.removeAttribute("required");
                }
            }

            toggleEbookletInput();
            ebookletYes.addEventListener("change", toggleEbookletInput);
            ebookletNo.addEventListener("change", toggleEbookletInput);


            //Donasi Container
            let donasiYes = document.getElementById("donasi_yes");
            let donasiNo = document.getElementById("donasi_no");
            let donasiContainer = document.getElementById("donasi_container");

            function toggleDonasiTable() {
                if (donasiYes.checked) {
                    donasiContainer.style.display = "block";
                } else {
                    donasiContainer.style.display = "none";
                }
            }

            toggleDonasiTable();
            donasiYes.addEventListener("change", toggleDonasiTable);
            donasiNo.addEventListener("change", toggleDonasiTable);


            //Tipe Kupon dan Kupon Referal Container
            let kuponYes = document.getElementById("kupon_yes");
            let kuponNo = document.getElementById("kupon_no");
            let tipeKuponContainer = document.getElementById("tipe_kupon_container");
            let tipeKupon = document.getElementById("tipe_kupon");
            let kuponContainer = document.getElementById("kupon_container");
            let referalContainer = document.getElementById("referal_container");

            function toggleTipeKupon() {
                if (kuponYes.checked) {
                    tipeKuponContainer.style.display = "block";
                    tipeKupon.setAttribute("required", "required");
                    toggleKuponReferalContainers();
                } else {
                    tipeKuponContainer.style.display = "none";
                    tipeKupon.removeAttribute("required");
                    kuponContainer.style.display = "none";
                    referalContainer.style.display = "none";
                }
            }
            
            function toggleKuponReferalContainers() {
                let selectedValue = tipeKupon.value;
                kuponContainer.style.display = "none";
                referalContainer.style.display = "none";
                if (selectedValue === "kupon") {
                    kuponContainer.style.display = "block";
                } else if (selectedValue === "referal") {
                    referalContainer.style.display = "block";
                } else if (selectedValue === "keduanya") {
                    kuponContainer.style.display = "block";
                    referalContainer.style.display = "block";
                }
            }
            toggleKuponReferalContainers();
            toggleTipeKupon();

            kuponYes.addEventListener("change", toggleTipeKupon);
            kuponNo.addEventListener("change", toggleTipeKupon);
            tipeKupon.addEventListener("change", toggleKuponReferalContainers);


            //Gambar Seating Plan
            document.querySelectorAll(".file-drop-area").forEach((dropArea) => {
                const fileInput = dropArea.querySelector("input[type='file']");
                const fileNameDisplay = dropArea.querySelector("#fileName");
                const imageContainer = dropArea.closest(".mb-3").querySelector(".image-container");
                const previewImage = imageContainer.querySelector("img");
                const editBtn = imageContainer.querySelector(".edit-btn");
                const deleteBtn = imageContainer.querySelector(".delete-btn");
                const existingImageInput = dropArea.querySelector("input[type='hidden']");
                
                // Load existing image
                let checkImage = existingImageInput.value.trim();
                if (checkImage && checkImage !== "null") {
                    let existingImage = `{{ asset('storage/') }}/${checkImage}`;
                    previewImage.src = existingImage;
                    imageContainer.classList.remove("d-none");
                    dropArea.classList.add("d-none");
                }

                // Click to browse
                dropArea.addEventListener("click", () => fileInput.click());

                // Handle file selection
                fileInput.addEventListener("change", (event) => {
                    updateFileDisplay(event.target.files[0]);
                });

                // Drag over effect
                dropArea.addEventListener("dragover", (event) => {
                    event.preventDefault();
                    dropArea.classList.add("dragover");
                });

                dropArea.addEventListener("dragleave", () => {
                    dropArea.classList.remove("dragover");
                });

                // Drop file
                dropArea.addEventListener("drop", (event) => {
                    event.preventDefault();
                    dropArea.classList.remove("dragover");
                    const file = event.dataTransfer.files[0];
                    fileInput.files = event.dataTransfer.files; // Update input
                    updateFileDisplay(file);
                });

                // Update File Display
                function updateFileDisplay(file) {
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewImage.src = e.target.result;
                            imageContainer.classList.remove("d-none");
                            dropArea.classList.add("d-none");
                            fileNameDisplay.textContent = file.name;
                            existingImageInput.value = ""; // Clear existing image
                        };
                        reader.readAsDataURL(file);
                    } else {
                        fileNameDisplay.textContent = "Belum ada file yang dipilih";
                    }
                }

                // Edit Button (Re-upload)
                if (editBtn) {
                    editBtn.addEventListener("click", function () {
                        fileInput.click();
                    });
                }

                // Delete Button (Remove Image)
                if (deleteBtn) {
                    deleteBtn.addEventListener("click", function () {
                        previewImage.src = "";
                        imageContainer.classList.add("d-none");
                        dropArea.classList.remove("d-none");
                        fileInput.value = "";
                        fileNameDisplay.textContent = "Belum ada file yang dipilih";
                        existingImageInput.value = existingImage; // Restore old image value if deleted
                    });
                }
            });
        });
    </script>
    @endpush
@elseif($event->jenis_kegiatan == 'latihan')
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //Modal tambah latihan
            document.getElementById('loadCreateForm').addEventListener('click', function() {
                let route = this.dataset.action;
                let id = this.dataset.id;
                let tanggalMulai = this.dataset.tanggalMulai;
                let tanggalSelesai = this.dataset.tanggalSelesai;

                fetch(route)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('modalContainer').innerHTML = html;
                        const modalElement = document.getElementById('createModal');

                        modalElement.querySelector('#events_id').value = id;
                        const dateInput = modalElement.querySelector('#tanggal');
                        if (dateInput && tanggalMulai && tanggalSelesai) {
                            dateInput.setAttribute('min', tanggalMulai);
                            dateInput.setAttribute('max', tanggalSelesai);
                        }
                        new bootstrap.Modal(modalElement).show();
                        
                        //modal JS
                        //Perulangan wrapper
                        const kegiatanBerulang = document.getElementById('kegiatan_berulang');
                        const perulanganWrapper = document.getElementById('perulangan_wrapper');
                        const inputs = perulanganWrapper.querySelectorAll("input, select");

                        function togglePerulanganWrapper() {
                            if (kegiatanBerulang.value === 'ya') {
                                perulanganWrapper.style.display = 'block';
                                inputs.forEach(el => {
                                    if (el.id !== 'tanggal_berakhir' && el.id !== 'jumlah_perulangan') {
                                        el.disabled = false;
                                    }
                                });

                                toggleInputs();
                            } else {
                                perulanganWrapper.style.display = 'none';
                                inputs.forEach(el => {
                                    el.disabled = true;
                                    if (el.tagName === "SELECT") {
                                        const defaultOption = el.querySelector("option[default], option[selected]");
                                        if (defaultOption) {
                                            el.value = defaultOption.value;
                                        } else {
                                            el.selectedIndex = 0;
                                        }
                                    } else {
                                        if (el.type === "checkbox" || el.type === "radio") {
                                            el.checked = false;
                                        } else if (['text', 'number', 'date', 'time'].includes(el.type)) {
                                            el.value = "";
                                        }
                                    }
                                });
                            }
                        }

                        if (kegiatanBerulang && perulanganWrapper) {
                            kegiatanBerulang.addEventListener('change', togglePerulanganWrapper);
                            togglePerulanganWrapper();
                        }


                        //Disable input tipe selesai perulangan
                        const radios = modalElement.querySelectorAll('input[name="tipe_selesai"]');
                        const inputTanggal = modalElement.querySelector('#tanggal_berakhir');
                        const inputJumlah = modalElement.querySelector('#jumlah_perulangan');

                        function toggleInputs() {
                            const selected = modalElement.querySelector('input[name="tipe_selesai"]:checked').value;

                            if (inputTanggal) inputTanggal.disabled = selected !== 'tanggal';
                            if (inputJumlah) inputJumlah.disabled = selected !== 'jumlah';
                        }

                        radios.forEach(radio => {
                            radio.addEventListener('change', toggleInputs);
                        });

                        toggleInputs();
                    });
            });

            document.querySelectorAll(".loadEditForm").forEach((button) => {
                button.addEventListener("click", function() {
                    let latihanId = this.dataset.id;
                    let eventId = this.dataset.eventId;
                    let tanggalMulai = this.dataset.tanggalMulai;
                    let tanggalSelesai = this.dataset.tanggalSelesai;
                    let editUrl = `{{ route('latihans.edit', ':id') }}`.replace(':id', latihanId);

                    fetch(editUrl)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById("modalContainer").innerHTML = html;
                            const modalElement = document.getElementById('editModal');

                            modalElement.querySelector('#events_id').value = eventId;
                            const dateInput = modalElement.querySelector('#tanggal');
                            if (dateInput && tanggalMulai && tanggalSelesai) {
                                dateInput.setAttribute('min', tanggalMulai);
                                dateInput.setAttribute('max', tanggalSelesai);
                            }

                            new bootstrap.Modal(modalElement).show();
                            
                        })
                        .catch(error => console.error("Error loading modal:", error));
                });
            });


            //Data table pembeli tiket dan tipe tiket
            $('#latihanTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada jadwal latihan"
                }
            });
        });
    </script>
    @endpush
@elseif($event->jenis_kegiatan == 'seleksi')
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //Modal tambah pendaftar
            let tipe = @json($seleksi->tipe);
            let onlyChoirMember = true;
            let route = @json(route('members.search'));
            if (tipe == 'panitia'){
                onlyChoirMember = false;
                route = @json(route('panitia.search'));
            }
            
            $('#addMemberModal').on('shown.bs.modal', function () {
                $('#users_id').select2({
                    placeholder: 'Cari Pengguna...',
                    dropdownParent: $('#addMemberModal'),
                    width: '100%',
                    ajax: {
                        url: route,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term,
                                only_choir_members: onlyChoirMember
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
                document.querySelectorAll('input[name="mode"]').forEach(radio => {
                    radio.addEventListener('change', function () {
                        const existingSection = document.getElementById('existing-user-section');
                        const newSection = document.getElementById('new-user-section');

                        if (this.value === 'ada') {
                            existingSection.style.display = 'block';
                            newSection.style.display = 'none';

                            // Enable inputs in existing section
                            existingSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                            // Disable inputs in new section
                            newSection.querySelectorAll('input, select').forEach(el => el.disabled = true);

                        } else {
                            existingSection.style.display = 'none';
                            newSection.style.display = 'block';

                            // Enable inputs in new section
                            newSection.querySelectorAll('input, select').forEach(el => el.disabled = false);
                            // Disable inputs in existing section
                            existingSection.querySelectorAll('input, select').forEach(el => el.disabled = true);
                        }
                    });
                });
            });

            //Data table pembeli tiket dan tipe tiket
            $('#pendaftarTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada pendaftar"
                }
            });
            $('#hasilTable').DataTable({
                "language": {
                    "emptyTable": "Belum ada pendaftar"
                }
            });
        });
    </script>
    @endpush
@endif