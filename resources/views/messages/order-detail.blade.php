<b>Thông tin đơn hàng <code>{{ $order->order_number }}</code></b><br>
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
<code>• {{ $name . ' x' . $item->pivot->quantity . ' ' . format_currency($item->pivot->amount) }}</code><br>
@empty
@endforelse
🛵 Phí ship: {{ format_currency($order->shipping_amount) }}<br>
💸 Tổng tiền: {{ format_currency($order->total_amount) }}<br><br>
📌 <i>Ghi chú: {{ $order->notes }}</i><br>
🕒 Thời gian: {{ $order->created_at->diffForHumans() }}<br>
♻️ Trạng thái: {{ __("order.s." . $order->status->value) }}
