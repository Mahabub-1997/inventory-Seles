@extends('layouts.master')
@section('main-content')
@section('page-css')
@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.EditCustomer') }}</h1>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_edit_client">
    <div class="col-lg-12 mb-3">
        <div class="card">

            <form @submit.prevent="Update_Client()">
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label for="username">{{ __('translate.FullName') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" v-model="client.username" class="form-control"
                                name="username" id="username" placeholder="{{ __('translate.FullName') }}">
                            <span class="error" v-if="errors && errors.username">
                                @{{ errors.username[0] }}
                            </span>
                        </div>


                        <div class="form-group col-md-4">
                            <label for="Phone">{{ __('translate.Phone') }}</label>
                            <input type="text" v-model="client.phone" class="form-control" id="Phone"
                                placeholder="{{ __('translate.Enter_Phone') }}">
                            <span class="error" v-if="errors && errors.phone">
                                @{{ errors.phone[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="city">{{ __('translate.City') }}</label>
                            <input type="text" v-model="client.city" class="form-control" id="city"
                                placeholder="{{ __('translate.Enter_City') }}">
                            <span class="error" v-if="errors && errors.city">
                                @{{ errors.city[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="email">{{ __('translate.Email') }}</label>
                            <input type="text" v-model="client.email" class="form-control" id="email"
                                id="email" placeholder="{{ __('translate.Enter_email_address') }}">
                            <span class="error" v-if="errors && errors.email">
                                @{{ errors.email[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="area_id">{{ __('translate.Area') }}</label>
                            <select v-model="client.area_id" class="form-control" id="area_id" @change="getCenterData(client.area_id)">
                                <option :value="null">--select--</option>
                                <option v-for="area in Areas" :key="area.id" :value="area.id">
                                    @{{area.name}}
                                </option>
                            </select>
                            <span class="error" v-if="errors && errors.area_id">
                                @{{ errors.area_id[0] }}
                            </span>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sub_center_id">{{ __('translate.Sub-center') }}</label>
                            <select v-model="client.sub_center_id" class="form-control" id="sub_center_id">
                                <option :value="null">--select--</option>
                                <option v-for="sc in Subcenters" :key="sc.id" :value="sc.id">
                                    @{{sc.name}}
                                </option>
                            </select>
                            <span class="error" v-if="errors && errors.sub_center_id">
                                @{{ errors.sub_center_id[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="photo">{{ __('translate.Image') }}</label>
                            <input name="photo" @change="changePhoto" type="file" class="form-control"
                                id="photo">
                            <span class="error" v-if="errors && errors.photo">
                                @{{ errors.photo[0] }}
                            </span>
                        </div>

                        <div class="form-group col-md-8">
                            <label for="address">{{ __('translate.Address') }}</label>
                            <textarea v-model="client.address" class="form-control" name="address"
                                id="address"
                                placeholder="{{ __('translate.Address') }}"></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
                                <span v-if="SubmitProcessing" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span> <i class="i-Yes me-2 font-weight-bold"></i>
                                {{ __('translate.Submit') }}
                            </button>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-js')

<script>
    Vue.component('v-select', VueSelect.VueSelect)

    var app = new Vue({
        el: '#section_edit_client',
        data: {
            editmode: false,
            SubmitProcessing:false,
            errors:[],
            Areas:[],
            Subcenters:[],
            client: @json($client),
            data: new FormData(),
            old_photo:@json($client->photo),
        },

        methods: {

            // Selected_Status(value) {
            //     if (value === null) {
            //         this.client.status = 1;
            //     }
            // },


            changePhoto(e){
                let file = e.target.files[0];
                this.client.photo = file;
            },
            Get_Area_Data() {
                axios
                    .get("/api/areas")
                    .then(response => {
                        this.Areas = response.data.areas;
                        console.log('response', this.Areas);
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            },
            getCenterData(id) {
                axios
                    .get("/api/center-by-area/"+id)
                    .then(response => {
                        this.Subcenters = response.data.subcenters;
                        console.log('response', this.Subcenters);
                    })
                    .catch(error => {
                        console.log('error', error);
                    });
            },

            //----------------------- Update_Client ---------------------------\\
            Update_Client() {
                var self = this;
                self.SubmitProcessing = true;
                self.data.append("username", self.client.username);
                self.data.append("status", self.client.status);
                self.data.append("email", self.client.email);
                self.data.append("area_id", self.client.area_id);
                self.data.append("sub_center_id", self.client.sub_center_id);
                self.data.append("city", self.client.city);
                self.data.append("phone", self.client.phone);
                self.data.append("address", self.client.address);
                if(self.old_photo != self.client.photo){
                 self.data.append("photo", self.client.photo);
                }
                self.data.append("_method", "put");

                axios
                    .post("/people/clients/" + this.client.id, self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/people/clients';
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

        },
        //-----------------------------Autoload function-------------------
        mounted() {
            this.Get_Area_Data();
            this.getCenterData(this.client.area_id)
        },
        created() {
        }
    })
</script>

@endsection
