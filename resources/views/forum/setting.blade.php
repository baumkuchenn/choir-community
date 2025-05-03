@extends('layouts.forum')

@section('content')
<div class="ps-3 pe-3 d-flex flex-nowrap">
    @include('forum.partials.sidebar', ['followForums' => $followForums, 'topForums' => $topForums])
    <div class="w-100">
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
        <div class="col-lg-11 mx-auto position-relative">
            <div class="position-relative">
                <img src="{{ asset('storage/' . $forum->foto_banner) }}" alt="Banner" class="w-100 rounded" style="height: 180px; object-fit: cover;">
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between bg-white p-3 rounded-bottom shadow-sm mb-3" style="margin-top: -20px;">
                <div style="position: absolute; top: 140px; left: 20px;">
                    <img src="{{ asset('storage/' . $forum->foto_profil) }}" alt="Profile" width="96" height="96" class="rounded-circle border border-white shadow">
                </div>
                <div class="mt-2" style="margin-left: 110px;">
                    <h4 class="mb-0">{{ $forum->nama }}</h4>
                    <small class="text-muted">
                        {{ $forum->visibility_label }} • {{ $forum->members->count() }} anggota
                    </small>
                </div>
            </div>

            <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">Kembali</a>

            @if(in_array($jabatan, ['admin']))
                <div class="shadow-sm p-3 mb-2">
                    <h4 class="mb-3 fw-bold">Pengaturan Forum</h4>
                    <form action="{{ route('forum.update', $forum->slug) }}" method="POST" class="mb-0">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $forum->nama) }}" required>
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="visibility" class="form-label">Tipe Forum</label>
                            <select class="form-select" id="visibility" name="visibility" required>
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option value="public" {{ old('visibility', $forum->visibility) == 'public' ? 'selected' : '' }}>Publik</option>
                                <option value="private" {{ old('visibility', $forum->visibility) == 'private' ? 'selected' : '' }}>Privat</option>
                            </select>
                            @error('visibility')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="foto_profil" class="form-label">Foto Profil Forum</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img id="fotoProfilPreview" src="{{ asset('storage/' . old('foto_profil', $forum->foto_profil)) }}" alt="Foto Profil" style="width: 100px; height: 100px;">
                                    <div class="text-center">
                                        <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                                        <small class="fw-light">JPG, JPEG, atau PNG. Max 2 MB</small>
                                    </div>
                                </div>
                                <small class="fw-light">Rekomendasi foto memiliki aspek ratio 1:1</small>
                                @error('foto_profil')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="foto_banner" class="form-label">Foto Banner Forum</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img id="fotoBannerPreview" src="{{ asset('storage/' . old('foto_banner', $forum->foto_banner)) }}" alt="Foto Banner" style="width: 160px; height: 90px;">
                                    <div class="text-center">
                                        <input type="file" class="form-control" id="foto_banner" name="foto_banner" accept="image/*">
                                        <small class="fw-light">JPG, JPEG, atau PNG. Max 2 MB</small>
                                    </div>
                                </div>
                                <small class="fw-light">Rekomendasi foto memiliki aspek ratio 16:9</small>
                                @error('foto_banner')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Forum</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" maxlength="250" rows="10" required>{!! old('deskripsi', $forum->deskripsi) !!}</textarea>
                            <div class="d-flex justify-content-between">
                                <small id="deskripsi-counter">0/250</small>
                            </div>
                            @error('deskripsi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            @endif
            @if(in_array($jabatan, ['admin', 'moderator']))
                <div class="shadow-sm p-3 mb-3">
                    <h4 class="mb-3 fw-bold">Anggota Forum</h4>
                    <table id="anggotaTable" class="table table-bordered shadow text-center">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center">Nama Lengkap</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Jabatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->jabatan }}</td>
                                    <td>
                                        <button class="btn btn-primary edit-modal" data-name="member" data-route="{{ route('forum-member.edit', $item->id) }}">Ubah</button>
                                        <button class="btn btn-outline-danger deleteBtn" data-name="{{ $item->user->name }}" data-action="{{ route('forum-member.destroy', $item->id) }}">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary fw-bold create-modal" data-id="{{ $forum->id }}" data-name="member" data-action="{{ route('forum-member.create') }}">+ Tambah Anggota</button>
                </div>
            @endif
            <div class="shadow-sm p-3 mb-3">
                <h4 class="mb-3 fw-bold">Pengaturan Notifikasi</h4>
            </div>
        </div>
    </div>
</div>
<div id="modalContainer"></div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Update preview profil dan banner
        document.getElementById('foto_profil').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoProfilPreview').src = e.target.result; // Set the image source to preview
                };
                reader.readAsDataURL(file); // Convert the file to Base64
            }
        });

        document.getElementById('foto_banner').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoBannerPreview').src = e.target.result; // Set the image source to preview
                };
                reader.readAsDataURL(file); // Convert the file to Base64
            }
        });


        //Counter Deskripsi
        const deksripsiInput = document.getElementById("deskripsi");
        const deskripsiCount = document.getElementById("deskripsi-counter");

        function updateCounter(inputElement, counterElement) {
            counterElement.textContent = `${inputElement.value.length}/250 karakter`;
        }

        updateCounter(deksripsiInput, deskripsiCount);

        deksripsiInput.addEventListener("input", function() {
            updateCounter(deksripsiInput, deskripsiCount);
        });


        //Member data table
        $('#anggotaTable').DataTable({
            "lengthMenu": [5, 10, 20, 40],
            "order": [[1, "asc"]],
            "language": {
                "emptyTable": "Belum ada anggota pada forum"
            }
        });


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
                        if (name == 'member'){
                            createModal = document.getElementById('tambahAnggotaModal');
                            createModal.querySelector('#forums_id').value = id;

                            $('#users_id').select2({
                                placeholder: 'Cari Pengguna...',
                                dropdownParent: $('#tambahAnggotaModal'),
                                width: '100%',
                                ajax: {
                                    url: @json(route('forum-member.search')),
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        return {
                                            search: params.term,
                                        };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: data
                                        };
                                    }
                                }
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
                            if (name == 'member'){
                                editModal = document.getElementById('editAnggotaModal');
                            }
                            new bootstrap.Modal(editModal).show();
                        })
                        .catch(error => console.error("Error loading modal:", error));
                });
            });
    });
</script>
@endsection