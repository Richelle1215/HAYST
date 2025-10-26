<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - LUMIÈRE</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-light">

@include('partials.header')

    <div class="container py-4">

        <!-- Delivery Address -->
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-danger mb-2">📍 Delivery Address</h6>
            </div>
            <div class="card-body">
                <strong>{{ Auth::user()->name }}</strong>
                <span class="text-muted"> ({{ old('phone') ?? '09XXXXXXXXX' }})</span>
                <p class="mb-0">{{ old('address') ?? 'Complete Address' }}</p>

                <button class="btn btn-link p-0 mt-2" onclick="document.getElementById('shippingForm').style.display='block'; this.style.display='none'">
                    Change
                </button>

                <!-- Hidden Form for Editing Address -->
                <div id="shippingForm" class="mt-3" style="display:none;">
                    <div class="mb-2">
                        <input type="text" name="fullname" value="{{ Auth::user()->name }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="phone" class="form-control" placeholder="09XXXXXXXXX" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="address" class="form-control" rows="3" required placeholder="Street, Barangay, City"></textarea>
                    </div>
                </div>
            </div>
        </div>


        <!-- Product List -->
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold">🛍 Products Ordered</h6>
            </div>

            <ul class="list-group list-group-flush">
                @php $total = 0; @endphp
                @foreach($cartItems as $item)
                    @php 
                        $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                    @endphp

                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            <small class="text-muted">x{{ $item->quantity }}</small>
                        </div>
                        <span class="fw-bold">₱{{ number_format($subtotal,2) }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total ({{ count($cartItems) }} Item):</span>
                    <span class="text-danger">₱{{ number_format($total,2) }}</span>
                </div>
            </div>
        </div>


        <!-- Payment Method -->
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold">💳 Payment Method</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Cash on Delivery</span>
                    <button class="btn btn-link p-0">Change</button>
                </div>
            </div>
        </div>


        <!-- Final Payment Summary -->
        <form id="checkoutForm" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <div class="card p-3 shadow-sm">
                <div class="d-flex justify-content-between mb-2">
                    <span>Merchandise Subtotal</span>
                    <span>₱{{ number_format($total,2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Shipping Fee</span>
                    <span>₱0.00</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fw-bold fs-5 text-danger mb-3">
                    <span>Total Payment:</span>
                    <span>₱{{ number_format($total,2) }}</span>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                    Place Order
                </button>
            </div>
        </form>

    </div>


<script>
    document.getElementById("checkoutForm").addEventListener("submit", function(e) {
        let phone = document.querySelector('input[name="phone"]');
        if (phone && !phone.value.match(/^09\d{9}$/)) {
            alert("Phone must start with 09 and have 11 digits.");
            e.preventDefault();
        }
    });
</script>

</body>
</html>