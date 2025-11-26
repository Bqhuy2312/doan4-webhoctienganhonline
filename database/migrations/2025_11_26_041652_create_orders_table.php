<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // Người mua
            $table->unsignedBigInteger('course_id'); // Khóa học muốn mua

            // Mã đơn hàng (Dùng để đối chiếu với VNPAY - vnp_TxnRef)
            $table->string('order_code')->unique();

            $table->decimal('amount', 15, 0);

            // Trạng thái: pending (chờ), paid (thành công), failed (thất bại)
            $table->string('status')->default('pending');

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
