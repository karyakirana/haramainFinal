<table class="table table-bordered table-hover table-checkable" style="margin-top: 13px !important">
    <thead>
        <tr>
            <th width="10%">Kode</th>
            <th width="20%">Kategori Akun</th>
            <th width="50%">Keterangan</th>
            <th width="20%">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if($kategoriAkun->)
            <tr>
                <td colspan="3">No data</td>
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
