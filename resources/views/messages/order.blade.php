<b>Thông tin đơn hàng {{ $order->id }}</b><br>
👤 Người nhận: <code>{{ $customer->name }}</code><br>
📞 SĐT: <code>{{ $customer->phone }}</code><br>
📦 Địa chỉ: <code>{{ $customer->address }}</code><br><br>
🛒<b>Sản phẩm</b><br>
@forelse ($items as $item)
<?php
    $name = $item->product['code'].'-'.$item->product['name'];
    $max_length = 20;
    $name = Illuminate\Support\Str::of($name)->padRight($max_length, '.')->limit($max_length);
?>
<code>• {{ $name . '  x' . $item->quantity . '  ' . $item->amount . config('clover.currency') }}</code><br>
@empty
@endforelse
🛵 Phí ship: {{ $order->shipping_amount . config('clover.currency') }}<br>
💸 Tổng tiền: {{ $order->total_amount . config('clover.currency') }}<br><br>
📌 <i>Ghi chú: {{ $order->notes }}</i><br>
