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
                            v-b-tooltip.hover
                            title="Tambah Data Baru"
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
                                        />
                                    </b-input-group>
                                </div>
                            </b-col>
                            <b-col cols="6" class="d-flex justify-content-end">
                                <div class="w-100 max-300">
                                    <b-input-group size="sm">
                                        <b-form-input />
                                        <b-input-group-append>
                                            <b-button
                                                size="sm"
                                                text="Button"
                                                variant="primary"
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
                            show-empty
                            :emptyText="
                                table.loading
                                    ? 'Memuat data...'
                                    : 'Tidak ada data'
                            "
                        >
                            <template #cell(no)="data">
                                {{ data.index + 1 }}
                            </template>
                            <template #cell(no_rekening)="data">
                                {{ getKodeKecamatan(data.item.no_rekening) }}
                            </template>
                            <template #cell(action)="data">
                                <b-button
                                    variant="danger"
                                    size="xs"
                                    class="mx-1"
                                    @click="doDelete(data.item, true)"
                                    v-b-tooltip.hover
                                    title="Hapus"
                                >
                                    <b-icon icon="trash" />
                                </b-button>
                                <b-button
                                    variant="success"
                                    size="xs"
                                    class="mx-1"
                                    @click="doUpdate(data.item)"
                                    v-b-tooltip.hover
                                    title="Ubah"
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
            title="Form Setoran Simpok Simwa"
            id="modal-form"
            hide-footer
            size="lg"
            centered
        >
            <b-overlay :show="form.showOverlay" rounded="sm">
                <b-row>
                    <b-col md="6" offset-md="3">
                        <b-form @submit="doSave">
                            <b-form-group
                                id="input-group-no_rekening"
                                label-for="input-group-no_rekening"
                            >
                                <template>
                                    No. Rekening:
                                    <span class="text-danger">*</span>
                                </template>
                                <b-form-select
                                    id="no_rekening"
                                    :options="opt.no_rekening"
                                    v-model="$v.form.data.no_rekening.$model"
                                    :state="validateState('no_rekening')"
                                    @change="doGetNoRekeningDetail()"
                                />
                            </b-form-group>
                            <b-form-group
                                id="input-group-nama"
                                label="Nama Lengkap:"
                                label-for="nama"
                            >
                                <b-form-input
                                    id="nama"
                                    v-model="form.data.nama"
                                    disabled
                                />
                            </b-form-group>
                            <b-form-group
                                id="input-group-produk"
                                label="Produk:"
                                label-for="produk"
                            >
                                <b-form-input
                                    id="produk"
                                    v-model="form.data.produk"
                                    disabled
                                />
                            </b-form-group>
                            <b-form-group
                                id="input-group-saldo_efektif"
                                label="Saldo Efektif:"
                                label-for="saldo_efektif"
                            >
                                <b-form-input
                                    id="saldo_efektif"
                                    v-model="form.data.saldo_efektif"
                                    disabled
                                />
                            </b-form-group>
                            <hr />
                            <b-form-group
                                id="input-group-tgl_transaksi"
                                label-for="tgl_transaksi"
                            >
                                <template>
                                    Tanggal Transaksi:
                                    <span class="text-danger">*</span>
                                </template>
                                <b-form-datepicker
                                    id="tgl_transaksi"
                                    v-model="$v.form.data.tgl_transaksi.$model"
                                    :state="validateState('tgl_transaksi')"
                                />
                            </b-form-group>
                            <b-form-group
                                id="input-group-jumlah_setoran"
                                label-for="jumlah_setoran"
                            >
                                <template>
                                    Jumlah Setoran:
                                    <span class="text-danger">*</span>
                                </template>
                                <b-form-input
                                    type="number"
                                    id="jumlah_setoran"
                                    v-model="$v.form.data.jumlah_setoran.$model"
                                    :state="validateState('jumlah_setoran')"
                                />
                            </b-form-group>
                            <b-form-group
                                id="input-group-no_referensi"
                                label="No. Referensi:"
                                label-for="no_referensi"
                            >
                                <b-form-input
                                    id="no_referensi"
                                    v-model="form.data.no_referensi"
                                ></b-form-input>
                            </b-form-group>
                            <b-form-group
                                id="input-group-keterangan"
                                label-for="keterangan"
                            >
                                <template>
                                    Keterangan:
                                    <span class="text-danger">*</span>
                                </template>
                                <b-form-textarea
                                    id="keterangan"
                                    v-model="$v.form.data.keterangan.$model"
                                    :state="validateState('keterangan')"
                                    placeholder="Enter something..."
                                    rows="3"
                                    max-rows="6"
                                ></b-form-textarea>
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
                                        form.loading ? "Memproses..." : "Simpan"
                                    }}
                                </b-button>
                            </b-col>
                        </b-form>
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
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import easycoApi from "@/core/services/easyco.service";
export default {
    name: "SetoranAnggota",
    components: {},
    data() {
        return {
            form: {
                data: {
                    id: null,
                    no_rekening: null,
                    nama: null,
                    produk: null,
                    saldo_efektif: null,
                    tgl_transaksi: null,
                    jumlah_setoran: null,
                    no_referensi: null,
                    keterangan: null,
                    created_by: null,
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
                        key: "no_rekening",
                        sortable: true,
                        label: "No. Rekening",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "nama",
                        sortable: true,
                        label: "Nama Lengkap",
                        thClass: "text-center",
                        tdClass: "text-left",
                    },
                    {
                        key: "produk",
                        sortable: true,
                        label: "Produk",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "jumlah_setoran",
                        sortable: true,
                        label: "Jumlah Setoran",
                        thClass: "text-center",
                        tdClass: "text-center",
                    },
                    {
                        key: "tgl_transaksi",
                        sortable: true,
                        label: "Tanggal Transaksi",
                        thClass: "text-center",
                        tdClass: "text-center",
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
            },
            remove: {
                data: Object,
                loading: false,
            },
            opt: {
                perPage: [10, 25, 50, 100],
                no_rekening: [],
            },
        };
    },
    mixins: [validationMixin],
    validations: {
        form: {
            data: {
                no_rekening: {
                    required,
                },
                tgl_transaksi: {
                    required,
                },
                jumlah_setoran: {
                    required,
                },
                keterangan: {
                    required,
                },
            },
        },
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
    },
    methods: {
        validateState(name) {
            const { $dirty, $error } = this.$v.form.data[name];
            return $dirty ? !$error : null;
        },
        getKodeKecamatan(kode) {
            let kecamatan = this.opt.no_rekening.find(
                (item) => item.value == kode
            );
            if (kecamatan) {
                return kecamatan.text;
            }
        },
        async doGetNoRekeningDetail() {
            let payload = {
                no_rekening: this.form.data.no_rekening,
            };
            try {
                let req = await easycoApi.doGetNoRekeningDetail(
                    payload,
                    this.user.token
                );
                let { data, status, msg } = req.data;
                if (status) {
                    this.form.data.tgl_transaksi = data.tgl_transaksi;
                }
            } catch (error) {
                console.error(error);
            }
        },
        async doGetNoRekening() {
            let payload = { ...this.paging };
            payload.sortDir = payload.sortDesc ? "DESC" : "ASC";
            payload.perPage = "~";
            try {
                let req = await easycoApi.doGetNoRekening(
                    payload,
                    this.user.token
                );
                let { data, status, msg, total } = req.data;
                if (status) {
                    this.opt.no_rekening = [
                        {
                            value: 0,
                            text: "kecamatan",
                        },
                    ];
                    data.map((item) => {
                        this.opt.no_rekening.push({
                            value: item.no_rekening,
                            text: item.nama_kecamatan,
                        });
                    });
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
                let req = await easycoApi.doGetSimpananSetoranAnggota(
                    payload,
                    this.user.token
                );
                let { data, status, msg, total } = req.data;
                if (status) {
                    this.table.items = data;
                    this.table.totalRows = total;
                }
                this.table.loading = false;
            } catch (error) {
                this.table.loading = false;
                console.error(error);
            }
        },
        async doSave(e) {
            this.$v.form.$touch();
            if (!this.$v.form.$anyError) {
                this.form.loading = true;
                try {
                    let payload = this.form.data;
                    payload.created_by = this.user.id;
                    let req = false;
                    if (payload.id) {
                        req = await easycoApi.doUpdateSimpananSetoranAnggota(
                            payload,
                            this.user.token
                        );
                    } else {
                        req = await easycoApi.doCreateSimpananSetoranAnggota(
                            payload,
                            this.user.token
                        );
                    }
                    let { status } = req.data;
                    if (status) {
                        this.notify(
                            "success",
                            "Success",
                            "Data berhasil disimpan"
                        );
                        this.doGet();
                        this.doGetNoRekening();
                        this.$bvModal.hide("modal-form");
                    } else {
                        this.notify("danger", "Error", "Data gagal disimpan");
                    }
                    this.form.loading = false;
                } catch (error) {
                    this.notify("danger", "Error", error);
                    this.form.loading = false;
                }
            } else {
                e.preventDefault();
            }
        },
        async doUpdate(item) {
            try {
                let req = await easycoApi.doGetSimpananSetoranAnggotaDetail(
                    `id=${item.id}`,
                    this.user.token
                );
                let { data, status, msg } = req.data;
                if (status) {
                    this.form.data = data;
                    this.$bvModal.show("modal-form");
                }
            } catch (error) {
                console.log(error);
                this.notify("danger", "Error", "Gagal mengambil data");
            }
        },
        async doDelete(item, prompt) {
            if (prompt) {
                this.remove.data = item;
                this.$bvModal.show("modal-delete");
            } else {
                this.remove.loading = true;
                try {
                    let req = await easycoApi.doDeleteSimpananSetoranAnggota(
                        `id=${this.remove.data.id}`,
                        this.user.token
                    );
                    let { status } = req.data;
                    if (status) {
                        this.remove.loading = false;
                        this.$bvModal.hide("modal-delete");
                        this.notify(
                            "success",
                            "Success",
                            "Data berhasil dihapus"
                        );
                        this.doGet();
                        this.doGetNoRekening();
                    } else {
                        this.notify("danger", "Error", "Data gagal dihapus");
                    }
                } catch (error) {
                    this.notify("danger", "Error", error);
                }
            }
        },
        doClearForm() {
            this.form.data = {
                id: null,
                no_rekening: 0,
                tgl_transaksi: null,
                jenis_setoran: null,
                created_by: null,
            };
            this.$v.form.$reset();
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
    mounted() {
        this.doGet();
        this.doGetNoRekening();
    },
};
</script>
