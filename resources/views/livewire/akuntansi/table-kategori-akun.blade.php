<table class="table table-bordered table-hover table-checkable" style="margin-top: 13px !important">
    <thead>
        <tr>
            <th width="10%" class="text-center">Kode</th>
            <th width="20%" class="text-center">Kategori Akun</th>
            <th width="50%" class="text-center">Keterangan</th>
            <th width="20%" class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if($kategoriAkun->count() == 0)
            <tr>
                <td colspan="4" class="text-center">No data</td>
            </tr>
        @endif

        @foreach($kategoriAkun as $row)
            <tr>
                <td>{{ $row->kode }}</td>
                <td>{{ $row->kategori }}</td>
                <td>{{ $row->deskripsi }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
