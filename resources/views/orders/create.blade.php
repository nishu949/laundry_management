@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2>Book New Order</h2>
            <div class="sub">Fill in customer details and add items</div>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
    </div>

    <form method="POST" action="{{ route('orders.store') }}" novalidate>
        @csrf

        {{-- Customer --}}
        <div class="card">
            <h3 style="margin-top:0;">Customer Information</h3>

            <div class="field-row">
                <div class="field">
                    <label for="customer_name">Full Name <span style="color:#fca5a5;">*</span></label>
                    <input id="customer_name" name="customer_name" type="text"
                           value="{{ old('customer_name') }}"
                           placeholder="e.g. John Doe"
                           class="@error('customer_name') is-invalid @enderror"
                           required>
                    @error('customer_name')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="customer_phone">Phone <span style="color:#fca5a5;">*</span></label>
                    <input id="customer_phone" name="customer_phone" type="text"
                           value="{{ old('customer_phone') }}"
                           placeholder="e.g. 555-1234"
                           class="@error('customer_phone') is-invalid @enderror"
                           required>
                    @error('customer_phone')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="field">
                <label for="customer_address">Address <span class="muted" style="font-weight:400;">(optional)</span></label>
                <input id="customer_address" name="customer_address" type="text"
                       value="{{ old('customer_address') }}"
                       placeholder="Street, city, postal code"
                       class="@error('customer_address') is-invalid @enderror">
                @error('customer_address')
                    <div class="field-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="service_type">Service Type <span style="color:#fca5a5;">*</span></label>
                    <select id="service_type" name="service_type"
                            class="@error('service_type') is-invalid @enderror" required>
                        <option value="wash_fold" @selected(old('service_type') === 'wash_fold')>Wash &amp; Fold</option>
                        <option value="dry_clean" @selected(old('service_type') === 'dry_clean')>Dry Clean</option>
                        <option value="iron_only" @selected(old('service_type') === 'iron_only')>Iron Only</option>
                    </select>
                    @error('service_type')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="pickup_scheduled_at">Pickup Date &amp; Time</label>
                    <input id="pickup_scheduled_at" name="pickup_scheduled_at" type="datetime-local"
                           value="{{ old('pickup_scheduled_at') }}"
                           class="@error('pickup_scheduled_at') is-invalid @enderror">
                    @error('pickup_scheduled_at')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                <div>
                    <h3 style="margin:0;">Items</h3>
                    <small class="muted">Rate is applied per item based on quantity tiers.</small>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addItem()">+ Add Item</button>
            </div>

            <div id="items" style="margin-top:1rem;"></div>

            @error('items')
                <div class="field-error">⚠ {{ $message }}</div>
            @enderror
        </div>

        {{-- Actions --}}
        <div style="display:flex; gap:0.6rem; margin-top:1.25rem;">
            <button type="submit" class="btn">Book Order</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <template id="item-template">
        <div class="item-row">
            <div>
                <select name="items[__IDX__][rate_card_id]" required>
                    @foreach($rateCards as $rc)
                        <option value="{{ $rc->id }}">{{ $rc->name }} — base {{ $rc->base_rate_formatted }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="number" name="items[__IDX__][count]" value="1" min="1" required>
            </div>
            <div>
                <button type="button" class="remove-btn" onclick="this.closest('.item-row').remove()" title="Remove item">✕</button>
            </div>
        </div>
    </template>

    <script>
        let idx = 0;
        const container = document.getElementById('items');
        const template  = document.getElementById('item-template');

        function addItem() {
            const html = template.innerHTML.replaceAll('__IDX__', idx);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            container.appendChild(wrapper.firstElementChild);
            idx++;
        }

        addItem();
    </script>
@endsection