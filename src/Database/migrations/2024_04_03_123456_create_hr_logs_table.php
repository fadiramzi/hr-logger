<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_id')->unique();
            $table->string('source_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('api_version')->nullable();
            $table->string('endpoint');
            $table->string('controller')->nullable();
            $table->string('method_name')->nullable();
            $table->string('http_method')->nullable();
            $table->datetime('request_time');
            $table->datetime('response_time')->nullable();
            $table->decimal('execution_time', 10, 3);
            $table->longText('request_payload')->nullable();
            $table->longText('response_payload')->nullable();
            $table->string('response_code');
            $table->string('user_identifier')->nullable();
            $table->longText('exception')->nullable();
            // Add your desired columns here
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_logs');
    }
}
