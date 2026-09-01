<div class="mb-4">
    <div class="section-actions">
        <h4 class="section-title-small">Kelola Hero Slider</h4>
        <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#addHeroModal">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Slide
        </a>
    </div>
    @if ($heroSlides->isEmpty())
        <div class="empty-state">
            <p>Tidak ada slide. Tambahkan slide baru.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Judul</th>
                        <th>Gambar</th>
                        <th>Urutan</th>
                        <th>Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($heroSlides as $slide)
                    <tr>
                        <td>{{ $slide->urutan }}</td>
                        <td>
                            <strong>{{ $slide->judul }}</strong>
                            @if($slide->subjudul)
                                <br><small style="color:var(--mid);">{{ Str::limit($slide->subjudul, 60) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($slide->gambar)
                                <small style="word-break:break-all;">{{ Str::limit($slide->gambar, 40) }}</small>
                            @else
                                <small style="color:var(--mid);">—</small>
                            @endif
                        </td>
                        <td>{{ $slide->urutan }}</td>
                        <td>
                            @if($slide->is_active)
                                <span class="status-badge status-selesai" style="font-size:10px;">Aktif</span>
                            @else
                                <span class="status-badge status-menunggu_validasi" style="font-size:10px;">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.hero.edit', $slide->id) }}" class="btn-edit">
                                <i class="bi bi-pencil" aria-hidden="true"></i> Edit
                            </a>
                            <form action="{{ route('admin.hero.delete', $slide->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-delete" onclick="return confirm('Hapus slide ini?')">
                                    <i class="bi bi-trash" aria-hidden="true"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($heroSlides->hasPages())
            <div class="mt-3">{{ $heroSlides->links() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.hero.reorder') }}" class="mt-3">
            @csrf
            <p style="font-size:12px;color:var(--mid);margin-bottom:8px;">Urutkan: masukkan ID slide urut dari atas ke bawah (pisah koma), contoh: 3,1,2</p>
            <div style="display:flex;gap:8px;">
                <input type="text" name="order" class="form-control" placeholder="3,1,4,2" style="max-width:320px;">
                <button type="submit" class="btn-edit">Simpan Urutan</button>
            </div>
            <small style="color:var(--mid);">Alternatif: edit tiap slide dan ubah angka <code>urutan</code>.</small>
        </form>
    @endif
</div>

<!-- Add Hero Modal -->
<div class="modal fade" id="addHeroModal" tabindex="-1" aria-labelledby="addHeroModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.hero.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addHeroModalLabel">Tambah Slide Hero</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Judul *</label>
              <input type="text" class="form-control" name="judul" required maxlength="255" placeholder="JAKARTAPROVCSIRT">
            </div>
            <div class="mb-3">
              <label class="form-label">Subjudul</label>
              <textarea class="form-control" name="subjudul" rows="3" maxlength="1000" placeholder="Deskripsi singkat slide..."></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Gambar (upload)</label>
              <input type="file" class="form-control" name="gambar" accept=".jpg,.jpeg,.png,.webp">
              <small style="color:var(--mid);">jpg/jpeg/png/webp, max 5MB. Disimpan di storage/app/public/hero/</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Atau URL Gambar</label>
              <input type="text" class="form-control" name="gambar_url" placeholder="https://...">
            </div>
            <div class="mb-3">
              <label class="form-label">Tautan CTA</label>
              <input type="text" class="form-control" name="tautan" placeholder="/warnings atau https://...">
            </div>
            <div class="mb-3">
              <label class="form-label">Teks Tautan</label>
              <input type="text" class="form-control" name="teks_tautan" value="LAPOR INSIDEN SEKARANG">
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                  <label class="form-label">Urutan</label>
                  <input type="number" class="form-control" name="urutan" value="0">
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label">Aktif</label>
                  <select class="form-select" name="is_active">
                    <option value="1" selected>Ya</option>
                    <option value="0">Tidak</option>
                  </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
