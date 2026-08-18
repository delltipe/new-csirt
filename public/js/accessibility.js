/**
 * Accessibility Widget v2.0 — JavaScript
 * Rebuilt to match jakarta.go.id "Widget Aksesibilitas Version 2.0",
 * restyled on the project design tokens (see DESIGN_SYSTEM.md).
 *
 * Features: menampilkan posisi Voice (TTS), Perbesar/Perkecil Teks,
 * Skala Abu-Abu, Kontras+, Sembunyikan Gambar, Rata Tulisan,
 * Tulisan Dapat Dibaca, Tinggi Garis, Animasi Dijeda, Kursor,
 * Spasi Teks, Garis Bawahi Tautan, + Reset.
 * Storage: LocalStorage (key: accessibilityState)
 */

class AccessibilityWidget {
  constructor() {
    this.toggle = document.getElementById('accessibility-toggle');
    this.panel = document.getElementById('accessibility-panel');
    this.closeBtn = document.getElementById('circle_close_popup_dsb');
    this.resetBtn = document.getElementById('reset_pengaturan_all_dsb');

    // State
    this.state = {
      language: 'id',
      voice: false,
      fontSizeLevel: 0,      // 0..3  -> 100 / 115 / 130 / 145 %
      grayscale: false,
      contrast: 0,           // 0 normal | 1 high | 2 dark | 3 invert
      hideImages: false,
      textAlign: 0,          // 0 left | 1 center | 2 right | 3 justify
      readableFont: false,
      lineHeightLevel: 0,    // 0..2 -> 1.5 / 1.7 / 2.0
      pauseAnimations: false,
      largeCursor: false,
      letterSpacingLevel: 0, // 0..2 -> 0 / 1.5 / 3 px
      underlineLinks: 0      // 0 off | 1 on
    };

    const F = [100, 115, 130, 145];
    const LH = [1.5, 1.7, 2.0];
    const LS = [0, 1.5, 3];

    this.FONT_SIZES = F;
    this.LINE_HEIGHTS = LH;
    this.LETTER_SPACINGS = LS;

    this.init();
  }

  init() {
    if (!this.toggle || !this.panel) return;

    // Load saved preferences (with old-format migration)
    this.loadPreferences();

    // Bind events
    this.toggle.addEventListener('click', () => this.togglePanel());
    this.closeBtn.addEventListener('click', () => this.closePanel());
    this.resetBtn.addEventListener('click', () => this.reset());

    // Language dropdown
    const langBtn = document.getElementById('dropdown_bahasa_widget');
    const langPanel = document.getElementById('show_bahasa_widget_dsb');
    langBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const hidden = langPanel.hasAttribute('hidden');
      langPanel.toggleAttribute('hidden', !hidden);
      langBtn.setAttribute('aria-expanded', hidden ? 'true' : 'false');
    });

    // Keyboard shortcut (CTRL+U)
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
        e.preventDefault();
        this.togglePanel();
      }
    });

    // Toggle tiles (aria-pressed)
    this.bindToggle('action_moda_suara', () => this.setVoice(!this.state.voice));
    this.bindToggle('action_grey_scale', () => this.setGrayscale(!this.state.grayscale));
    this.bindToggle('action_hidden_image', () => this.setHideImages(!this.state.hideImages));
    this.bindToggle('action_tulisan_dapat_di_baca', () => this.setReadableFont(!this.state.readableFont));
    this.bindToggle('action_animate_pause', () => this.setPauseAnimations(!this.state.pauseAnimations));
    this.bindToggle('action_kursor', () => this.setLargeCursor(!this.state.largeCursor));

    // Gauge tiles
    const el = (id) => document.getElementById(id);

    el('action_perbesar_text').addEventListener('click', () => this.adjustFontSize(1));
    el('action_perkecil_text').addEventListener('click', () => this.adjustFontSize(-1));

    el('action_kontras').addEventListener('click', () => this.cycleContrast());
    el('action_perataan_text').addEventListener('click', () => this.cycleTextAlign());
    el('action_tulisan_line_height').addEventListener('click', () => this.cycleLineHeight());
    el('action_space_text').addEventListener('click', () => this.cycleLetterSpacing());
    el('action_garis_bawahi_tautan').addEventListener('click', () => this.cycleUnderlineLinks());

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.accessibility-widget')) {
        this.closePanel();
      }
    });

    // Stop TTS on page unload
    window.addEventListener('beforeunload', () => this.stopVoice());

    // Apply saved preferences
    this.applyPreferences();
    this.updateUI();
  }

  bindToggle(id, handler) {
    document.getElementById(id).addEventListener('click', handler);
  }

  togglePanel() {
    if (this.panel.hasAttribute('hidden')) {
      this.openPanel();
    } else {
      this.closePanel();
    }
  }

  openPanel() {
    this.panel.removeAttribute('hidden');
    this.toggle.setAttribute('aria-expanded', 'true');
  }

  closePanel() {
    this.panel.setAttribute('hidden', '');
    this.toggle.setAttribute('aria-expanded', 'false');
    this.stopVoice();
  }

  // Collection helpers for updates
  setTileActive(id, active) {
    document.getElementById(id).classList.toggle('active_box_menu_disabilitas', !!active);
  }

  setTilePressed(id, pressed) {
    const t = document.getElementById(id);
    t.setAttribute('aria-pressed', pressed ? 'true' : 'false');
    t.classList.toggle('active_box_menu_disabilitas', !!pressed);
  }

  updateStrip(id, level, total, label) {
    const container = document.getElementById(id);
    if (!container) return;
    const strips = container.children;
    const count = strips.length;
    for (let i = 0; i < count; i++) {
      const cls = i < level ? 'strip_loading_process_v' : 'strip_loading_unprocess_v';
      strips[i].className = cls + total;
    }
    container.setAttribute('aria-label', label + ': level ' + level + ' dari ' + total);
  }

  setVisible(ids, visibleIndex) {
    ids.forEach((id, i) => {
      const s = document.getElementById(id);
      // Explicit inline display so it wins over the .hidden_svg CSS rule.
      if (s) s.style.display = i === visibleIndex ? 'block' : 'none';
    });
  }

  // ----- Voice (Mode Suara — Web Speech API) -----
  setVoice(enabled) {
    this.state.voice = enabled;
    if (enabled) {
      this.speak();
    } else {
      this.stopVoice();
    }
    this.setTilePressed('action_moda_suara', this.state.voice);
    this.savePreferences();
  }

  speak() {
    const synth = window.speechSynthesis;
    if (!synth) return;
    this.stopVoice();
    const main = document.querySelector('main') || document.body;
    let text = (main.innerText || '').replace(/\s+/g, ' ').trim();
    if (!text) return;
    const max = 180;
    const chunks = [];
    while (text.length > max) {
      let cut = text.lastIndexOf(' ', max);
      if (cut <= 0) cut = max;
      chunks.push(text.slice(0, cut));
      text = text.slice(cut).trim();
    }
    if (text) chunks.push(text);
    chunks.forEach((part) => {
      const u = new SpeechSynthesisUtterance(part);
      u.lang = 'id-ID';
      u.rate = 0.95;
      u.pitch = 1;
      synth.speak(u);
    });
    this.setTilePressed('action_moda_suara', true);
    document.getElementById('action_moda_suara').classList.add('speaking');
  }

  stopVoice() {
    if (window.speechSynthesis) window.speechSynthesis.cancel();
    const tile = document.getElementById('action_moda_suara');
    if (tile) tile.classList.remove('speaking');
  }

  // ----- Font size -----
  adjustFontSize(delta) {
    this.state.fontSizeLevel = Math.max(0, Math.min(3, this.state.fontSizeLevel + delta));
    this.updateFontUI();
    this.applyFontSize();
    this.savePreferences();
  }

  updateFontUI() {
    const lvl = this.state.fontSizeLevel;
    const a = 'Ukuran teks';
    this.updateStrip('list_strip_loading_perbesar_text', lvl, 4, a);
    this.updateStrip('list_strip_loading_perkecil_text', lvl, 4, a);
    this.setTileActive('action_perbesar_text', lvl > 0);
  }

  applyFontSize() {
    const pct = this.FONT_SIZES[this.state.fontSizeLevel] / 100;
    const targets = [];
    document.querySelectorAll('body, body *').forEach((el) => {
      if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE') return;
      if (el.closest && el.closest('.accessibility-widget')) return;
      targets.push(el);
    });
    // Clear previous inline sizes, then rescale from the CSS base so each
    // level is idempotent (e.g. 145% is always 145% of the original size).
    targets.forEach((el) => { el.style.fontSize = ''; });
    targets.forEach((el) => {
      const cur = parseFloat(window.getComputedStyle(el).fontSize);
      if (cur > 0 && Number.isFinite(cur)) {
        el.style.fontSize = Math.round(cur * pct * 100) / 100 + 'px';
      }
    });
  }

  // ----- Line height -----
  cycleLineHeight() {
    this.state.lineHeightLevel = (this.state.lineHeightLevel + 1) % 3;
    this.updateLineHeightUI();
    this.applyLineHeight();
    this.savePreferences();
  }

  updateLineHeightUI() {
    const lvl = this.state.lineHeightLevel;
    this.updateStrip('list_strip_loading_action_tulisan_line_height', lvl, 3, 'Tinggi garis');
    this.setTileActive('action_tulisan_line_height', lvl > 0);
  }

  applyLineHeight() {
    const lh = this.LINE_HEIGHTS[this.state.lineHeightLevel];
    document.documentElement.style.setProperty('--accessibility-line-height', lh);
    document.querySelectorAll('body, body *').forEach((el) => {
      if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE') return;
      if (el.closest && el.closest('.accessibility-widget')) return;
      el.style.lineHeight = lh;
    });
  }

  // ----- Letter spacing -----
  cycleLetterSpacing() {
    this.state.letterSpacingLevel = (this.state.letterSpacingLevel + 1) % 3;
    this.updateLetterSpacingUI();
    this.applyLetterSpacing();
    this.savePreferences();
  }

  updateLetterSpacingUI() {
    const lvl = this.state.letterSpacingLevel;
    this.updateStrip('list_strip_loading_action_space_text', lvl, 3, 'Spasi teks');
    this.setTileActive('action_space_text', lvl > 0);
  }

  applyLetterSpacing() {
    const ls = this.LETTER_SPACINGS[this.state.letterSpacingLevel];
    document.documentElement.style.setProperty('--accessibility-letter-spacing', ls + 'px');
    document.querySelectorAll('body *').forEach((el) => {
      if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE') return;
      if (el.closest && el.closest('.accessibility-widget')) return;
      el.style.letterSpacing = ls + 'px';
    });
  }

  // ----- Contrast+ (Normal → High → Dark → Invert) -----
  cycleContrast() {
    this.state.contrast = (this.state.contrast + 1) % 4;
    this.updateContrastUI();
    this.applyContrast();
    this.savePreferences();
  }

  updateContrastUI() {
    const lvl = this.state.contrast;
    const icons = ['svg_kontras_multi', 'svg_kontras_klise', 'svg_kontras_warna', 'svg_balikan_warna'];
    this.setVisible(icons, lvl);
    this.updateStrip('list_strip_loading_action_kontras', lvl, 4, 'Level kontras');
    this.setTileActive('action_kontras', lvl > 0);
  }

  applyContrast() {
    const html = document.documentElement;
    html.classList.remove('accessibility-contrast-high', 'accessibility-contrast-dark', 'accessibility-invert');
    if (this.state.contrast === 1) {
      html.classList.add('accessibility-contrast-high');
    } else if (this.state.contrast === 2) {
      html.classList.add('accessibility-contrast-dark');
    } else if (this.state.contrast === 3) {
      html.classList.add('accessibility-invert');
    }
  }

  // ----- Text alignment (Left → Center → Right → Justify) -----
  cycleTextAlign() {
    this.state.textAlign = (this.state.textAlign + 1) % 4;
    this.updateTextAlignUI();
    this.applyTextAlign();
    this.savePreferences();
  }

  updateTextAlignUI() {
    const lvl = this.state.textAlign;
    const icons = ['svg_left_text_icon', 'svg_center_text_icon', 'svg_right_text_icon', 'svg_right_left_text_icon'];
    this.setVisible(icons, lvl);
    this.updateStrip('list_strip_loading_perataan_text', lvl, 4, 'Perataan tulisan');
    this.setTileActive('action_perataan_text', lvl > 0);
  }

  applyTextAlign() {
    const html = document.documentElement;
    html.classList.remove('accessibility-align-center', 'accessibility-align-right', 'accessibility-align-justify');
    if (this.state.textAlign === 1) {
      html.classList.add('accessibility-align-center');
    } else if (this.state.textAlign === 2) {
      html.classList.add('accessibility-align-right');
    } else if (this.state.textAlign === 3) {
      html.classList.add('accessibility-align-justify');
    }
  }

  // ----- Underline links (0/1) -----
  cycleUnderlineLinks() {
    this.state.underlineLinks = this.state.underlineLinks ? 0 : 1;
    this.updateUnderlineUI();
    this.applyUnderlineLinks();
    this.savePreferences();
  }

  updateUnderlineUI() {
    const lvl = this.state.underlineLinks;
    this.setVisible(['svg_decoration_link', 'svg_block_decoration_link'], lvl);
    this.updateStrip('list_strip_loading_action_garis_bawahi_tautan', lvl, 2, 'Garis bawahi tautan');
    this.setTileActive('action_garis_bawahi_tautan', lvl > 0);
  }

  applyUnderlineLinks() {
    document.documentElement.classList.toggle('accessibility-underline-links', this.state.underlineLinks > 0);
  }

  // ----- Simple toggles -----
  setGrayscale(enabled) {
    this.state.grayscale = enabled;
    document.documentElement.classList.toggle('accessibility-grayscale', enabled);
    this.setTilePressed('action_grey_scale', enabled);
    this.savePreferences();
  }

  setHideImages(enabled) {
    this.state.hideImages = enabled;
    document.documentElement.classList.toggle('accessibility-hide-images', enabled);
    this.setTilePressed('action_hidden_image', enabled);
    this.savePreferences();
  }

  setReadableFont(enabled) {
    this.state.readableFont = enabled;
    document.documentElement.classList.toggle('accessibility-readable-font', enabled);
    this.setTilePressed('action_tulisan_dapat_di_baca', enabled);
    this.savePreferences();
  }

  setPauseAnimations(enabled) {
    this.state.pauseAnimations = enabled;
    document.documentElement.classList.toggle('accessibility-pause-animations', enabled);
    this.setTilePressed('action_animate_pause', enabled);
    if (enabled) {
      this.setVisible(['svg_animasi_pause', 'svg_animasi_play'], 1);
    } else {
      this.setVisible(['svg_animasi_pause', 'svg_animasi_play'], 0);
    }
    this.savePreferences();
  }

  setLargeCursor(enabled) {
    this.state.largeCursor = enabled;
    document.documentElement.classList.toggle('accessibility-large-cursor', enabled);
    this.setTilePressed('action_kursor', enabled);
    this.savePreferences();
  }

  // ----- Apply all preferences -----
  applyPreferences() {
    this.applyFontSize();
    this.applyLineHeight();
    this.applyLetterSpacing();
    this.applyContrast();
    this.applyTextAlign();
    this.applyUnderlineLinks();
    document.documentElement.classList.toggle('accessibility-grayscale', this.state.grayscale);
    document.documentElement.classList.toggle('accessibility-hide-images', this.state.hideImages);
    document.documentElement.classList.toggle('accessibility-readable-font', this.state.readableFont);
    document.documentElement.classList.toggle('accessibility-pause-animations', this.state.pauseAnimations);
    document.documentElement.classList.toggle('accessibility-large-cursor', this.state.largeCursor);
    this.setVisible(['svg_animasi_pause', 'svg_animasi_play'], this.state.pauseAnimations ? 1 : 0);
  }

  updateUI() {
    this.updateFontUI();
    this.updateLineHeightUI();
    this.updateLetterSpacingUI();
    this.updateContrastUI();
    this.updateTextAlignUI();
    this.updateUnderlineUI();

    this.setTilePressed('action_moda_suara', this.state.voice);
    this.setTilePressed('action_grey_scale', this.state.grayscale);
    this.setTilePressed('action_hidden_image', this.state.hideImages);
    this.setTilePressed('action_tulisan_dapat_di_baca', this.state.readableFont);
    this.setTilePressed('action_animate_pause', this.state.pauseAnimations);
    this.setTilePressed('action_kursor', this.state.largeCursor);

    if (this.state.voice) this.setTilePressed('action_moda_suara', true);
  }

  // ----- Reset to defaults -----
  reset() {
    if (confirm('Atur ulang semua pengaturan aksesibilitas ke default?')) {
      this.stopVoice();
      this.state = {
        language: 'id',
        voice: false,
        fontSizeLevel: 0,
        grayscale: false,
        contrast: 0,
        hideImages: false,
        textAlign: 0,
        readableFont: false,
        lineHeightLevel: 0,
        pauseAnimations: false,
        largeCursor: false,
        letterSpacingLevel: 0,
        underlineLinks: 0
      };

      // Clear stored preferences
      localStorage.removeItem('accessibilityState');

      this.applyPreferences();
      this.updateUI();
    }
  }

  // ----- LocalStorage -----
  savePreferences() {
    localStorage.setItem('accessibilityState', JSON.stringify(this.state));
  }

  loadPreferences() {
    const saved = localStorage.getItem('accessibilityState');
    if (!saved) return;
    try {
      const raw = JSON.parse(saved);
      if (raw.fontSizeLevel !== undefined) {
        this.state = Object.assign({}, this.state, raw);
        return;
      }
      // Old v1 format migration
      if (typeof raw.fontSize === 'number') {
        const F = this.FONT_SIZES;
        let lvl = 0;
        for (let i = 0; i < F.length; i++) { if (raw.fontSize >= F[i]) lvl = i; }
        this.state.fontSizeLevel = lvl;
      }
      if (typeof raw.lineHeight === 'number') {
        const LH = this.LINE_HEIGHTS;
        let lvl = 0;
        for (let i = 0; i < LH.length; i++) { if (raw.lineHeight >= LH[i]) lvl = i; }
        this.state.lineHeightLevel = lvl;
      }
      if (typeof raw.letterSpacing === 'number') {
        this.state.letterSpacingLevel = raw.letterSpacing >= 3 ? 2 : (raw.letterSpacing >= 1 ? 1 : 0);
      }
      if (raw.contrast === 'high') this.state.contrast = 1;
      if (raw.contrast === 'dark') this.state.contrast = 2;
      this.state.grayscale = !!raw.grayscale;
    } catch (e) {
      console.error('Failed to load accessibility preferences:', e);
    }
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new AccessibilityWidget();
});