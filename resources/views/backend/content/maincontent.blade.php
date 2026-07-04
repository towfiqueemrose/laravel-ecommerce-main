@extends('backend.master')

@section('maincontent')

    @section('title')
        {{ env('APP_NAME') }}-Admin
    @endsection

@php
    $totalOrders = App\Models\Order::count();
    $myOrders = App\Models\Order::where('admin_id', Auth::guard('admin')->user()->id)->count();
    $processingOrders = App\Models\Order::where('status', 'Processing')->count();
    $pendingOrders = App\Models\Order::where('status', 'Pending')->count();
    $recentOrders = App\Models\Order::latest()->take(5)->get();
@endphp

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Total Orders</p>
                        <h2 class="stat-value">{{ $totalOrders }}</h2>
                    </div>
                    <span class="stat-trend up">↑ 12%</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">My Orders</p>
                        <h2 class="stat-value">{{ $myOrders }}</h2>
                    </div>
                    <span class="stat-trend up">↑ 8%</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Processing</p>
                        <h2 class="stat-value">{{ $processingOrders }}</h2>
                    </div>
                    <span class="stat-trend up">↑ 5%</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-danger">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Pending</p>
                        <h2 class="stat-value">{{ $pendingOrders }}</h2>
                    </div>
                    <span class="stat-trend down">↓ 3%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-8">
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Sales Overview</h3>
                    <select class="form-select" style="width:auto;padding:6px 12px;font-size:13px;border:1px solid #e2e8f0;border-radius:6px;color:#475569;">
                        <option>Last 7 days</option>
                        <option>This Month</option>
                        <option>This Year</option>
                    </select>
                </div>
                <div class="chart-placeholder">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-4">
            <div class="content-card">
                <div class="card-header" style="margin-bottom:16px;">
                    <h3 class="card-title">Recent Orders</h3>
                </div>
                <div>
                    @forelse ($recentOrders as $order)
                        <div class="order-row">
                            <div>
                                <span class="order-id">#{{ $order->invoiceID ?? $order->id }}</span>
                                @if ($order->customers)
                                    <span class="order-customer">{{ $order->customers->customerName ?? '' }}</span>
                                @endif
                            </div>
                            <span class="order-amount">৳{{ number_format($order->subTotal) }}</span>
                        </div>
                    @empty
                        <p style="color:#94a3b8;text-align:center;padding:20px 0;">No orders yet</p>
                    @endforelse
                </div>
                <a href="{{ url('/order/dashboard') }}" style="display:block;text-align:center;margin-top:16px;color:#4f46e5;font-size:13px;font-weight:500;text-decoration:none;">View All Orders →</a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-4">
            <a href="{{ route('admin.products.create') }}" class="quick-action">
                <div class="qa-icon" style="color:#4f46e5;">+</div>
                <h4 class="qa-title">New Product</h4>
                <p class="qa-sub">Add a product to inventory</p>
            </a>
        </div>
        <div class="col-sm-12 col-xl-4">
            <a href="{{ url('/order/dashboard') }}" class="quick-action">
                <div class="qa-icon" style="color:#0ea5e9;">📋</div>
                <h4 class="qa-title">View Orders</h4>
                <p class="qa-sub">Manage pending orders</p>
            </a>
        </div>
        <div class="col-sm-12 col-xl-4">
            <a href="{{ route('admin.admins.index') }}" class="quick-action">
                <div class="qa-icon" style="color:#f59e0b;">👥</div>
                <h4 class="qa-title">Manage Admins</h4>
                <p class="qa-sub">Roles & permissions</p>
            </a>
        </div>
    </div>
</div>
@endsection
