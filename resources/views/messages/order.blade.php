<b>Thông tin đơn hàng {{ $order->id }}</b><br>
👤 Người nhận: <code>{{ $customer->name }}</code><br>
📞 SĐT: <code>{{ $customer->phone }}</code><br>
📦 Địa chỉ: <code>{{ $order->address }}</code><br><br>
🛒<b>Sản phẩm</b><br>
@forelse ($items as $item)
<?php
    $name = $item->product['code'].'-'.$item->product['name'];
    $max_length = 17;
    $name = Illuminate\Support\Str::of($name)->limit($max_length)->padRight($max_length, '.');
?>
<code>• {{ $name . ' x' . $item->quantity . ' ' . $item->amount . config('clover.currency') }}</code><br>
@empty
@endforelse
🛵 Phí ship: {{ money($order->shipping_amount, convert: true) }}<br>
💸 Tổng tiền: {{ money($order->total_amount, convert: true) }}<br><br>
📌 <i>Ghi chú: {{ $order->notes }}</i><br>
