{{--
    Partial: admin/products/_barcode_field.blade.php

    Drop this into both create.blade.php and edit.blade.php
    wherever SKU sits (they belong together).

    Usage in create/edit form:
        @include('admin.products._barcode_field')
--}}

<div class="form-group">
    <label for="barcode">
        Physical Barcode / QR Value
        <span class="text-muted" style="font-size:11px;font-weight:400;">
            (EAN-13, UPC, GTIN, or any code printed on the product)
        </span>
    </label>

    <div class="input-group">
        {{-- The scanner types its output here (it behaves like a keyboard) --}}
        <input type="text"
               id="barcode"
               name="barcode"
               class="form-control @error('barcode') is-invalid @enderror"
               value="{{ old('barcode', $product->barcode ?? '') }}"
               placeholder="Scan the product barcode or type it manually…"
               autocomplete="off"
               spellcheck="false">

        {{-- Visual feedback button — does NOT submit, just clears the field --}}
        <div class="input-group-append">
            <button type="button"
                    id="btn-scan-ready"
                    class="btn btn-outline-secondary"
                    title="Click then scan the physical product">
                <i class="mdi mdi-barcode-scan"></i>
                <span id="scan-btn-label">Ready to Scan</span>
            </button>
            <button type="button"
                    class="btn btn-outline-danger"
                    onclick="clearBarcode()"
                    title="Clear">
                <i class="mdi mdi-close"></i>
            </button>
        </div>

        @error('barcode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <small class="form-text text-muted mt-1">
        <i class="mdi mdi-information-outline"></i>
        Click <strong>Ready to Scan</strong>, then point your scanner at the physical product.
        Leave blank if the product has no external barcode.
    </small>

    {{-- Live duplicate check feedback --}}
    <div id="barcode-feedback" class="mt-1" style="font-size:12px;"></div>
</div>

@push('scripts')
<script>
(function () {
    const input     = document.getElementById('barcode');
    const btn       = document.getElementById('btn-scan-ready');
    const btnLabel  = document.getElementById('scan-btn-label');
    const feedback  = document.getElementById('barcode-feedback');
    let dupTimer    = null;
    let scanMode    = false;

    /* ── Scan-ready mode ───────────────────────────────────────
       A USB/Bluetooth barcode scanner types its output like a keyboard,
       ending with Enter. We just need to make sure the input has focus.
    ─────────────────────────────────────────────────────────── */
    btn.addEventListener('click', function () {
        scanMode = true;
        input.value = '';
        input.focus();
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        btnLabel.textContent = 'Scanning… (point scanner now)';

        // Auto-reset after 15 s if nothing was scanned
        setTimeout(resetScanMode, 15000);
    });

    // Scanners send Enter after the barcode — detect it
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && scanMode) {
            e.preventDefault();
            resetScanMode();
            checkDuplicate();
        }
    });

    input.addEventListener('blur', () => { if (scanMode) resetScanMode(); });
    input.addEventListener('input', () => {
        clearTimeout(dupTimer);
        dupTimer = setTimeout(checkDuplicate, 500);
    });

    function resetScanMode() {
        scanMode = false;
        btn.classList.replace('btn-success', 'btn-outline-secondary');
        btnLabel.textContent = 'Ready to Scan';
    }

    function clearBarcode() {
        input.value = '';
        feedback.innerHTML = '';
        input.focus();
    }
    window.clearBarcode = clearBarcode; // expose for onclick

    /* ── Duplicate check ───────────────────────────────────────
       Hits an endpoint to see if another product already uses this barcode.
    ─────────────────────────────────────────────────────────── */
    function checkDuplicate() {
        const val = input.value.trim();
        if (val.length < 3) { feedback.innerHTML = ''; return; }

        const productId = '{{ $product->id ?? '' }}'; // empty on create
        const url = '{{ route('admin.products.check-barcode') }}'
                  + '?barcode=' + encodeURIComponent(val)
                  + (productId ? '&exclude=' + productId : '');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                if (data.taken) {
                    feedback.innerHTML = `<span class="text-danger">
                        <i class="mdi mdi-alert-circle-outline"></i>
                        This barcode is already assigned to
                        <strong>${data.product}</strong>.
                    </span>`;
                    input.classList.add('is-invalid');
                } else {
                    feedback.innerHTML = val.length > 0
                        ? `<span class="text-success">
                               <i class="mdi mdi-check-circle-outline"></i>
                               Barcode is available.
                           </span>`
                        : '';
                    input.classList.remove('is-invalid');
                }
            })
            .catch(() => { feedback.innerHTML = ''; });
    }
})();
</script>
@endpush
