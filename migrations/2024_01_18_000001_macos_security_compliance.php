<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class MacosSecurityCompliance extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('macos_security_compliance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->bigInteger('last_compliance_check')->nullable();
            $table->string('baseline')->nullable();
            $table->string('compliant')->nullable();
            $table->integer('fails')->nullable();
            $table->integer('passes')->nullable();
            $table->integer('exempt')->nullable();
            $table->integer('total')->nullable();
            $table->mediumText('compliance_json')->nullable();

            $table->index('serial_number');
            $table->index('baseline');
            $table->index('compliant');
            $table->index('fails');
            $table->index('passes');
            $table->index('exempt');
            $table->index('total');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('macos_security_compliance');
    }
}
