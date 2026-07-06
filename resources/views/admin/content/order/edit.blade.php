<section class="content">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-person-circle me-2"></i>
                    <strong>Customer Info</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="form-group" id="storenamepart">
                                <label class="form-label">Store Name</label>
                                <select id="storeID" class="form-select" disabled>
                                    <option value="1">{{ env('APP_NAME') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" readonly class="form-control" id="invoiceID"
                                    value="{{ $order->invoiceID }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName"
                                    value="{{ $order->customerName }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Customer Phone</label>
                                <input type="text" class="form-control" id="customerPhone"
                                    value="{{ $order->customerPhone }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Customer Address</label>
                                <textarea class="form-control" id="customerAddress" rows="2">{{ $order->customerAddress }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="form-group" id="courierdatatbl">
                                        <label class="form-label">Courier Name</label>
                                        <select id="courierID" class="form-select">
                                            <option value="{{ $order->courier_id }}">{{ $order->courierName }}</option>
                                        </select>
                                        <?php
                                        use App\Models\Courier;
                                        $couriers = Courier::all();
                                        ?>
                                        <script>
                                            var couriers = <?php echo json_encode($couriers); ?>;
                                        </script>
                                    </div>
                                </div>
                                <div class="col-lg-6 hasCity">
                                    <div class="form-group" id="citydatatbl">
                                        <label class="form-label">City Name</label>
                                        <select id="cityID" class="form-select">
                                            <option value="{{ $order->city_id }}">{{ $order->cityName }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 hasZone">
                                    <div class="form-group" id="xonedatatbl">
                                        <label class="form-label">Zone Name</label>
                                        <select id="zoneID" class="form-select">
                                            <option value="{{ $order->zone_id }}">{{ $order->zoneName }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Customer Notes</label>
                                <textarea class="form-control" id="customerNote" rows="2">{{ $order->customerNote }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Order Date</label>
                                <input type="text" class="form-control datepicker" value="{{ $order->orderDate }}"
                                    id="orderDate">
                            </div>
                        </div>
                        @if ($order->deliveryDate)
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Delivery Date</label>
                                    <input type="text" class="form-control datepicker" id="deliveryDate"
                                        value="{{ $order->deliveryDate }}">
                                </div>
                            </div>
                        @endif
                        @if ($order->completeDate)
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-label">Complete Date</label>
                                    <input type="text" class="form-control datepicker" id="completeDate"
                                        value="{{ $order->completeDate }}">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-box-seam me-2"></i>
                    <strong>Product Info</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table id="productTable" class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Code</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $product)
                                    <tr>
                                        <td style="display: none"><input type="text" class="productID" value="{{ $product->product_id }}"></td>
                                        <td><input type="text" name="color" class="form-control form-control-sm" value="{{ $product->color }}" style="max-width: 60px;"></td>
                                        <td><input type="text" name="size" class="form-control form-control-sm" value="{{ $product->size }}" style="max-width: 40px;"></td>
                                        <td><span class="productCode">{{ $product->productCode }}</span></td>
                                        <td><span class="productName">{{ $product->productName }}</span></td>
                                        <td><input type="number" class="productQuantity form-control form-control-sm" value="{{ $product->quantity }}" style="width: 70px;"></td>
                                        <td><span class="productPrice">{{ $product->productPrice }}</span></td>
                                        <td><button class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <select id="productID" class="form-select">
                                            <option value="">Select Product</option>
                                        </select>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group mb-2" id="paymntidname">
                                <label class="form-label">Payment Type</label>
                                <select id="paymentTypeID" class="form-select">
                                    <option value="{{ $order->payment_type_id }}">{{ $order->paymentTypeName }}</option>
                                </select>
                            </div>
                            <div class="form-group mb-2 paymentID" id="paymentIDname">
                                <select id="paymentID" class="form-select mb-2">
                                    <option value="{{ $order->payment_id }}">{{ $order->paymentNumber }}</option>
                                </select>
                                <button class="btn btn-info btn-sm w-100" id="sendmessage">Send SMS</button>
                            </div>
                            <div class="form-group mb-2 paymentAgentNumber">
                                <input type="text" class="form-control" id="paymentAgentNumber"
                                    placeholder="Enter Bkash Agent Number" value="{{ $order->paymentAgentNumber }}">
                            </div>
                            <div class="form-group mb-2 hide">
                                <label class="form-label">Memo Number</label>
                                <input type="text" class="form-control" id="memo"
                                    placeholder="Enter Memo Number"
                                    @if ($order->memo) value="{{ $order->memo }}"
                                @else @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded-3">
                                <div class="mb-2 row align-items-center">
                                    <label class="col-sm-5 col-form-label fw-medium">Sub Total</label>
                                    <div class="col-sm-7">
                                        <span class="form-control" id="subtotal">{{ $order->subTotal }}</span>
                                    </div>
                                </div>
                                <div class="mb-2 row align-items-center">
                                    <label class="col-sm-5 col-form-label fw-medium">Delivery</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" value="{{ $order->deliveryCharge }}" id="deliveryCharge">
                                    </div>
                                </div>
                                <div class="mb-2 row align-items-center">
                                    <label class="col-sm-5 col-form-label fw-medium">Discount</label>
                                    <div class="col-sm-7">
                                        <input type="text" value="{{ $order->discountCharge }}" class="form-control" id="discountCharge">
                                    </div>
                                </div>
                                <div class="mb-2 row align-items-center paymentAmount">
                                    <label class="col-sm-5 col-form-label fw-medium">Payment</label>
                                    <div class="col-sm-7">
                                        <input type="text" value="{{ $order->paymentAmount }}" class="form-control" id="paymentAmount">
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <label class="col-sm-5 col-form-label fw-medium">Total</label>
                                    <div class="col-sm-7">
                                        <span class="form-control fw-bold text-primary" id="total">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <button type="button" id="btn-update" value="{{ $order->id }}"
                                class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> Update Order</button>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="websiteLink" placeholder="Website Link">
                                <button class="btn btn-info" id="sendweblink">Send Link</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-2">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-chat-dots me-2"></i>
                    <strong>Order Status</strong>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" id="comment" class="form-control" placeholder="Add Notes">
                        <button class="btn btn-success" id="updateComment" type="button">Update Note</button>
                    </div>
                    <div class="table-responsive">
                        <table id="orderCommentTable" data-id="{{ $order->id }}" class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Created At</th>
                                    <th>Notes</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-clock-history me-2"></i>
                    <strong>Old Orders</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="oldOrderTable" data-id="{{ $order->id }}" class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Customer Info</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>