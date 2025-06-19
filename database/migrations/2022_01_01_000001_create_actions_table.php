<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actions', function(Blueprint $table) {
            $table->id();

            // The user who performed the action
            // We can use nullable()->index() to allow for actions that are not performed by a user (cron, system, etc.)
            $table->foreignId('performer_id')->constrained('users')->nullOnDelete();
            $table->string('performer_type');
            // * $table->string('performer_name')->nullable();
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');

            // Here is the action type (in pur case: created, updated and deleted). We can use enum for more strict validation or json field for more flexibility.
            $table->string('action_type');

            //TODO: Improvements (*):
            // JSON field for before/after data (can be null at first)
            // $table->json('versions')->nullable();
            
            // Example of payload: 
            // {
            //     "old": {
            //         "title": "Old Title",
            //         "body": "Some old content"
            //     },
            //     "new": {
            //         "title": "New Title",
            //         "body": "Some updated content"
            //     }
            // }


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
