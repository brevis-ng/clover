<b>Thông tin đơn hàng</b><br>
👤 Người nhận: <b>{{ $customer->name }}</b><br>
📞 SĐT: <b>{{ $customer->phone }}</b><br>
📦 Địa chỉ: <b>{{ $customer->address }}</b><br><br>
🛒<b>Đơn hàng</b><br>
@forelse ($items as $item)
<?php
    $name = $item->product['code'].'-'.$item->product['name'];
    $max_length = 30;
    $name = Illuminate\Support\Str::of($name)->padRight($max_length, '.')->limit($max_length);
?>
<code>•{{ $name . '  x' . $item->quantity . '  ' . $item->amount . config('clover.currency') }}</code><br>
@empty
@endforelse
🛵 Phí ship: {{ $order->shipping_amount . config('clover.currency') }}<br>
💸 Tổng tiền: {{ $order->total_amount . config('clover.currency') }}<br><br>
📌 <i>Ghi chú: {{ $order->notes }}</i><br>
