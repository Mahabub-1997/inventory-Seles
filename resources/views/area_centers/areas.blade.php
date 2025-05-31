@extends('layouts.master')
@section('main-content')
    @section('page-css')
        <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/styles/vendor/nprogress.css')}}">
    @endsection

    <div class="breadcrumb">
        <h1>{{ __('translate.Area') }}</h1>
    </div>

    <div class="separator-breadcrumb border-top"></div>


    <div class="row" id="section_Category_list">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-end mb-3">
                        <a class="new_area btn btn-outline-primary btn-md m-1"><i class="i-Add me-2 font-weight-bold"></i>
                            {{ __('translate.Create') }}</a>
                    </div>

                    <div class="table-responsive">
                        <table id="area_table" class="display table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>{{ __('translate.Name') }}</th>
                                <th>{{ __('translate.Status') }}</th>
                                <th class="not_show">{{ __('translate.Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Add & Edit category -->
        <div class="modal fade" id="modal_Area" tabindex="-1" role="dialog" aria-labelledby="modal_Area"
             aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editmode" class="modal-title">{{ __('translate.Edit') }}</h5>
                        <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="editmode?Update_Area():Create_Area()" enctype="multipart/form-data">
                            <div class="row">

                                <div class="form-group col-md-12">
                                    <label for="code">{{ __('translate.Name') }}<span class="field_required">*</span></label>
                                    <input type="text" v-model="area.name" class="form-control" name="name" id="name"
                                           placeholder="{{ __('translate.Enter Area Name') }}">
                                    <span class="error" v-if="errors && errors.name">
                  @{{ errors.name[0] }}
                </span>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="name">{{ __('translate.Status') }}<span class="field_required">*</span></label>
                                    <select v-model="area.status" class="form-control" name="status" id="status">
                                        <option :value="1">{{ __('translate.Active') }}</option>
                                        <option :value="0">{{ __('translate.Inactive') }}</option>
                                    </select>
                                    <span class="error" v-if="errors && errors.status">
                  @{{ errors.status[0] }}
                </span>
                                </div>

                            </div>
                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
                  <span v-if="SubmitProcessing" class="spinner-border spinner-border-sm" role="status"
                        aria-hidden="true"></span> <i class="i-Yes me-2 font-weight-bold"></i> {{ __('translate.Submit') }}
                                    </button>
                                </div>
                            </div>


                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('page-js')

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/nprogress.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            "use strict";

            $(document).ready(function () {
                //init datatable
                Area_datatable();
            });

            //Get Data
            function Area_datatable(){
                var table = $('#area_table').DataTable({
                    processing: true,
                    serverSide: true,
                    "order": [[ 0, "desc" ]],
                    'columnDefs': [
                        {
                            'targets': [0],
                            'visible': false,
                            'searchable': false,
                        },
                    ],
                    ajax: "{{ route('area.index') }}",
                    columns: [
                        {data: 'id' , name: 'id',className: "d-none"},
                        {data: 'name', name: 'name'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},

                    ],

                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    dom: "<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5 p-0'f>>rtip",
                    oLanguage: {
                        sEmptyTable: "{{ __('datatable.sEmptyTable') }}",
                        sInfo: "{{ __('datatable.sInfo') }}",
                        sInfoEmpty: "{{ __('datatable.sInfoEmpty') }}",
                        sInfoFiltered: "{{ __('datatable.sInfoFiltered') }}",
                        sInfoThousands: "{{ __('datatable.sInfoThousands') }}",
                        sLengthMenu: "_MENU_",
                        sLoadingRecords: "{{ __('datatable.sLoadingRecords') }}",
                        sProcessing: "{{ __('datatable.sProcessing') }}",
                        sSearch: "",
                        sSearchPlaceholder: "{{ __('datatable.sSearchPlaceholder') }}",
                        oPaginate: {
                            sFirst: "{{ __('datatable.oPaginate.sFirst') }}",
                            sLast: "{{ __('datatable.oPaginate.sLast') }}",
                            sNext: "{{ __('datatable.oPaginate.sNext') }}",
                            sPrevious: "{{ __('datatable.oPaginate.sPrevious') }}",
                        },
                        oAria: {
                            sSortAscending: "{{ __('datatable.oAria.sSortAscending') }}",
                            sSortDescending: "{{ __('datatable.oAria.sSortDescending') }}",
                        }
                    },
                    buttons: [
                        {
                            extend: 'collection',
                            text: "{{ __('translate.EXPORT') }}",
                            buttons: [
                                {
                                    extend: 'print',
                                    text: 'print',
                                    exportOptions: {
                                        columns: ':visible:Not(.not_show)',
                                        rows: ':visible'
                                    },
                                },
                                {
                                    extend: 'pdf',
                                    text: 'pdf',
                                    exportOptions: {
                                        columns: ':visible:Not(.not_show)',
                                        rows: ':visible'
                                    },
                                },
                                {
                                    extend: 'excel',
                                    text: 'excel',
                                    exportOptions: {
                                        columns: ':visible:Not(.not_show)',
                                        rows: ':visible'
                                    },
                                },
                                {
                                    extend: 'csv',
                                    text: 'csv',
                                    exportOptions: {
                                        columns: ':visible:Not(.not_show)',
                                        rows: ':visible'
                                    },
                                },
                            ]
                        }]
                });
            }

            // event reload Datatatble
            $(document).bind('event_area', function (e) {
                $('#modal_Area').modal('hide');
                $('#area_table').DataTable().destroy();
                Area_datatable();
            });


            //Create Category
            $(document).on('click', '.new_area', function () {
                app.editmode = false;
                app.reset_Form();
                $('#modal_Area').modal('show');
            });

            //Edit Category
            $(document).on('click', '.edit', function () {
                NProgress.start();
                NProgress.set(0.1);
                app.editmode = true;
                app.reset_Form();
                var id = $(this).attr('id');
                app.Get_Data_Edit(id);

                setTimeout(() => {
                    NProgress.done()
                    $('#modal_Area').modal('show');
                }, 500);
            });

            //Delete Category
            $(document).on('click', '.delete', function () {
                var id = $(this).attr('id');
                app.Remove_Category(id);
            });
        });
    </script>

    <script>
        var app = new Vue({
            el: '#section_Category_list',
            data: {
                editmode: false,
                SubmitProcessing:false,
                errors:[],
                areas: [],
                area: {
                    id: "",
                    name: "",
                    status: 1
                }
            },

            methods: {



                //------------------------------ Modal  (create category) -------------------------------\\
                new_area() {
                    this.reset_Form();
                    this.editmode = false;
                    $('#modal_Area').modal('show');
                },

                //--------------------------- reset Form ----------------\\
                reset_Form() {
                    this.area = {
                        id: "",
                        name: "",
                        status: 1
                    };
                    this.errors = {};
                },

                //---------------------- Get_Data_Edit  ------------------------------\\
                Get_Data_Edit(id) {
                    axios
                        .get("/products/categories/"+id+"/edit")
                        .then(response => {
                            this.area   = response.data.area;
                        })
                        .catch(error => {

                        });
                },

                //------------------------ Create_Category---------------------------\\
                Create_Area() {
                    var self = this;
                    self.SubmitProcessing = true;
                    axios
                        .post("/area-center/area", {
                            name: this.area.name,
                            status: this.area.status
                        })
                        .then(response => {
                            self.SubmitProcessing = false;
                            $.event.trigger('event_area');
                            toastr.success('{{ __('translate.Created_in_successfully') }}');
                            self.errors = {};
                        })
                        .catch(error => {
                            self.SubmitProcessing = false;
                            if (error.response.status == 422) {
                                self.errors = error.response.data.errors;
                            }
                            toastr.error('{{ __('translate.There_was_something_wronge') }}');
                        });
                },

                //----------------------- Update_Category ---------------------------\\
                Update_Area() {
                    var self = this;
                    self.SubmitProcessing = true;
                    axios
                        .put("/products/categories/" + this.area.id, {
                            name: this.area.name,
                            status: this.area.status
                        })
                        .then(response => {
                            self.SubmitProcessing = false;
                            $.event.trigger('event_area');
                            toastr.success('{{ __('translate.Updated_in_successfully') }}');
                            self.errors = {};
                        })
                        .catch(error => {
                            self.SubmitProcessing = false;
                            if (error.response.status == 422) {
                                self.errors = error.response.data.errors;
                            }
                            toastr.error('{{ __('translate.There_was_something_wronge') }}');
                        });
                },

                //--------------------------------- Remove_Category ---------------------------\\
                Remove_Category(id) {

                    swal({
                        title: '{{ __('translate.Are_you_sure') }}',
                        text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#0CC27E',
                        cancelButtonColor: '#FF586B',
                        confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                        cancelButtonText: '{{ __('translate.No_cancel') }}',
                        confirmButtonClass: 'btn btn-primary me-5',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    }).then(function () {
                        axios
                            .delete("/products/categories/" + id)
                            .then(() => {
                                $.event.trigger('event_area');
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
                },



            },
            //-----------------------------Autoload function-------------------
            created() {
            }

        })

    </script>



@endsection
