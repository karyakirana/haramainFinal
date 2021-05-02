<x-metronics-layout>
    <x-slot name="css">
        <link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    </x-slot>

    <x-slot name="subHeader">
        <x-sub-header title="Master" subTitle="Data Supplier"></x-sub-header>
    </x-slot>

    {{-- begin::slot --}}
    <x-metronics-card>

        <x-slot name="title">
            <span class="card-icon">
                <i class="flaticon2-supermarket text-primary"></i>
            </span>
            <h3 class="card-label">Data Supplier</h3>
        </x-slot>

        <x-slot name="toolbar">
            <!--begin::Button-->
            <a href="#" class="btn btn-primary font-weight-bolder" id="btnNew">
                <span class="svg-icon svg-icon-md">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <circle fill="#000000" cx="9" cy="15" r="6" />
                            <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>New Record
            </a>
            <!--end::Button-->
        </x-slot>

        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
            <tr>
                <th>Nomor</th>
                <th>Jenis Supplier</th>
                <th>Supplier</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Keterangan</th>
                <th>Actions</th>
            </tr>
            </thead>
        </table>
        <!--end: Datatable-->

    </x-metronics-card>

    <x-modals modalId="modalCrud" formId="formModal">
        <input type="text" name="id" hidden>
        <div class="form-group row">
            <label class="col-2 col-form-label">Jenis Supplier</label>
            <div class="col-10">
                <select name="jenis" id="jenisSupplier" class="form-control select2"></select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Nama Supplier</label>
            <div class="col-10">
                <input class="form-control" type="text" name="nama">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Telepon</label>
            <div class="col-10">
                <input class="form-control" type="text" name="telepon">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Alamat</label>
            <div class="col-10">
                <textarea class="form-control" name="alamat"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 col-form-label">Keterangan</label>
            <div class="col-10">
                <textarea class="form-control" name="keterangan"></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button type="button"  class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="button" id="btnSubmit" class="btn btn-primary font-weight-bold">Save changes</button>
        </x-slot>
    </x-modals>
    {{-- end::slot --}}

    <x-slot name="scripts">
        <!--begin::Page Vendors(used by this page)-->
        <script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Page Vendors-->
        <!--begin::Page Scripts(used by this page)-->
        <script src="/pages/supplier.js"></script>
        <!--end::Page Scripts-->
    </x-slot>

</x-metronics-layout>
