<?php

namespace Owlwebdev\Ecom\Observers;

use Owlwebdev\Ecom\Models\Order;
use Owlwebdev\Ecom\Models\OrderHistory;

class OrderObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Order "updated" event.
     *
     * @param  Owlwebdev\Ecom\Models\Order  $user
     * @return void
     */
    public function created(Order $order)
    {
        // update products quantity
        if (in_array($order->order_status_id, Order::DECREMENT_STATUSES)) {
            $order->updateProducts('decrement');
        } elseif (in_array($order->order_status_id, Order::INCREMENT_STATUSES)) {
            $order->updateProducts('increment');
        }

        // update coupon
        if ($order->coupon_id && $order->coupon) {
            $coupon = $order->coupon->reduceQuantity();

            if (!$coupon) {
                $order->coupon_id = null;
                $order->saveQuietly();
            }
        }

        if ($order->products->isNotEmpty()) {
            $order->recalculateTotals();
        }

        //history
        if (in_array($order->order_status_id, Order::PUBLIC_STATUSES)) {
            $order->history()->create([
                'type' => OrderHistory::STATUS,
                'admin_id' => auth()->user()->id ?? null,
                'text' => $order->showStatus(),
            ]);
        }
    }

    public function updated(Order $order)
    {
        if ($order->wasChanged()) {
            // update products quantity
            if ($order->wasChanged('order_status_id')) {
                if (in_array($order->order_status_id, Order::DECREMENT_STATUSES)) {
                    $order->updateProducts('decrement');
                } elseif (in_array($order->order_status_id, Order::INCREMENT_STATUSES)) {
                    $order->updateProducts('increment');
                }

                //history
                $order->history()->create([
                    'type' => OrderHistory::STATUS,
                    'admin_id' => auth()->user()->id ?? null,
                    'text' => $order->showStatus(),
                ]);
            }

            // update coupon
            if ($order->wasChanged('coupon_id') && $order->coupon) {
                $coupon = $order->coupon->reduceQuantity();

                if (!$coupon) {
                    $order->coupon_id = null;
                    $order->saveQuietly();
                }
            }

            //payment & shipping
            if ($order->wasChanged('payment_method')) {
                //history
                $order->history()->create([
                    'type' => OrderHistory::PAYMENT,
                    'admin_id' => auth()->user()->id ?? null,
                    'admin_id' => auth()->user()->id ?? null,
                    'text' => $order->payment_method,
                ]);
            }

            if ($order->wasChanged('shipping_method')) {
                //history
                $order->history()->create([
                    'type' => OrderHistory::SHIPPING,
                    'admin_id' => auth()->user()->id ?? null,
                    'text' => $order->shipping_method,
                ]);
            }
        }

        $order->recalculateTotals();
    }
}
