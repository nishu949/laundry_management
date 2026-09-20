@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div>
            <h2>Rate Cards</h2>
            <div class="sub">Services and quantity-based pricing tiers</div>
        </div>
        <a href="{{ route('rate-cards.create') }}" class="btn">+ New Rate Card</a>
    </div>

    @if($rateCards->isEmpty())
        <div class="empty">
            <div class="empty-icon">💵</div>
            <h3>No rate cards yet</h3>
            <p>Create your first service and pricing tiers to start booking orders.</p>
            <a href="{{ route('rate-cards.create') }}" class="btn">+ New Rate Card</a>
        </div>
    @else
        @foreach($rateCards as $rc)
            <div class="card" style="margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap;">
                    <div>
                        <h3 style="margin:0 0 0.35rem; font-size:1.15rem;">{{ $rc->name }}</h3>
                        <small class="muted">
                            Service code: <code>{{ $rc->service_type }}</code>
                            &nbsp;·&nbsp;
                            Base rate: <strong style="color:#a5b4fc;">{{ $rc->base_rate_formatted }}</strong>
                        </small>
                    </div>
                    <div style="display:flex; gap:0.4rem;">
                        <a href="{{ route('rate-cards.edit', $rc) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form method="POST" action="{{ route('rate-cards.destroy', $rc) }}"
                              onsubmit="return confirm('Delete this rate card?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>

                @if(!empty($rc->tiers))
                    <div class="table-wrap" style="margin-top:1.15rem; box-shadow:none;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Quantity Tier</th>
                                    <th style="text-align:right;">Rate per item</th>
                                    <th style="text-align:right;">Example (10 items)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(collect($rc->tiers)->sortBy('min_quantity') as $tier)
                                    <tr>
                                        <td>{{ $tier['min_quantity'] }}+ items</td>
                                        <td style="text-align:right;" class="amount">${{ number_format($tier['rate'], 2) }}</td>
                                        <td style="text-align:right;" class="amount">${{ number_format($tier['rate'] * 10, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="muted" style="margin:1rem 0 0; font-size:0.88rem;">No quantity tiers — flat rate of {{ $rc->base_rate_formatted }} per item.</p>
                @endif
            </div>
        @endforeach
    @endif
@endsection