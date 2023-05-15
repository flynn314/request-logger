<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogRequestTable extends Migration
{
    private string $tableName = 'logs_requests';

    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('method', 8)->nullable();
            $table->string('url', 512)->index();
            $table->json('query');
            $table->json('request_headers')->nullable();
            $table->longText('request_content')->nullable();
            $table->json('response_headers')->nullable();
            $table->longText('response_content')->nullable();
            $table->ipAddress('ip')->index();
            $table->unsignedInteger('user_token_id')->nullable()->index();
            $table->timestamps();
            $table->unsignedInteger('updated_at_diff_s')->default(0)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
}
