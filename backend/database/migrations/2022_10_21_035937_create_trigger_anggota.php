<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTriggerAnggota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER ins_kop_anggota AFTER INSERT ON kop_anggota FOR EACH ROW EXECUTE PROCEDURE insert_anggota();
            CREATE TRIGGER ins_kop_pengajuan AFTER INSERT ON kop_pengajuan FOR EACH ROW EXECUTE PROCEDURE insert_reg_pembiayaan();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('
            DROP TRIGGER ins_kop_anggota ON kop_anggota;
            DROP TRIGGER ins_kop_pengajuan ON kop_pengajuan;
        ');
    }
}
