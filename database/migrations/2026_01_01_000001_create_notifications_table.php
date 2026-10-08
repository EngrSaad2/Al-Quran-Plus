<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_bn');
            $table->text('short_description_en');
            $table->text('short_description_bn');
            $table->longText('content_en');
            $table->longText('content_bn');
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('notification_type')->default('general'); // general, hadith, event, announcement, ramadan
            $table->boolean('is_active')->default(true);
            $table->timestamp('publish_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
