@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2>Edit Rate Card</h2>
            <div class="sub">{{ $rateCard->name }}</div>
        </div>
        <a href="{{ route('rate-cards.index') }}" class="btn btn-secondary">← Back</a>
    </div>

    <form method="POST" action="{{ route('rate-cards.update', $rateCard) }}">
        @csrf
        @method('PUT')

        <div class="card">
            <h3 style="margin-top:0;">Service Details</h3>

            <div class="field-row">
                <div class="field">
                    <label>Service Name <span style="color:#fca5a5;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $rateCard->name) }}"
                           class="@error('name') is-invalid @enderror" required>
                    @error('name') <div class="field-error">⚠ {{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label>Service Code <span style="color:#fca5a5;">*</span></label>
                    <input type="text" name="service_type" value="{{ old('service_type', $rateCard->service_type) }}"
                           class="@error('service_type') is-invalid @enderror" required>
                    @error('service_type') <div class="field-error">⚠ {{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field">
                <label>Base Rate (per item) <span style="color:#fca5a5;">*</span></label>
                <input type="number" name="base_rate" step="0.01" min="0"
                       value="{{ old('base_rate', $rateCard->base_rate) }}"
                       class="@error('base_rate') is-invalid @enderror" required>
                @error('base_rate') <div class="field-error">⚠ {{ $message }}</div> @enderror
            </div>
        </div>

        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0;">Quantity Tiers</h3>
                    <small class="muted">Edit, add, or remove tiers. Clear all to fall back to the base rate.</small>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addTier()">+ Add Tier</button>
            </div>

            <div id="tiers" style="margin-top:1.1rem;"></div>
        </div>

        <div style="display:flex; gap:0.6rem;">
            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('rate-cards.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <template id="tier-template">
        <div class="item-row" style="grid-template-columns:1fr 1fr auto;">
            <div><input type="number" name="tiers[__IDX__][min_quantity]" placeholder="Min qty" min="1"></div>
            <div><input type="number" name="tiers[__IDX__][rate]" placeholder="Rate" step="0.01" min="0"></div>
            <div><button type="button" class="remove-btn" onclick="this.closest('.item-row').remove()" title="Remove tier">✕</button></div>
        </div>
    </template>

    <script>
        let idx = 0;
        const c = document.getElementById('tiers');
        const t = document.getElementById('tier-template');
        const existing = @json($rateCard->tiers ?? []);

        function addTier(minQty = '', rate = '') {
            const html = t.innerHTML.replaceAll('__IDX__', idx++);
            const w = document.createElement('div');
            w.innerHTML = html.trim();
            const row = w.firstElementChild;
            row.querySelector('input[name*="min_quantity"]').value = minQty;
            row.querySelector('input[name*="rate"]').value = rate;
            c.appendChild(row);
        }

        if (existing.length === 0) {
            addTier();
        } else {
            existing.forEach(tier => addTier(tier.min_quantity, tier.rate));
        }
    </script>
@endsection