<div class="modal fade" id="topicModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Topik Forum</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3">
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#list-topik">Daftar Topik</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#buat-topik">Buat Topik</a>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
          <div class="tab-pane fade show active" id="list-topik">
            <table id="topicTable" class="table table-bordered shadow">
                <thead>
                    <tr class="bg-primary">
                        <th class="text-center">Nama</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forum->topics as $item)
                    <tr class="text-center">
                        <td>{{ $item->nama }}</td>
                        <td class="d-flex justify-content-center gap-3">
                            <form action="{{ route('topik.destroy', $item->slug) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus topik {{ $item->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="buat-topik">
            <form method="POST" action="{{ route('topik.store', $forum->slug) }}">
              @csrf
              <div class="mb-3">
                <label for="nama" class="form-label">Judul Topik</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary">Buat Topik</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>