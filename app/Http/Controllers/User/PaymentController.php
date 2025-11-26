<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        // Kiểm tra khóa học có tồn tại không
        $course = Course::findOrFail($request->course_id);

        // Tạo mã đơn hàng ngẫu nhiên (Ví dụ: ORDER_123456)
        $orderCode = 'ORDER_' . rand(100000, 999999);

        // LƯU ĐƠN HÀNG VÀO CSDL (Trạng thái: pending)
        Order::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'order_code' => $orderCode,
            'amount' => $course->price, // Giả sử model Course có cột price
            'status' => 'pending',
        ]);

        // CẤU HÌNH VNPAY
        $vnp_TmnCode = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = route('payment.return');

        $vnp_TxnRef = $orderCode;
        $vnp_OrderInfo = "Thanh toan khoa hoc: " . $course->title;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $course->price * 100; // Nhân 100 theo yêu cầu VNPAY
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef, // Mã đơn hàng của chúng ta
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        // ... (Đoạn code kiểm tra chữ ký SecureHash giữ nguyên như cũ) ...
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {

            // 1. Lấy mã đơn hàng từ VNPAY trả về
            $orderCode = $request->vnp_TxnRef;

            // 2. Tìm đơn hàng trong CSDL
            $order = Order::where('order_code', $orderCode)->first();

            if (!$order) {
                return redirect()->route('user.home')->with('error', 'Không tìm thấy đơn hàng!');
            }

            // 3. Kiểm tra kết quả giao dịch
            if ($request->vnp_ResponseCode == '00') {
                // THANH TOÁN THÀNH CÔNG

                // A. Cập nhật trạng thái đơn hàng
                $order->update(['status' => 'paid']);

                // B. Kích hoạt khóa học (Tạo Enrollment)
                // Kiểm tra xem đã đăng ký chưa để tránh trùng lặp
                $exists = Enrollment::where('user_id', $order->user_id)
                    ->where('course_id', $order->course_id)
                    ->exists();

                if (!$exists) {
                    Enrollment::create([
                        'user_id' => $order->user_id,
                        'course_id' => $order->course_id,
                        'progress' => 0
                    ]);
                }

                return redirect()->route('user.my_courses')->with('success', 'Thanh toán thành công! Bạn đã có thể vào học.');

            } else {
                // THANH TOÁN THẤT BẠI / HỦY BỎ
                $order->update(['status' => 'failed']);
                return redirect()->route('user.home')->with('error', 'Giao dịch thất bại hoặc bị hủy.');
            }
        } else {
            return redirect()->route('user.home')->with('error', 'Chữ ký không hợp lệ!');
        }
    }
}