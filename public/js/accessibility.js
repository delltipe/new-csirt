/**
 * Accessibility Widget JavaScript
 * Handles: Font size, Line height, Letter spacing, Contrast, Grayscale
 * Storage: LocalStorage (persists across sessions)
 */

class AccessibilityWidget {
  constructor() {
    this.toggle = document.getElementById('accessibility-toggle');
    this.panel = document.getElementById('accessibility-panel');
    this.closeBtn = document.getElementById('accessibility-close');
    this.resetBtn = document.getElementById('accessibility-reset');
    
    // State
    this.state = {
      fontSize: 100,      // percentage
      lineHeight: 1.5,    // multiplier
      letterSpacing: 0,   // px
      contrast: 'normal', // normal | high | dark
      grayscale: false    // true | false
    };

    this.init();
  }

  init() {
    // Load saved preferences
    this.loadPreferences();
    
    // Bind events
    this.toggle.addEventListener('click', () => this.togglePanel());
    this.closeBtn.addEventListener('click', () => this.closePanel());
    this.resetBtn.addEventListener('click', () => this.reset());
    
    // Keyboard shortcut (CTRL+U)
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
        e.preventDefault();
        this.togglePanel();
      }
    });

    // Font size controls
    document.getElementById('font-decrease').addEventListener('click', () => this.adjustFontSize(-10));
    document.getElementById('font-increase').addEventListener('click', () => this.adjustFontSize(10));

    // Line height controls
    document.getElementById('line-height-decrease').addEventListener('click', () => this.adjustLineHeight(-0.2));
    document.getElementById('line-height-increase').addEventListener('click', () => this.adjustLineHeight(0.2));

    // Letter spacing controls
    document.getElementById('letter-spacing-decrease').addEventListener('click', () => this.adjustLetterSpacing(-1));
    document.getElementById('letter-spacing-increase').addEventListener('click', () => this.adjustLetterSpacing(1));

    // Contrast controls
    ['normal', 'high', 'dark'].forEach(mode => {
      document.getElementById(`contrast-${mode}`).addEventListener('click', () => this.setContrast(mode));
    });

    // Grayscale controls
    ['off', 'on'].forEach(mode => {
      document.getElementById(`grayscale-${mode}`).addEventListener('click', () => this.setGrayscale(mode === 'on'));
    });

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.accessibility-widget')) {
        this.closePanel();
      }
    });

    // Apply saved preferences
    this.applyPreferences();
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
    this.updateUI();
  }

  closePanel() {
    this.panel.setAttribute('hidden', '');
    this.toggle.setAttribute('aria-expanded', 'false');
  }

  // Font Size
  adjustFontSize(delta) {
    this.state.fontSize = Math.max(80, Math.min(150, this.state.fontSize + delta));
    document.getElementById('font-size-display').textContent = this.state.fontSize + '%';
    this.applyFontSize();
    this.savePreferences();
  }

  applyFontSize() {
    const scale = this.state.fontSize / 100;
    document.documentElement.style.fontSize = (16 * scale) + 'px';
  }

  // Line Height
  adjustLineHeight(delta) {
    this.state.lineHeight = Math.max(1.3, Math.min(2.0, parseFloat((this.state.lineHeight + delta).toFixed(1))));
    const labels = { 1.3: 'Kecil', 1.5: 'Normal', 1.7: 'Sedang', 1.9: 'Besar', 2: 'Sangat Besar' };
    document.getElementById('line-height-display').textContent = labels[this.state.lineHeight.toFixed(1)] || 'Custom';
    this.applyLineHeight();
    this.savePreferences();
  }

  applyLineHeight() {
    document.documentElement.style.setProperty('--accessibility-line-height', this.state.lineHeight);
    document.querySelectorAll('body, body *').forEach(el => {
      if (el.tagName !== 'SCRIPT' && el.tagName !== 'STYLE') {
        el.style.lineHeight = this.state.lineHeight;
      }
    });
  }

  // Letter Spacing
  adjustLetterSpacing(delta) {
    this.state.letterSpacing = Math.max(0, Math.min(4, this.state.letterSpacing + delta));
    const labels = { 0: 'Normal', 1: 'Sedang', 2: 'Besar', 3: 'Lebih Besar', 4: 'Maksimal' };
    document.getElementById('letter-spacing-display').textContent = labels[this.state.letterSpacing] || 'Custom';
    this.applyLetterSpacing();
    this.savePreferences();
  }

  applyLetterSpacing() {
    document.documentElement.style.setProperty('--accessibility-letter-spacing', this.state.letterSpacing + 'px');
    document.querySelectorAll('body *').forEach(el => {
      if (el.tagName !== 'SCRIPT' && el.tagName !== 'STYLE') {
        el.style.letterSpacing = this.state.letterSpacing + 'px';
      }
    });
  }

  // Contrast
  setContrast(mode) {
    this.state.contrast = mode;
    
    // Update button states
    ['normal', 'high', 'dark'].forEach(m => {
      const btn = document.getElementById(`contrast-${m}`);
      btn.setAttribute('aria-pressed', m === mode);
    });

    this.applyContrast();
    this.savePreferences();
  }

  applyContrast() {
    const html = document.documentElement;
    html.classList.remove('accessibility-contrast-high', 'accessibility-contrast-dark');

    if (this.state.contrast === 'high') {
      html.classList.add('accessibility-contrast-high');
    } else if (this.state.contrast === 'dark') {
      html.classList.add('accessibility-contrast-dark');
    }
  }

  // Grayscale
  setGrayscale(enabled) {
    this.state.grayscale = enabled;
    
    // Update button states
    document.getElementById('grayscale-off').setAttribute('aria-pressed', !enabled);
    document.getElementById('grayscale-on').setAttribute('aria-pressed', enabled);

    this.applyGrayscale();
    this.savePreferences();
  }

  applyGrayscale() {
    const html = document.documentElement;
    if (this.state.grayscale) {
      html.style.filter = 'grayscale(100%)';
    } else {
      html.style.filter = 'grayscale(0%)';
    }
  }

  // Apply all preferences
  applyPreferences() {
    this.applyFontSize();
    this.applyLineHeight();
    this.applyLetterSpacing();
    this.applyContrast();
    this.applyGrayscale();
  }

  // Update UI to reflect current state
  updateUI() {
    document.getElementById('font-size-display').textContent = this.state.fontSize + '%';
    
    const lineHeightLabels = { 1.3: 'Kecil', 1.5: 'Normal', 1.7: 'Sedang', 1.9: 'Besar', 2: 'Sangat Besar' };
    document.getElementById('line-height-display').textContent = lineHeightLabels[this.state.lineHeight.toFixed(1)] || 'Custom';
    
    const spacingLabels = { 0: 'Normal', 1: 'Sedang', 2: 'Besar', 3: 'Lebih Besar', 4: 'Maksimal' };
    document.getElementById('letter-spacing-display').textContent = spacingLabels[this.state.letterSpacing] || 'Custom';

    // Update contrast buttons
    ['normal', 'high', 'dark'].forEach(m => {
      document.getElementById(`contrast-${m}`).setAttribute('aria-pressed', m === this.state.contrast);
    });

    // Update grayscale buttons
    document.getElementById('grayscale-off').setAttribute('aria-pressed', !this.state.grayscale);
    document.getElementById('grayscale-on').setAttribute('aria-pressed', this.state.grayscale);
  }

  // Reset to defaults
  reset() {
    if (confirm('Atur ulang semua pengaturan aksesibilitas ke default?')) {
      this.state = {
        fontSize: 100,
        lineHeight: 1.5,
        letterSpacing: 0,
        contrast: 'normal',
        grayscale: false
      };
      
      // Clear stored preferences
      localStorage.removeItem('accessibilityState');
      
      this.applyPreferences();
      this.updateUI();
    }
  }

  // LocalStorage
  savePreferences() {
    localStorage.setItem('accessibilityState', JSON.stringify(this.state));
  }

  loadPreferences() {
    const saved = localStorage.getItem('accessibilityState');
    if (saved) {
      try {
        this.state = JSON.parse(saved);
      } catch (e) {
        console.error('Failed to load accessibility preferences:', e);
      }
    }
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new AccessibilityWidget();
});
