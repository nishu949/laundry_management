<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:         #0d0d0f;
            --surface:    #16171a;
            --surface-2:  #1c1d21;
            --border:     #26282d;
            --border-2:   #34373d;
            --text:       #e7e9ee;
            --text-dim:   #a1a4ad;
            --muted:      #6b7079;
            --accent:     #e7e9ee;
            --accent-h:   #ffffff;
            --focus:      rgba(231,233,238,0.10);
            --radius:     10px;
            --radius-sm:  7px;

            /* NEW: full-width padding */
            --side-pad:   2rem;
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.55;
            font-size: 15px;
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar */
        .navbar {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* CHANGED: full width instead of max-width 1040px */
        .container {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0 var(--side-pad);
        }
        @media (max-width: 640px) {
            .container { padding: 0 1rem; }
        }

        .nav-inner {
            display: flex; justify-content: space-between;
            align-items: center; gap: 1rem; flex-wrap: wrap;
        }
        .brand {
            display: inline-flex; align-items: center; gap: 0.55rem;
            font-size: 0.98rem; font-weight: 600;
            color: var(--text); text-decoration: none;
            letter-spacing: -0.005em;
        }
        .brand-mark {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--surface);
            border: 1px solid var(--border);
            font-size: 0.95rem;
        }
        .nav-links { display: flex; gap: 0.35rem; align-items: center; }

        main { padding: 2rem 0 4rem; }

        h1, h2, h3 { margin: 0 0 0.5rem; color: var(--text); letter-spacing: -0.015em; }
        h1 { font-size: 1.5rem;  font-weight: 600; }
        h2 { font-size: 1.35rem; font-weight: 600; }
        h3 { font-size: 0.95rem; font-weight: 600; }

        .page-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;
        }
        .page-header h2 { margin: 0; }
        .page-header .sub {
            color: var(--muted); font-size: 0.875rem; margin-top: 0.15rem;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
        }
        .card + .card { margin-top: 1rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 0.85rem;
            background: var(--accent);
            color: #0d0d0f;
            border: 1px solid var(--accent);
            border-radius: var(--radius-sm);
            font-size: 0.86rem;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.12s, border-color 0.12s;
        }
        .btn:hover { background: var(--accent-h); border-color: var(--accent-h); }
        .btn-secondary {
            background: var(--surface);
            color: var(--text-dim);
            border-color: var(--border-2);
        }
        .btn-secondary:hover {
            background: var(--surface-2); color: var(--text);
            border-color: var(--border-2);
        }
        .btn-danger {
            background: var(--surface);
            color: #f87171;
            border-color: #3f1d1d;
        }
        .btn-danger:hover {
            background: #2a1717; color: #fca5a5; border-color: #7f1d1d;
        }
        .btn-sm { padding: 0.32rem 0.65rem; font-size: 0.8rem; }

        label {
            display: block; font-weight: 500; font-size: 0.84rem;
            margin-bottom: 0.35rem; color: var(--text-dim);
        }
        input[type="text"], input[type="number"], input[type="datetime-local"],
        input:not([type]), select, textarea {
            width: 100%;
            padding: 0.55rem 0.7rem;
            border: 1px solid var(--border-2);
            border-radius: var(--radius-sm);
            font-size: 0.92rem;
            font-family: inherit;
            color: var(--text);
            background: var(--bg);
            transition: border-color 0.12s, box-shadow 0.12s;
        }
        input::placeholder { color: #55585f; }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--text-dim);
            box-shadow: 0 0 0 3px var(--focus);
        }
        input.is-invalid, select.is-invalid {
            border-color: #b91c1c;
            background: #1d1212;
        }
        input.is-invalid:focus, select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15);
        }
        select option { background: var(--surface-2); color: var(--text); }
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(0.65); cursor: pointer;
        }

        .field { margin-bottom: 1rem; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 640px) { .field-row { grid-template-columns: 1fr; } }
        .field-error { color: #f87171; font-size: 0.8rem; margin-top: 0.3rem; }

        .table-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            padding: 0.75rem 1rem; text-align: left;
            border-bottom: 1px solid var(--border);
        }
        th {
            background: var(--surface-2);
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: var(--surface-2); }
        td .amount { font-variant-numeric: tabular-nums; font-weight: 600; }

        .badge {
            display: inline-block;
            padding: 0.18rem 0.55rem;
            border-radius: 5px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.01em;
            line-height: 1.6;
            border: 1px solid transparent;
        }
        .badge-pickup    { background: rgba(245,158,11,0.10); color: #fcd34d; border-color: rgba(245,158,11,0.25); }
        .badge-washing   { background: rgba(59,130,246,0.10); color: #93c5fd; border-color: rgba(59,130,246,0.25); }
        .badge-ready     { background: rgba(34,197,94,0.10);  color: #86efac; border-color: rgba(34,197,94,0.25); }
        .badge-delivered { background: rgba(148,163,184,0.10);color: #cbd5e1; border-color: rgba(148,163,184,0.25); }
        .badge-paid      { background: rgba(34,197,94,0.10);  color: #86efac; border-color: rgba(34,197,94,0.25); }
        .badge-pending   { background: rgba(245,158,11,0.10); color: #fcd34d; border-color: rgba(245,158,11,0.25); }

        .flash {
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.25);
            color: #86efac;
            font-size: 0.9rem;
        }
        .errors {
            padding: 0.85rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            background: rgba(220,38,38,0.08);
            border: 1px solid rgba(220,38,38,0.25);
            color: #fca5a5;
            font-size: 0.9rem;
        }
        .errors strong { color: #fecaca; }
        .errors ul { margin: 0.35rem 0 0; padding-left: 1.15rem; }

        .empty {
            text-align: center;
            padding: 3rem 1.5rem;
            background: var(--surface);
            border: 1px dashed var(--border-2);
            border-radius: var(--radius);
            color: var(--muted);
        }
        .empty-icon { font-size: 2rem; margin-bottom: 0.75rem; opacity: 0.55; }
        .empty h3 { color: var(--text); margin-bottom: 0.25rem; }
        .empty p { margin: 0.25rem 0 1.25rem; font-size: 0.9rem; }

        .timeline { list-style: none; padding: 0; margin: 0; }
        .timeline li {
            padding: 0.55rem 0 0.55rem 1.4rem;
            border-left: 1px solid var(--border-2);
            position: relative;
            color: var(--text-dim);
            font-size: 0.9rem;
        }
        .timeline li::before {
            content: '';
            position: absolute;
            left: -5px; top: 0.9rem;
            width: 9px; height: 9px;
            border-radius: 50%;
            background: var(--text);
            border: 2px solid var(--surface);
        }
        .timeline li time { color: var(--muted); font-size: 0.82rem; margin-left: 0.4rem; }

        .tabs {
            display: flex; gap: 0.2rem; flex-wrap: wrap;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .tabs a {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.55rem 0.9rem;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color 0.12s, border-color 0.12s;
        }
        .tabs a:hover { color: var(--text); }
        .tabs a.active { color: var(--text); border-bottom-color: var(--text); }
        .tabs a .count { color: var(--muted); font-size: 0.78rem; font-weight: 400; }

        .kv { display: grid; grid-template-columns: 150px 1fr; gap: 0.55rem 1rem; }
        .kv dt { color: var(--muted); font-size: 0.86rem; }
        .kv dd { margin: 0; color: var(--text); }

        .item-row {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 0.5rem;
            align-items: center;
            padding: 0.6rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--bg);
            margin-bottom: 0.5rem;
        }
        .item-row select, .item-row input { margin: 0; }
        .remove-btn {
            background: var(--surface);
            color: var(--muted);
            border: 1px solid var(--border-2);
            width: 34px; height: 34px;
            padding: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.12s;
        }
        .remove-btn:hover {
            background: rgba(220,38,38,0.10);
            color: #f87171;
            border-color: rgba(220,38,38,0.35);
        }
        @media (max-width: 640px) {
            .item-row { grid-template-columns: 1fr 1fr auto; }
        }

        small.muted { color: var(--muted); font-size: 0.84rem; }
        code {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.8rem;
            color: var(--text-dim);
            background: var(--surface-2);
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            border: 1px solid var(--border);
        }
        .amount-lg {
            font-size: 1.5rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            color: var(--text);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-inner">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark">🧺</span>
                Laundry
            </a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}"
                   class="btn btn-sm {{ request()->routeIs('dashboard') ? '' : 'btn-secondary' }}">Dashboard</a>
                <a href="{{ route('orders.index') }}"
                   class="btn btn-sm {{ request()->routeIs('orders.*') ? '' : 'btn-secondary' }}">Orders</a>
                <a href="{{ route('rate-cards.index') }}"
                   class="btn btn-sm {{ request()->routeIs('rate-cards.*') ? '' : 'btn-secondary' }}">Rate Cards</a>
                <a href="{{ route('orders.create') }}" class="btn btn-sm">+ New Order</a>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="errors">
                    <strong>Please fix the following:</strong>
                    <ul>
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>