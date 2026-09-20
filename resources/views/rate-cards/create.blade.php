@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2>New Rate Card</h2>
            <div class="sub">Define a service and its tiered pricing</div>
        </div>
        <a href="{{ route('rate-cards.index') }}" class="btn btn-secondary">← Back</a>
    </div>

    <form method="POST" action="{{ route('rate-cards.store') }}">
        @csrf

        <div class="card">
            <h3 style="margin-top:0;">Service Details</h3>

            <div class="field-row">
                <div class="field">
                    <label>Service Name <span style="color:#fca5a5;">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="e.g. Express Wash"
                           class="@error('name') is-invalid @enderror" required>
                    @error('name') <div class="field-error">⚠ {{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label>Service Code <span style="color:#fca5a5;">*</span></label>
                    <input type="text" name="service_type" value="{{ old('service_type') }}"
                           placeholder="e.g. express_wash"
                           class="@error('service_type') is-invalid @enderror" required>
                    <small class="muted">Lowercase, underscores only. Unique.</small>
                    @error('service_type') <div class="field-error">⚠ {{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field">
                <label>Base Rate (per item) <span style="color:#fca5a5;">*</span></label>
                <input type="number" name="base_rate" step="0.01" min="0"
                       value="{{ old('base_rate') }}" placeholder="2.50"
                       class="@error('base_rate') is-invalid @enderror" required>
                @error('base_rate') <div class="field-error">⚠ {{ $message }}</div> @enderror
            </div>
        </div>

        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
                <div>
                    <h3 style="margin:0;">Quantity Tiers <span class="muted" style="font-weight:400; font-size:0.85rem;">(optional)</span></h3>
                    <small class="muted">When item count reaches a tier's minimum, that rate applies instead of the base.</small>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addTier()">+ Add Tier</button>
            </div>

            <div id="tiers" style="margin-top:1.1rem;"></div>
            <p class="muted" style="font-size:0.85rem; margin:0.5rem 0 0;">Example: min quantity <code>10</code>, rate <code>2.00</code> → orders of 10+ items pay $2.00/item.</p>
        </div>

        <div style="display:flex; gap:0.6rem;">
            <button type="submit" class="btn">Save Rate Card</button>
            <a href="{{ route('rate-cards.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    <template id="tier-template">
        <div class="item-row" style="grid-template-columns:1fr 1fr auto;">
            <div>
                <input type="number" name="tiers[__IDX__][min_quantity]" placeholder="Min qty (e.g. 10)" min="1">
            </div>
            <div>
                <input type="number" name="tiers[__IDX__][rate]" placeholder="Rate (e.g. 2.00)" step="0.01" min="0">
            </div>
            <div>
                <button type="button" class="remove-btn" onclick="this.closest('.item-row').remove()" title="Remove tier">✕</button>
            </div>
        </div>
    </template>

    <script>
        let idx = 0;
        const c = document.getElementById('tiers');
        const t = document.getElementById('tier-template');
        function addTier() {
            const html = t.innerHTML.replaceAll('__IDX__', idx++);
            const w = document.createElement('div');
            w.innerHTML = html.trim();
            c.appendChild(w.firstElementChild);
        }
        addTier();
    </script>
@endsection