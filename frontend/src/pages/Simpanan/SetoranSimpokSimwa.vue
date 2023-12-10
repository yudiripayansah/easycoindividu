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
                            <template #cell(trx_type)="item">
                                <div v-if="item.item.trx_type === 1">
                                    Simpok
                                </div>
                                <div v-else-if="item.item.trx_type === 2">
                                    Simwa
                                </div>
                                <div v-else>-</div>
                            </template>
                            <template #cell(amount)="item">
                                Rp {{ numberFormat(item.item.amount, 0) }}
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
            :title="'Form ' + $route.name"
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
                                    name="No Anggota"
                                    v-slot="validationContext"
                                    :rules="{ required: true }"
                                >
                                    <b-form-group
                                        id="input-group-no_anggota"
                                        label-for="input-no_anggota"
                                    >
                                        <template>
                                            No Anggota:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-select
                                            id="input-no_anggota"
                                            name="input-no_anggota"
                                            v-model="form.data.no_anggota"
                                            :options="opt.no_anggota"
                                            aria-describedby="input-no_anggota-live-feedback"
                                        ></b-form-select>

                                        <b-form-invalid-feedback
                                            id="input-no_anggota-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

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
                                        disabled
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="input-group-simpok"
                                    label-for="input-simpok"
                                >
                                    <template>
                                        Saldo Simpanan Pokok:
                                        <span class="text-danger">*</span>
                                    </template>
                                    <b-form-input
                                        id="input-simpok"
                                        name="input-simpok"
                                        v-model="form.data.simpok"
                                        class="text-right"
                                        disabled
                                    ></b-form-input>
                                </b-form-group>

                                <b-form-group
                                    id="input-group-simwa"
                                    label-for="input-simwa"
                                >
                                    <template>
                                        Saldo Simpanan Wajib:
                                        <span class="text-danger">*</span>
                                    </template>
                                    <b-form-input
                                        id="input-simwa"
                                        name="input-simwa"
                                        v-model="form.data.simwa"
                                        class="text-right"
                                        disabled
                                    ></b-form-input>
                                </b-form-group>

                                <hr />

                                <ValidationProvider
                                    name="Tanggal Transaksi"
                                    v-slot="validationContext"
                                    :rules="{ required: true }"
                                >
                                    <b-form-group
                                        id="input-group-tanggal_transaksi"
                                        label-for="input-tanggal_transaksi"
                                    >
                                        <template>
                                            Tanggal Transaksi:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-datepicker
                                            :date-format-options="{
                                                day: 'numeric',
                                                month: 'numeric',
                                                year: 'numeric',
                                            }"
                                            locale="en"
                                            :max="maxDate"
                                            id="input-tanggal_transaksi"
                                            name="input-tanggal_transaksi"
                                            v-model="
                                                form.data.tanggal_transaksi
                                            "
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-tanggal_transaksi-live-feedback"
                                        ></b-form-datepicker>

                                        <b-form-invalid-feedback
                                            id="input-tanggal_transaksi-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Jenis Setoran"
                                    v-slot="validationContext"
                                    :rules="{ required: true }"
                                >
                                    <b-form-group
                                        id="input-group-no_anggota"
                                        label-for="input-no_anggota"
                                    >
                                        <template>
                                            Jenis Setoran:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-select
                                            id="input-jenis_setoran"
                                            name="input-jenis_setoran"
                                            v-model="form.data.jenis_setoran"
                                            :options="opt.jenis_setoran"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-jenis_setoran-live-feedback"
                                        ></b-form-select>

                                        <b-form-invalid-feedback
                                            id="input-jenis_setoran-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Jumlah Setoran"
                                    v-slot="validationContext"
                                    :rules="{ required: true }"
                                >
                                    <b-form-group
                                        id="input-group-jumlah_setoran"
                                        label-for="input-jumlah_setoran"
                                    >
                                        <template>
                                            Jumlah Setoran:
                                            <span class="text-danger">*</span>
                                        </template>
                                        <b-form-input
                                            type="number"
                                            id="input-jumlah_setoran"
                                            name="input-jumlah_setoran"
                                            v-model="form.data.jumlah_setoran"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-jumlah_setoran-live-feedback"
                                        ></b-form-input>

                                        <b-form-invalid-feedback
                                            id="input-jumlah_setoran-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider name="No Referensi">
                                    <b-form-group
                                        id="input-group-no_referensi"
                                        label-for="input-no_referensi"
                                    >
                                        <template> No Referensi: </template>
                                        <b-form-input
                                            id="input-no_referensi"
                                            name="input-no_referensi"
                                            v-model="form.data.no_referensi"
                                        ></b-form-input>
                                    </b-form-group>
                                </ValidationProvider>

                                <ValidationProvider
                                    name="Keterangan"
                                    v-slot="validationContext"
                                    :rules="{ required: true }"
                                >
                                    <b-form-group
                                        id="input-group-keterangan"
                                        label-for="input-keterangan"
                                    >
                                        <template>
                                            Keterangan:
                                            <span class="text-danger">*</span>
                                        </template>

                                        <b-form-textarea
                                            id="input-keterangan"
                                            name="input-keterangan"
                                            v-model="form.data.keterangan"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            aria-describedby="input-keterangan-live-feedback"
                                            placeholder="Enter something..."
                                            rows="3"
                                            max-rows="6"
                                        ></b-form-textarea>

                                        <b-form-invalid-feedback
                                            id="input-keterangan-live-feedback"
                                            >{{
                                                validationContext.errors[0]
                                            }}</b-form-invalid-feedback
                                        >
                                    </b-form-group>
                                </ValidationProvider>

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
    </div>
</template>
<script>
import { mapGetters } from "vuex";
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
import { nextTick } from "vue";

// Install VeeValidate rules and localization
Object.keys(rules).forEach((rule) => {
    extend(rule, rules[rule]);
});

localize("en", en);

export default {
    name: "SetoranSimpokSimwa",
    components: {
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
            form: {
                data: {
                    no_anggota: null,
                    nama_anggota: null,
                    simpok: null,
                    simwa: null,
                    tanggal_transaksi: null,
                    no_referensi: null,
                    jenis_setoran: null,
                    jumlah_setoran: null,
                    keterangan: null,
                },
                loading: false,
                showOverlay: false,
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
                        key: "no_anggota",
                        sortable: true,
                        label: "No Anggota",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "nama_anggota",
                        sortable: true,
                        label: "Nama Anggota",
                        thClass: "text-center",
                        tdClass: "text-left",
                    },
                    {
                        key: "kode_cabang",
                        sortable: true,
                        label: "Kode Cabang",
                        thClass: "text-center",
                        tdClass: "text-left",
                    },
                    {
                        key: "flag_debet_credit",
                        sortable: true,
                        label: "D/C",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "trx_type",
                        sortable: true,
                        label: "Jenis Setoran",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "trx_date",
                        sortable: true,
                        label: "Tanggal Transaksi",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "amount",
                        sortable: true,
                        label: "Jumlah Setoran",
                        thClass: "text-center",
                        tdClass: "text-right",
                    },
                    {
                        key: "description",
                        sortable: true,
                        label: "Keterangan",
                        thClass: "text-center",
                        tdClass: "text-left",
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
            opt: {
                no_anggota: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                ],
                jenis_setoran: [
                    {
                        value: null,
                        text: "Please select an option",
                    },
                    {
                        text: "Simpok",
                        value: "1",
                    },
                    {
                        text: "Simwa",
                        value: "2",
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
        "form.data.no_anggota"(newValue) {
            if (newValue !== null) {
                this.doGetProfileAnggota(newValue);
            }
        },
    },
    mounted() {
        this.doGet();
        this.doGetAnggota();
    },
    methods: {
        ...helper,

        getValidationState({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        async doGet() {
            this.table.showOverlay = true;
            try {
                let payload = this.paging;
                payload.sortDir = payload.sortDesc ? "DESC" : "ASC";
                this.table.loading = true;
                let req = await easycoApi.listSimpananSetoranSimpokSimwa(
                    payload,
                    this.user.token
                );
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
            } finally {
                this.table.showOverlay = false;
            }
        },
        async doGetAnggota() {
            try {
                let payload = null;
                let req = await easycoApi.pengajuanAnggotaRead(
                    payload,
                    this.user.token
                );
                let { data, status, msg } = req.data;
                if (status) {
                    this.opt.no_anggota = [
                        {
                            value: null,
                            text: "Please select an option",
                        },
                    ];
                    data.map((item) => {
                        this.opt.no_anggota.push({
                            value: item.no_anggota,
                            text: `${item.no_anggota} - ${item.nama_anggota}`,
                            data: item,
                        });
                    });
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
        async doGetProfileAnggota() {
            this.form.showOverlay = true;
            try {
                let payload = {
                    no_anggota: this.form.data.no_anggota,
                };
                let req = await easycoApi.laporanProfilAnggota(
                    payload,
                    this.user.token
                );
                let { data, status } = req.data;
                const { nama_anggota, simpok, simwa } = data;
                this.form.data.nama_anggota = nama_anggota;
                this.form.data.simpok = this.numberFormat(simpok, 0);
                this.form.data.simwa = this.numberFormat(simwa, 0);
            } catch (error) {
                console.error(error);
                this.notify("danger", "Error", error);
            } finally {
                this.form.showOverlay = false;
            }
        },
        async onSubmit() {
            this.form.loading = true;
            try {
                let payload = new FormData();
                let payloadData = { ...this.form.data };

                for (let key in payloadData) {
                    payload.append(key, payloadData[key]);
                }

                let req = await easycoApi.createSimpananSetoranSimpokSimwa(
                    payload,
                    this.user.token
                );

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
        doClearForm() {
            this.form.data = {
                no_anggota: null,
                no_referensi: null,
                tanggal_transaksi: null,
                jenis_setoran: null,
                jumlah_setoran: null,
                keterangan: null,
            };
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
