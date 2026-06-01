<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('stock_no')->unique();
            $table->string('item_code')->unique();
            $table->string('description');
            $table->string('unit');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity_on_hand', 12, 2)->default(0);
            $table->decimal('reorder_level', 12, 2)->default(0);
            $table->enum('status', ['active', 'low_stock', 'out_of_stock', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('ris_headers', function (Blueprint $table) {
            $table->id();
            $table->string('ris_no')->unique();
            $table->string('entity_name');
            $table->string('fund_cluster')->nullable();
            $table->foreignId('division_id')->constrained()->restrictOnDelete();
            $table->string('office');
            $table->string('responsibility_center_code')->nullable();
            $table->text('purpose');
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'pending', 'approved', 'issued', 'completed', 'cancelled'])->default('draft');
            $table->string('qr_token')->unique()->nullable();
            $table->unsignedSmallInteger('current_approval_level')->default(0);
            $table->timestamps();
        });

        Schema::create('approval_matrix_steps', function (Blueprint $table) {
            $table->id();
            $table->string('module')->default('ris');
            $table->unsignedSmallInteger('level');
            $table->string('role_name');
            $table->string('action_label');
            $table->boolean('is_final')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['module', 'level']);
        });

        Schema::create('ris_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ris_id')->constrained('ris_headers')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->restrictOnDelete();
            $table->string('stock_no');
            $table->string('unit');
            $table->string('description');
            $table->decimal('qty_requested', 12, 2);
            $table->decimal('qty_issued', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ris_id')->constrained('ris_headers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('action', ['submitted', 'approved', 'rejected', 'issued', 'completed', 'cancelled']);
            $table->unsignedSmallInteger('approval_level')->nullable();
            $table->string('role_name')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('signature_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('default');
            $table->enum('signature_type', ['requester', 'approver', 'issuer', 'receiver'])->default('requester');
            $table->string('path');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event');
            $table->morphs('auditable');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('signature_images');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('ris_details');
        Schema::dropIfExists('approval_matrix_steps');
        Schema::dropIfExists('ris_headers');
        Schema::dropIfExists('items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('divisions');
    }
};
