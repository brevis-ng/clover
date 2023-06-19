<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT = "draft";
    case PENDING = "pending";
    case PROCESSING = "processing";
    case SHIPPED = "shipped";
    case PAID = "paid";
    case UNPAID = "unpaid";
    case PARTIALPAYMENT = "partial_payment";
    case CANCELLED = "cancelled";
    case COMPLETED = "completed";
    case FAILED = "failed";
}
