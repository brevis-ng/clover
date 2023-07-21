<b>Thông tin đơn hàng <code>{{ $order->order_number }}</code> [{{ $order->status }}]</b><br>
👤 Người nhận: <code>{{ $order->customer->name }}</code><br>
📞 SĐT: <code>{{ $order->customer->phone }}</code><br>
📦 Địa chỉ: <code>{{ $order->address }}</code><br><br>
🛒<b>Sản phẩm</b><br>
@forelse ($order->products as $item)
<?php
    $name = $item->code.'-'.$item->name;
    $max_length = 17;
    $name = Illuminate\Support\Str::of($name)->limit($max_length)->padRight($max_length, '.');
?>
<code>• {{ $name . ' x' . $item->pivot->quantity . ' ' . money($item->pivot->amount, convert: true) }}</code><br>
@empty
@endforelse
🛵 Phí ship: {{ money($order->shipping_amount, convert: true) }}<br>
💸 Tổng tiền: {{ money($order->total_amount, convert: true) }}<br><br>
📌 <i>Ghi chú: {{ $order->notes }}</i><br>
