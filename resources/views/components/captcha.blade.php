{{-- components/captcha.blade.php — Math CAPTCHA widget (just numbers, e.g. "12 + 7 = ?") --}}
@props(['question'])

<style>
.captcha-field .captcha-row {
    display: grid;
    grid-template-columns: 140px 44px 1fr;
    gap: 12px;
    align-items: start;
}
.captcha-question {
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--mist, #F4F5F7);
    border: 1px solid var(--border, #D8DCE3);
    font-family: var(--font-body);
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: var(--ink, #0A0F1A);
    user-select: none;
}
.btn-captcha-refresh {
    width: 44px;
    height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--white);
    color: var(--navy, #003580);
    border: 1px solid var(--border, #D8DCE3);
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}
.btn-captcha-refresh:hover {
    background: var(--mist, #F4F5F7);
    border-color: var(--navy, #003580);
}
.btn-captcha-refresh:active {
    background: var(--navy-tint, #E8EEF6);
}
@media (max-width: 500px) {
    .captcha-field .captcha-row {
        grid-template-columns: 120px 44px 1fr;
        gap: 8px;
    }
}
</style>

<div class="form-field captcha-field">
    <label class="lapor-label" for="field-captcha-answer">
        Verifikasi <span class="req" aria-hidden="true">*</span>
    </label>
    <div class="captcha-row">
        <div class="captcha-question" id="captchaQuestion" aria-live="polite" aria-label="Soal verifikasi">{{ $question }}</div>
        <button type="button" class="btn-captcha-refresh" id="captchaRefresh" aria-label="Muat ulang verifikasi" title="Muat ulang">
            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
        </button>
        <input type="number" inputmode="numeric" autocomplete="off"
               id="field-captcha-answer" name="captcha_answer"
               class="lapor-input @error('captcha_answer') is-invalid @enderror"
               value="{{ old('captcha_answer') }}"
               placeholder="Jawaban"
               required
               aria-describedby="captchaHelp @error('captcha_answer') err-captcha-answer @enderror"
               @error('captcha_answer') aria-invalid="true" @enderror>
    </div>
    <div id="captchaHelp" class="field-hint" style="font-size:12px;color:var(--mid);margin-top:6px;">
        Selesaikan soal di atas. Klik tombol putar untuk soal baru.
    </div>
    @error('captcha_answer')
    <div class="field-error" id="err-captcha-answer" role="alert"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> {{ $message }}</div>
    @enderror
</div>

<script>
(function () {
    const btn = document.getElementById('captchaRefresh');
    const qEl = document.getElementById('captchaQuestion');
    const input = document.getElementById('field-captcha-answer');
    if (!btn || !qEl) return;
    btn.addEventListener('click', function () {
        btn.disabled = true;
        fetch("{{ route('captcha.refresh') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.question) {
                qEl.textContent = data.question;
                if (input) { input.value = ''; input.focus(); }
            }
        })
        .catch(function () {
            // silent fail, user can retry
        })
        .finally(function () { btn.disabled = false; });
    });
})();
</script>
