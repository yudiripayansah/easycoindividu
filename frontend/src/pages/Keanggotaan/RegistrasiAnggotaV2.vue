<template>
    <div>
        <h1 class="mb-5">{{ $route.name }}</h1>
        <b-overlay :show="table.showOverlay" rounded="sm">
            <b-card>
                <b-row no-gutters>
                    <b-col
                        cols="12"
                        class="d-flex justify-content-end mb-5 pb-5 border-bottom"
                    >
                        <b-button
                            variant="success"
                            @click="
                                $bvModal.show('modal-form');
                                doClearForm();
                            "
                        >
                            <b-icon icon="plus" />
                            Tambah Baru
                        </b-button>
                    </b-col>
                    <b-col cols="12" class="mb-5">
                        <b-row no-gutters>
                            <b-col cols="6">
                                <div class="w-100 max-200 pr-5">
                                    <b-input-group
                                        size="sm"
                                        prepend="Per Halaman"
                                    >
                                        <b-form-select
                                            v-model="paging.perPage"
                                            :options="opt.perPage"
                                            @change="doGet()"
                                        />
                                    </b-input-group>
                                </div>
                            </b-col>
                            <b-col cols="6" class="d-flex justify-content-end">
                                <div class="w-100 max-300">
                                    <b-input-group size="sm">
                                        <b-form-input v-model="paging.search" />
                                        <b-input-group-append>
                                            <b-button
                                                size="sm"
                                                text="Button"
                                                variant="primary"
                                                @click="doGet()"
                                            >
                                                <b-icon icon="search" />
                                                Cari
                                            </b-button>
                                        </b-input-group-append>
                                    </b-input-group>
                                </div>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col cols="12">
                        <b-table
                            responsive
                            bordered
                            outlined
                            small
                            striped
                            hover
                            :fields="table.fields"
                            :items="table.items"
                            :sort-by.sync="paging.sortBy"
                            :sort-desc.sync="paging.sortDesc"
                            show-empty
                            :emptyText="
                                table.loading
                                    ? 'Memuat data...'
                                    : 'Tidak ada data'
                            "
                        >
                            <template #cell(no)="item">
                                {{ item.index + 1 }}
                            </template>
                            <template #cell(action)="item">
                                <b-button
                                    variant="danger"
                                    size="xs"
                                    class="mx-1"
                                    @click="doDelete(item, true)"
                                >
                                    <b-icon icon="trash" />
                                </b-button>
                                <b-button
                                    variant="success"
                                    size="xs"
                                    class="mx-1"
                                    @click="doUpdate(item, false)"
                                >
                                    <b-icon icon="pencil" />
                                </b-button>
                            </template>
                        </b-table>
                    </b-col>
                    <b-col cols="12" class="justify-content-end d-flex">
                        <b-pagination
                            v-model="paging.page"
                            :total-rows="table.totalRows"
                            :per-page="paging.perPage"
                        >
                        </b-pagination>
                    </b-col>
                </b-row>
            </b-card>
        </b-overlay>

        <b-modal
            title="Form Registrasi Anggota"
            id="modal-form"
            hide-footer
            size="lg"
            centered
        >
            <b-overlay :show="form.showOverlay" rounded="sm">
                <b-row>
                    <b-col md="8" offset-md="2">
                        <ValidationObserver
                            ref="observer"
                            v-slot="{ handleSubmit }"
                        >
                            <b-form
                                @submit.stop.prevent="handleSubmit(onSubmit)"
                            >
                                <ValidationProvider
                                    name="Cabang"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-kode_cabang"
                                        label-for="input-kode_cabang"
                                    >
                                        <template>
                                            Cabang:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-select
                                            id="input-kode_cabang"
                                            name="input-kode_cabang"
                                            v-model="form.data.kode_cabang"
                                            :options="opt.kode_cabang"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-kode_cabang-live-feedback"
                                        ></b-form-select>

                                        <b-form-invalid-feedback
                                            id="input-kode_cabang-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Nama Lengkap"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-nama_anggota"
                                        label-for="input-nama_anggota"
                                    >
                                        <template>
                                            Nama Lengkap (Sesuai KTP):
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-nama_anggota"
                                            name="input-nama_anggota"
                                            v-model="form.data.nama_anggota"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-nama_anggota-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-nama_anggota-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Jenis Kelamin"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-jenis_kelamin"
                                        label-for="input-jenis_kelamin"
                                    >
                                        <template>
                                            Jenis Kelamin:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-select
                                            id="input-jenis_kelamin"
                                            name="input-jenis_kelamin"
                                            v-model="form.data.jenis_kelamin"
                                            :options="opt.jenis_kelamin"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-jenis_kelamin-live-feedback"
                                        ></b-form-select>

                                        <b-form-invalid-feedback
                                            id="input-jenis_kelamin-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Ibu Kandung"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-ibu_kandung"
                                        label-for="input-ibu_kandung"
                                    >
                                        <template>
                                            Ibu Kandung:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-ibu_kandung"
                                            name="input-ibu_kandung"
                                            v-model="form.data.ibu_kandung"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-ibu_kandung-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-ibu_kandung-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <b-row>
                                    <b-col>
                                        <ValidationProvider
                                            name="Tempat Lahir"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-tempat_lahir"
                                                label-for="input-tempat_lahir"
                                            >
                                                <template>
                                                    Tempat Lahir:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-tempat_lahir"
                                                    name="input-tempat_lahir"
                                                    v-model="
                                                        form.data.tempat_lahir
                                                    "
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-tempat_lahir-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-tempat_lahir-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                    <b-col>
                                        <ValidationProvider
                                            name="Tanggal Lahir"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-tgl_lahir"
                                                label-for="input-tgl_lahir"
                                            >
                                                <template>
                                                    Tanggal Lahir:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-input-group class="mb-3">
                                                    <b-form-input
                                                        id="input-tgl_lahir"
                                                        name="input-tgl_lahir"
                                                        v-model="
                                                            form.data.tgl_lahir
                                                        "
                                                        :state="
                                                            getValidationState(
                                                                validationContext
                                                            )
                                                        "
                                                        aria-describedby="input-tgl_lahir-live-feedback"
                                                    ></b-form-input>
                                                    <b-input-group-append>
                                                        <b-form-datepicker
                                                            button-only
                                                            right
                                                            :date-format-options="{
                                                                day: 'numeric',
                                                                month: 'numeric',
                                                                year: 'numeric',
                                                            }"
                                                            locale="en"
                                                            :max="maxDate"
                                                            v-model="
                                                                form.data
                                                                    .tgl_lahir
                                                            "
                                                        ></b-form-datepicker>
                                                    </b-input-group-append>
                                                    <b-form-invalid-feedback
                                                        id="input-tgl_lahir-live-feedback"
                                                        >{{
                                                            validationContext
                                                                .errors[0]
                                                        }}</b-form-invalid-feedback
                                                    >
                                                </b-input-group>
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                </b-row>

                                <ValidationProvider
                                    name="No. KTP"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-no_ktp"
                                        label-for="input-no_ktp"
                                    >
                                        <template>
                                            No. KTP:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-no_ktp"
                                            name="input-no_ktp"
                                            v-model="form.data.no_ktp"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-no_ktp-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-no_ktp-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Doc. KTP"
                                    rules="image"
                                >
                                    <b-form-group
                                        id="input-group-doc_ktp"
                                        label="Dok. KTP:"
                                        label-for="doc_ktp"
                                    >
                                        <b-form-file
                                            accept="image/jpeg, image/png, image/gif"
                                            v-model="doc_ktp_base64"
                                            placeholder="Choose a file or drop it here..."
                                            drop-placeholder="Drop file here..."
                                        ></b-form-file>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="No. NPWP"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-no_npwp"
                                        label-for="input-no_npwp"
                                    >
                                        <template>
                                            No. NPWP:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-no_npwp"
                                            name="input-no_npwp"
                                            v-model="form.data.no_npwp"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-no_npwp-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-no_npwp-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="No. Telp"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-no_telp"
                                        label-for="input-no_telp"
                                    >
                                        <template>
                                            No. Telp:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-no_telp"
                                            name="input-no_telp"
                                            v-model="form.data.no_telp"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-no_telp-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-no_telp-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Alamat"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-alamat"
                                        label-for="input-alamat"
                                    >
                                        <template>
                                            Alamat:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-textarea
                                            id="input-alamat"
                                            name="input-alamat"
                                            v-model="form.data.alamat"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            rows="3"
                                            max-rows="6"
                                            aria-describedby="input-alamat-live-feedback"
                                        ></b-form-textarea>

                                        <b-form-invalid-feedback
                                            id="input-alamat-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <b-row>
                                    <b-col>
                                        <ValidationProvider
                                            name="Desa"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-desa"
                                                label-for="input-desa"
                                            >
                                                <template>
                                                    Desa:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-desa"
                                                    name="input-desa"
                                                    v-model="form.data.desa"
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-desa-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-desa-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                    <b-col>
                                        <ValidationProvider
                                            name="Kecamatan"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-kecamatan"
                                                label-for="input-kecamatan"
                                            >
                                                <template>
                                                    Kecamatan:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-kecamatan"
                                                    name="input-kecamatan"
                                                    v-model="
                                                        form.data.kecamatan
                                                    "
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-kecamatan-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-kecamatan-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                </b-row>
                                <b-row>
                                    <b-col>
                                        <ValidationProvider
                                            name="Kabupaten"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-kabupaten"
                                                label-for="input-kabupaten"
                                            >
                                                <template>
                                                    Kabupaten:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-kabupaten"
                                                    name="input-kabupaten"
                                                    v-model="
                                                        form.data.kabupaten
                                                    "
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-kabupaten-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-kabupaten-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                    <b-col>
                                        <ValidationProvider
                                            name="Kode Pos"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-kodepos"
                                                label-for="input-kodepos"
                                            >
                                                <template>
                                                    Kode Pos:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-kodepos"
                                                    name="input-kodepos"
                                                    v-model="form.data.kodepos"
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-kodepos-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-kodepos-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                </b-row>

                                <ValidationProvider
                                    name="Pendidikan"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-pendidikan"
                                        label-for="input-pendidikan"
                                    >
                                        <template>
                                            Pendidikan:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-pendidikan"
                                            name="input-pendidikan"
                                            v-model="form.data.pendidikan"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-pendidikan-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-pendidikan-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <b-row>
                                    <b-col>
                                        <ValidationProvider
                                            name="Pekerjaan"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-pekerjaan"
                                                label-for="input-pekerjaan"
                                            >
                                                <template>
                                                    Pekerjaan:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-pekerjaan"
                                                    name="input-pekerjaan"
                                                    v-model="
                                                        form.data.pekerjaan
                                                    "
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-pekerjaan-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-pekerjaan-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                    <b-col>
                                        <ValidationProvider
                                            name="Ket Pekerjaan"
                                            v-slot="validationContext"
                                        >
                                            <b-form-group
                                                id="input-group-ket_pekerjaan"
                                                label-for="input-ket_pekerjaan"
                                            >
                                                <template>
                                                    Ket Pekerjaan:
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </template>
                                                <b-form-input
                                                    id="input-ket_pekerjaan"
                                                    name="input-ket_pekerjaan"
                                                    v-model="
                                                        form.data.ket_pekerjaan
                                                    "
                                                    :state="
                                                        getValidationState(
                                                            validationContext
                                                        )
                                                    "
                                                    aria-describedby="input-ket_pekerjaan-live-feedback"
                                                ></b-form-input>

                                                <b-form-invalid-feedback
                                                    id="input-ket_pekerjaan-live-feedback"
                                                    >{{
                                                        validationContext
                                                            .errors[0]
                                                    }}</b-form-invalid-feedback
                                                >
                                            </b-form-group>
                                        </ValidationProvider>
                                    </b-col>
                                </b-row>

                                <ValidationProvider
                                    name="Pendapatan Perbulan"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-pendapatan_perbulan"
                                        label-for="input-pendapatan_perbulan"
                                    >
                                        <template>
                                            Pendapatan Perbulan:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            type="number"
                                            id="input-pendapatan_perbulan"
                                            name="input-pendapatan_perbulan"
                                            v-model="
                                                form.data.pendapatan_perbulan
                                            "
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-pendapatan_perbulan-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-pendapatan_perbulan-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Status Pernikahan"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-status_perkawinan"
                                        label-for="input-status_perkawinan"
                                    >
                                        <template>
                                            Status Pernikahan:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-select
                                            id="input-status_perkawinan"
                                            name="input-status_perkawinan"
                                            v-model="
                                                form.data.status_perkawinan
                                            "
                                            :options="opt.status_perkawinan"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-status_perkawinan-live-feedback"
                                        ></b-form-select>

                                        <b-form-invalid-feedback
                                            id="input-status_perkawinan-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Nama Pasangan (Sesuai KTP)"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        id="input-group-nama_pasangan"
                                        label-for="input-nama_pasangan"
                                    >
                                        <template>
                                            Nama Pasangan (Sesuai KTP):
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            id="input-nama_pasangan"
                                            name="input-nama_pasangan"
                                            v-model="form.data.nama_pasangan"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-nama_pasangan-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-nama_pasangan-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <b-form-group
                                    id="input-group-ttd_anggota"
                                    label-for="ttd_anggota"
                                >
                                    <template>
                                        Tanda Tangan:
                                        <span class="text-danger">*</span>
                                    </template>
                                    <vueSignature
                                        ref="signature"
                                        :sigOption="signature.option"
                                        :w="'500px'"
                                        :h="'250px'"
                                        :disabled="signature.disabled"
                                    ></vueSignature>
                                    <b-button
                                        size="sm"
                                        type="button"
                                        variant="secondary"
                                        @click="signatureClear"
                                        >Clear</b-button
                                    >
                                    <b-button
                                        size="sm"
                                        type="button"
                                        variant="secondary"
                                        @click="signatureUndo"
                                        >Undo</b-button
                                    >
                                </b-form-group>

                                <b-col
                                    cols="12"
                                    class="d-flex justify-content-end border-top pt-5"
                                >
                                    <b-button
                                        variant="secondary"
                                        @click="$bvModal.hide('modal-form')"
                                        :disabled="form.loading"
                                        >Cancel
                                    </b-button>
                                    <b-button
                                        variant="primary"
                                        type="submit"
                                        :disabled="form.loading"
                                        class="ml-3"
                                    >
                                        {{
                                            form.loading
                                                ? "Memproses..."
                                                : "Simpan"
                                        }}
                                    </b-button>
                                </b-col>
                            </b-form>
                        </ValidationObserver>
                    </b-col>
                </b-row>
            </b-overlay>
        </b-modal>

        <b-modal
            title="Delete"
            id="modal-delete"
            hide-footer
            size="sm"
            header-bg-variant="warning"
            body-bg-variant="warning"
            centered
        >
            <p class="text-center py-3">Anda yakin ingin menghapus data ini?</p>
            <div class="d-flex justify-content-end">
                <b-button
                    variant="light"
                    type="button"
                    :disabled="remove.loading"
                    @click="$bvModal.hide('modal-delete')"
                    >Tidak
                </b-button>
                <b-button
                    variant="danger"
                    class="ml-3"
                    type="button"
                    :disabled="remove.loading"
                    @click="doDelete(remove.data, false)"
                >
                    {{ remove.loading ? "Memproses..." : "Ya" }}
                </b-button>
            </div>
        </b-modal>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
import vueSignature from "vue-signature";
import {
    ValidationObserver,
    ValidationProvider,
    extend,
    localize,
} from "vee-validate";
import en from "vee-validate/dist/locale/en.json";
import * as rules from "vee-validate/dist/rules";
import helper from "@/core/helper";
import easycoApi from "@/core/services/easyco.service";

// Install VeeValidate rules and localization
Object.keys(rules).forEach((rule) => {
    extend(rule, rules[rule]);
});

localize("en", en);

export default {
    name: "RegistrasiAnggota",
    components: {
        vueSignature,
        ValidationObserver,
        ValidationProvider,
    },
    data() {
        const now = new Date();
        const today = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate()
        );
        const maxDate = new Date(today);

        return {
            maxDate: maxDate,
            signature: {
                option: {
                    penColor: "rgb(0, 0, 0)",
                    backgroundColor: "rgb(255,255,255)",
                },
                disabled: false,
            },
            doc_ktp_base64: null,
            form: {
                data: {
                    id: null,
                    kode_cabang: null,
                    nama_anggota: null,
                    jenis_kelamin: null,
                    ibu_kandung: null,
                    tempat_lahir: null,
                    tgl_lahir: null,
                    no_ktp: null,
                    doc_ktp: null,
                    no_npwp: null,
                    no_telp: null,
                    alamat: null,
                    desa: null,
                    kecamatan: null,
                    kabupaten: null,
                    kodepos: null,
                    pendidikan: null,
                    pekerjaan: null,
                    ket_pekerjaan: null,
                    pendapatan_perbulan: null,
                    status_perkawinan: null,
                    nama_pasangan: null,
                    ttd_anggota: null,
                },
                loading: false,
            },
            table: {
                fields: [
                    {
                        key: "no",
                        sortable: false,
                        label: "No",
                        thClass: "text-center w-5p",
                        tdClass: "text-center",
                    },
                    {
                        key: "nama_anggota",
                        sortable: true,
                        label: "Nama Anggota",
                        thClass: "text-center",
                        tdClass: "",
                    },
                    {
                        key: "kode_cabang",
                        sortable: true,
                        label: "Kode Cabang",
                        thClass: "text-center",
                        tdClass: "",
                    },
                    {
                        key: "kode_rembug",
                        sortable: true,
                        label: "Kode Majelis",
                        thClass: "text-center",
                        tdClass: "",
                    },
                    {
                        key: "no_telp",
                        sortable: true,
                        label: "No Telp",
                        thClass: "text-center",
                        tdClass: "",
                    },
                    {
                        key: "alamat",
                        sortable: true,
                        label: "Alamat",
                        thClass: "text-center",
                        tdClass: "",
                    },
                    {
                        key: "action",
                        sortable: false,
                        label: "Action",
                        thClass: "text-center w-10p",
                        tdClass: "text-center",
                    },
                ],
                items: [],
                totalRows: 0,
                loading: false,
                showOverlay: false,
            },
            paging: {
                page: 1,
                perPage: 10,
                sortDesc: true,
                sortBy: "id",
                search: "",
                status: 0,
            },
            remove: {
                data: Object,
                loading: false,
            },
            opt: {
                kode_cabang: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                ],
                kode_rembug: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                ],
                jenis_kelamin: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                    {
                        value: "P",
                        text: "Pria",
                    },
                    {
                        value: "W",
                        text: "Wanita",
                    },
                ],
                pendidikan: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                    {
                        text: "Tidak Diketahui",
                        value: "0",
                    },
                    {
                        text: "SD / MI",
                        value: "1",
                    },
                    {
                        text: "SMP / MTs",
                        value: "2",
                    },
                    {
                        text: "SMK / SMA / MA",
                        value: "3",
                    },
                    {
                        text: "D1",
                        value: "4",
                    },
                    {
                        text: "D2",
                        value: "5",
                    },
                    {
                        text: "D3",
                        value: "6",
                    },
                    {
                        text: "S1",
                        value: "7",
                    },
                    {
                        text: "S2",
                        value: "8",
                    },
                    {
                        text: "S3",
                        value: "9",
                    },
                ],
                status_perkawinan: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                    {
                        text: "Belum Menikah",
                        value: "0",
                    },
                    {
                        text: "Menikah",
                        value: "1",
                    },
                    {
                        text: "Janda/ Duda",
                        value: "2",
                    },
                ],
                pekerjaan: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                    {
                        text: "Tidak Diketahui",
                        value: "0",
                    },
                    {
                        text: "Ibu Rumah Tangga",
                        value: "1",
                    },
                    {
                        text: "Buruh",
                        value: "2",
                    },
                    {
                        text: "Petani",
                        value: "3",
                    },
                    {
                        text: "Pedagang",
                        value: "4",
                    },
                    {
                        text: "Wiraswasta",
                        value: "5",
                    },
                    {
                        text: "Karyawan Swasta",
                        value: "6",
                    },
                    {
                        text: "PNS",
                        value: "7",
                    },
                ],
                perPage: [10, 20, 50, 100, 200],
            },
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["user"]),
    },
    watch: {
        paging: {
            handler(val) {
                this.doGet();
            },
            deep: true,
        },
        doc_ktp_base64: function (newVal) {
            if (newVal && newVal != null) {
                this.createBase64Image(newVal);
            } else {
                this.doc_ktp = null;
            }
        },
    },
    mounted() {
        this.doGet();
        this.doGetCabang();
    },
    methods: {
        ...helper,

        createBase64Image: function (FileObject) {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.form.data.doc_ktp = event.target.result;
            };
            reader.readAsDataURL(FileObject);
        },
        signatureClear() {
            var _this = this;
            _this.$refs.signature.clear();
        },
        signatureUndo() {
            var _this = this;
            _this.$refs.signature.undo();
        },
        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        async doGetCabang() {
            let payload = {
                perPage: "~",
                page: 1,
                sortBy: "nama_cabang",
                sortDir: "ASC",
                search: "",
            };
            try {
                let req = await easycoApi.cabangRead(payload, this.user.token);
                let { data, status, msg } = req.data;
                if (status) {
                    this.opt.kode_cabang = [
                        {
                            value: null,
                            text: "Please select an option",
                        },
                    ];
                    if (data.length > 0) {
                        data.map((item) => {
                            this.opt.kode_cabang.push({
                                value: item.kode_cabang,
                                text: item.nama_cabang,
                            });
                        });
                    }
                }
            } catch (error) {
                console.error(error);
            }
        },
        async doGet() {
            let payload = this.paging;
            payload.sortDir = payload.sortDesc ? "DESC" : "ASC";
            this.table.loading = true;
            try {
                let req = await easycoApi.anggotaRead(payload, this.user.token);
                let { data, status, msg, total } = req.data;
                if (status) {
                    this.table.items = data;
                    this.table.totalRows = total;
                } else {
                    this.notify("danger", "Error", msg);
                }
                this.table.loading = false;
            } catch (error) {
                this.table.loading = false;
                console.error(error);
                this.notify("danger", "Login Error", error);
            }
        },
        async onSubmit() {
            this.form.loading = true;
            try {
                let payload = new FormData();
                let payloadData = { ...this.form.data };

                // payloadData.created_by = this.user.id;
                // payloadData.status = 0;
                payloadData.ttd_anggota = this.$refs.signature.save();

                for (let key in payloadData) {
                    payload.append(key, payloadData[key]);
                }

                let req = false;
                if (payload.id) {
                    req = await easycoApi.anggotaUpdate(
                        payload,
                        this.user.token
                    );
                } else {
                    req = await easycoApi.anggotaCreate(
                        payload,
                        this.user.token
                    );
                }
                let { data, status, msg } = req.data;
                if (status) {
                    this.doGet();
                    this.$bvModal.hide("modal-form");
                    this.doClearForm();
                    this.notify("success", "Success", msg);
                } else {
                    this.notify("danger", "Error", msg);
                }
                this.form.loading = false;
            } catch (error) {
                this.form.loading = false;
                console.error(error);
                this.notify("danger", "Login Error", error);
            }
        },
        async doUpdate(data) {
            let id = data.item.id;
            try {
                const payload = `id=${id}`;
                let req = await easycoApi.anggotaDetail(
                    payload,
                    this.user.token
                );
                let { data, status, msg } = req.data;
                if (status) {
                    this.doGetCabang();
                    this.form.data = data;
                    this.$bvModal.show("modal-form");
                } else {
                    this.notify("danger", "Error", msg);
                }
            } catch (error) {
                console.error(error);
            }
        },
        async doDelete(data, dialog) {
            if (dialog) {
                console.log("dialog:", data);
                this.$bvModal.show("modal-delete");
                this.remove.data = data.item;
            } else {
                console.log("on delete:", data);
                let id = this.remove.data.id;
                try {
                    this.remove.loading = true;
                    const payload = `id=${id}`;
                    let req = await easycoApi.anggotaDelete(
                        payload,
                        this.user.token
                    );
                    let { data, status, msg } = req.data;
                    if (status) {
                        this.$bvModal.hide("modal-delete");
                        this.doGet();
                        this.remove.data = Object;
                        this.notify("success", "Success", msg);
                    } else {
                        this.notify("danger", "Error", msg);
                    }
                    this.remove.loading = false;
                } catch (error) {
                    console.log(error);
                    this.notify("danger", "Error", error);
                }
            }
        },
        doClearForm() {
            this.form.data = {
                id: null,
                kode_cabang: null,
                nama_anggota: null,
                jenis_kelamin: null,
                ibu_kandung: null,
                tempat_lahir: null,
                tgl_lahir: null,
                no_ktp: null,
                doc_ktp: null,
                no_npwp: null,
                no_telp: null,
                alamat: null,
                desa: null,
                kecamatan: null,
                kabupaten: null,
                kodepos: null,
                pendidikan: null,
                pekerjaan: null,
                ket_pekerjaan: null,
                pendapatan_perbulan: null,
                status_perkawinan: null,
                nama_pasangan: null,
                ttd_anggota: null,
            };

            // this.$nextTick(() => {
            //     this.$refs.observer.reset();
            // });
        },
        notify(type, title, msg) {
            this.$bvToast.toast(msg, {
                title: title,
                autoHideDelay: 5000,
                variant: type,
                toaster: "b-toaster-bottom-right",
                appendToast: true,
            });
        },
    },
};
</script>
